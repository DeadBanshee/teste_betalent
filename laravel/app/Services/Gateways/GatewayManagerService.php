<?php

namespace App\Services\Gateways;

use Exception;

class GatewayManagerService
{
    protected $gateway1;
    protected $gateway2;

    public function __construct(Gateway1Service $gateway1, Gateway2Service $gateway2)
    {
        $this->gateway1 = $gateway1;
        $this->gateway2 = $gateway2;
    }

    public function processPayment(array $data)
    {
        try {
            return [
                'gateway' => 'gateway_1',
                'response' => $this->gateway1->createTransaction($data),
            ];
        } catch (Exception $e) {
            try {
                return [
                    'gateway' => 'gateway_2',
                    'response' => $this->gateway2->createTransaction($data),
                ];
            } catch (Exception $e2) {
                throw new Exception('Todos os gateways falharam na transação.');
            }
        }
    }

    public function refundPayment(array $data, string $gatewayName)
    {
        return [
            'status' => 'success',
            'gateway' => $gatewayName,
            'response' => [
                'refund_id' => 'RF-' . rand(1000, 9999),
                'status' => 'refunded',
                'amount' => $data['amount'],
                'external_id' => $data['external_id'],
            ],
        ];
    }
}
