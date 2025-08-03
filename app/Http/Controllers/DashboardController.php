<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Total transactions data
        $totalTransactions = Transaction::selectRaw('
            COUNT(*) as count,
            SUM(amount) as total_amount,
            AVG(amount) as avg_amount
        ')->first();

        // Transactions by status
        $transactionsByStatus = Transaction::with('paymentStatus')
            ->selectRaw('
                payment_status_id,
                COUNT(*) as count,
                SUM(amount) as total_amount
            ')
            ->groupBy('payment_status_id')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'status_name' => $item->paymentStatus->code,
                    'count' => $item->count,
                    'total_amount' => $item->total_amount ?? 0,
                ];
            });

        // // Transactions by currency
        $transactionsByCurrency = Transaction::with('currency')
            ->selectRaw('
                currency_id,
                COUNT(*) as count,
                SUM(amount) as total_amount
            ')
            ->groupBy('currency_id')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'currency_name' => $item->currency->code,
                    'count' => $item->count,
                    'total_amount' => $item->total_amount ?? 0,
                ];
            });
        // Transactions over time (last 30 days)
        $transactionsOverTime = Transaction::selectRaw('
            DATE(transaction_date) as date,
            COUNT(*) as count,
            SUM(amount) as total_amount
        ')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        return Inertia::render('Dashboard', [
            'charts' => [
                'totalTransactions' => $totalTransactions,
                'byStatus' => $transactionsByStatus,
                'overTime' => $transactionsOverTime,
                'byCurrency' => $transactionsByCurrency,
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
