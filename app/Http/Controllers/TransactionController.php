<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display user's transaction history
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with('product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }
}
