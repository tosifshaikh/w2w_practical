<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'currency', 'paymentType', 'paymentStatus']);

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('amount', 'like', '%'.$request->search.'%')
                    ->orWhere('last4', 'like', '%'.$request->search.'%')
                    ->orWhere('id', 'like', '%'.$request->search.'%')
                    ->orWhereHas('customer', function ($q) use ($request) {
                        $q->where('customer_name', 'like', '%'.$request->search.'%')
                            ->orWhere('email', 'like', '%'.$request->search.'%');
                    });
            });
        }
        if ($request->has('currency_id') && $request->currency_id) {
            $query->where('currency_id', $request->currency_id);
        }

        if ($request->has('payment_type_id') && $request->payment_type_id) {
            $query->where('payment_type_id', $request->payment_type_id);
        }

        if ($request->has('payment_status_id') && $request->payment_status_id) {
            $query->where('payment_status_id', $request->payment_status_id);
        }
        $transactions = $query->orderBy('id', 'desc')->paginate(20);
        $transactions->getCollection()->each(function ($transaction) {
            $transaction->append('formatted_transaction_date');
        });

        return Inertia::render('Transaction/Transactions', [
            'transactions' => $transactions,
            'filters' => $request->only([
                'search',
                'currency_id',
                'payment_type_id',
                'payment_status_id',
            ]),
            'pagination' => [
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
            'dropdowns' => [
                'currencies' => Currency::all(['id', 'code']),
                'paymentTypes' => PaymentType::all(['id', 'code']),
                'paymentStatuses' => PaymentStatus::all(['id', 'code']),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
