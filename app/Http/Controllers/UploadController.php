<?php

namespace App\Http\Controllers;

use App\Models\{Currency, Customer, PaymentStatus, PaymentType, Transaction};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{DB, Http, Validator};
use Illuminate\Support\Str;
use Throwable;

class UploadController extends Controller
{
    const CHUNK_SIZE = 500;
    const MAX_FILE_SIZE = 2048; // 2MB in KB

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            $file = $request->file('csv_file');
            return $this->processCsvFile($file);
        } catch (Throwable $e) {
            return $this->errorResponse('Error processing file: ' . $e->getMessage());
        }
    }

    protected function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'csv_file' => [
                'required',
                'file',
                'mimes:csv',
                'max:' . self::MAX_FILE_SIZE
            ]
        ]);
    }

    protected function processCsvFile($file)
    {
        $path = $file->getRealPath();

        $data = array_map('str_getcsv', file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        if (empty($data)) {
            return $this->errorResponse('CSV file is empty');
        }

        $header = array_shift($data);
        $referenceData = $this->getReferenceData();

        $results = [
            'processed' => 0,
            'duplicates' => 0,
            'invalid' => 0
        ];
        DB::beginTransaction();
        try {
            $tempTransactions = [];
            $customersToCreate = [];
            $transactionsToInsert = [];
            foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
                foreach ($chunk as $row) {
                    $record = $this->parseRecord($header, $row);

                    if (!$this->isValidRecord($record)) {

                        $results['invalid']++;
                        continue;
                    }

                    $transactionKey = $this->createTransactionKey($record);
                    if (isset($tempTransactions[$transactionKey])) {
                        $results['duplicates']++;
                        continue;
                    }
                    $tempTransactions[$transactionKey] = true;
                    $customerKey = $record['email'];
                    if (!isset($customersToCreate[$customerKey])) {
                        $customersToCreate[$customerKey] = [
                            'email' => $record['email'],
                            'customer_name' => $record['customer_name']
                        ];
                    }

                    $transactionsToInsert[] = $this->prepareTransactionData(
                        $customerKey,
                        $record,
                        $referenceData
                    );
                }

                if ($results['invalid'] > 0 || $results['duplicates'] > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => sprintf(
                            'Found %d invalid and %d duplicate records - no data was inserted',
                            $results['invalid'],
                            $results['duplicates']
                        ),
                        'processed' => 0,
                        'invalid' => $results['invalid'],
                        'duplicates' => $results['duplicates']
                    ], Response::HTTP_CONFLICT);
                }
            }

            $customerIds = $this->bulkGetOrCreateCustomers($customersToCreate);

            foreach ($transactionsToInsert as $key => &$transaction) {
                $email = $transaction['customer_id'];
                $transaction['customer_id'] = $customerIds[$transaction['customer_id']];

                $record = $this->reconstructRecordFromTransaction($email, $transaction, $referenceData);

                if ($this->isDuplicateTransaction($transaction['customer_id'], $record, $referenceData)) {
                    $results['duplicates']++;
                    unset($transactionsToInsert[$key]);
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => sprintf(
                            'Found %d invalid and %d duplicate records - no data was inserted',
                            $results['invalid'],
                            $results['duplicates']
                        ),
                        'processed' => 0,
                        'invalid' => $results['invalid'],
                        'duplicates' => $results['duplicates']
                    ], Response::HTTP_CONFLICT);
                }
            }

            // Bulk insert all valid transactions
            if (!empty($transactionsToInsert)) {
                Transaction::insert($transactionsToInsert);
                $results['processed'] = count($transactionsToInsert);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'File processed successfully',
                ...$results
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'There is an issue while uploading.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    protected function reconstructRecordFromTransaction($email, $transaction, $referenceData)
    {
        return [
            'email' => $email,
            'customer_id' => $transaction['customer_id'],
            'last4' => $transaction['last4'],
            'amount' => $transaction['amount'],
            'currency' => array_flip($referenceData['currencies'])[$transaction['currency_id']] ?? '',
            'type' => array_flip($referenceData['paymentTypes'])[$transaction['payment_type_id']] ?? '',
            'status' => array_flip($referenceData['paymentStatuses'])[$transaction['payment_status_id']] ?? '',
            'transaction_date' => $transaction['transaction_date']
        ];
    }
    protected function createTransactionKey($record)
    {
        return md5(implode('|', [
            $record['email'],
            $record['last4'],
            $record['amount'],
            $record['currency'],
            $record['type'],
            $this->checkDateFormats($record['transaction_date'])
        ]));
    }
    protected function bulkGetOrCreateCustomers(array $customersData): array
    {
        $existing = Customer::whereIn('email', array_keys($customersData))
            ->pluck('id', 'email')
            ->toArray();

        $newCustomers = [];
        foreach ($customersData as $email => $data) {
            if (!isset($existing[$email])) {
                $newCustomers[] = $data;
            }
        }

        if (!empty($newCustomers)) {
            Customer::insert($newCustomers);
            $newlyCreated = Customer::whereIn('email', array_keys($customersData))
                ->whereNotIn('id', $existing)
                ->pluck('id', 'email')
                ->toArray();
            $existing += $newlyCreated;
        }

        return $existing;
    }
    protected function getReferenceData()
    {
        return [
            'currencies' => Currency::pluck('id', DB::raw('LOWER(code)'))->all(),
            'paymentTypes' => PaymentType::pluck('id', DB::raw('LOWER(code)'))->all(),
            'paymentStatuses' => PaymentStatus::pluck('id', DB::raw('LOWER(code)'))->all(),
        ];
    }

    protected function parseRecord($header, $row)
    {
        return array_combine($header, $row);
    }

    protected function isValidRecord($record)
    {
        return !empty($record['email']) && !empty($record['customer_name']);
    }

    protected function getCustomerId($record)
    {
        $customer = Customer::where('email', $record['email'])->first();

        if ($customer) {
            return $customer->id;
        }

        return null;
    }
    protected function isDuplicateTransaction($customerId, $record, $referenceData)
    {
        return Transaction::where([
            'customer_id' => $customerId,
            'last4' => $record['last4'],
            'amount' => $record['amount'],
            'currency_id' => $referenceData['currencies'][Str::lower($record['currency'])] ?? 0,
            'payment_type_id' => $referenceData['paymentTypes'][Str::lower($record['type'])] ?? 0,
            'payment_status_id' => $referenceData['paymentStatuses'][Str::lower($record['status'])] ?? 0,
            'transaction_date' => $this->checkDateFormats($record['transaction_date'])
        ])->exists();
    }

    protected function prepareTransactionData($customerId, $record, $referenceData)
    {
        return [
            'customer_id' => $customerId,
            'amount' => $record['amount'],
            'last4' => $record['last4'],
            'currency_id' => $referenceData['currencies'][Str::lower($record['currency'])] ?? 0,
            'payment_type_id' => $referenceData['paymentTypes'][Str::lower($record['type'])] ?? 0,
            'payment_status_id' => $referenceData['paymentStatuses'][Str::lower($record['status'])] ?? 0,
            'transaction_date' => $this->checkDateFormats($record['transaction_date']),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    protected function validationErrorResponse($validator)
    {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    protected function successResponse($data)
    {
        return response()->json([
            'status' => 'completed',
            'processed' => $data['processed'],
            'duplicates' => $data['duplicates'],
            'invalid' => $data['invalid']
        ]);
    }

    protected function errorResponse($message)
    {
        return redirect()->back()
            ->with('error', $message);
    }


    protected function checkDateFormats(string $dateString): ?string
    {
        $date = null;
        // Check for MySQL format (YYYY-MM-DD HH:MM:SS)
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dateString)) {
            $date = $dateString;
            return $date;
        }

        // Check for US format (M/D/YYYY HH:MM)
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4} \d{2}:\d{2}$/', $dateString)) {
            try {
                $date = Carbon::createFromFormat('n/j/Y H:i', $dateString)
                    ->format('Y-m-d H:i:s');
                return $date;
            } catch (\Exception $e) {
                // Invalid US format date
            }
        }

        return $date;
    }
}
