<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class Gateway1Service
{
    protected $baseUrl;
    protected $email;
    protected $token;
    protected $authToken;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:3001';
        $this->email = 'dev@betalent.tech';
        $this->token = 'FEC9BB078BF338F464F96B48089EB498';
    }

    protected function authenticate()
    {
        $response = Http::post("{$this->baseUrl}/login", [
            'email' => $this->email,
            'token' => $this->token,
        ]);

        if ($response->failed()) {
            throw new \Exception('Falha ao autenticar no Gateway 1');
        }

        $this->authToken = $response->json('token');
    }

    public function createTransaction(array $data)
    {
        if (!$this->authToken) {
            $this->authenticate();
        }

        $response = Http::withToken($this->authToken)
            ->post("{$this->baseUrl}/transactions", [
                'amount' => (int) round($data['amount'] * 100),
                'name' => $data['name'],
                'email' => $data['email'],
                'cardNumber' => $data['card_number'],
                'cvv' => $data['cvv'],
            ]);

        if ($response->failed()) {
            throw new \Exception('Falha ao criar transação no Gateway 1');
        }

        return $response->json();
    }
}
