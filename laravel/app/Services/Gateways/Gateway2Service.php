<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class Gateway2Service
{
    protected $baseUrl;
    protected $authToken;
    protected $authSecret;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:3002';
        $this->authToken = 'tk_f2198cc671b5289fa856';
        $this->authSecret = '3d15e8ed6131446ea7e3456728b1211f';
    }

    public function createTransaction(array $data)
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => $this->authToken,
            'Gateway-Auth-Secret' => $this->authSecret,
        ])->post("{$this->baseUrl}/transacoes", [
            'valor' => $data['amount'],
            'nome' => $data['name'],
            'email' => $data['email'],
            'numeroCartao' => $data['cardNumber'], // 🔧 corrigido
            'cvv' => $data['cvv'],
        ]);

        if ($response->failed()) {
            throw new \Exception('Falha ao criar transação no Gateway 2');
        }

        return $response->json();
    }
}
