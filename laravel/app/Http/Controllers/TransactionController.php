<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Client;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['client', 'gateway', 'products'])->get();

        return response()->json($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['client', 'gateway', 'products'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transação não encontrada'], 404);
        }

        return response()->json($transaction);
    }

    public function listByClient($clientId)
    {
        $client = Client::find($clientId);

        if (!$client) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $transactions = Transaction::with(['gateway', 'products'])
            ->where('client_id', $clientId)
            ->get();

        return response()->json([
            'client' => $client,
            'transactions' => $transactions
        ]);
    }
}
