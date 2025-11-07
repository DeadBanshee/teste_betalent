<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Gateways\GatewayManagerService;
use App\Models\Transaction;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\TransactionProduct;
use Exception;

class GatewayController extends Controller
{
    protected $gatewayManager;

    public function __construct(GatewayManagerService $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'cardNumber' => 'required|string|size:16',
            'cvv' => 'required|string|min:3|max:3',
            'client_id' => 'required|integer|exists:clients,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            
            //calculo do valor total baseado nos produtos e quantidades da requisição
            $totalAmount = 0;
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalAmount += $product->amount * $item['quantity'];
            }

            //prepara a requisição que vai ser enviada para os gateways
            $paymentData = [
                'amount' => $totalAmount,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'card_number' => $validated['cardNumber'],
                'cvv' => $validated['cvv'],
            ];

            $result = $this->gatewayManager->processPayment($paymentData);

            $gateway = Gateway::where('name', $result['gateway'])->firstOrFail();
            //insere no banco o registro da transação
            $transaction = Transaction::create([
                'client_id' => $validated['client_id'],
                'client' => $validated['name'],
                'gateway_id' => $gateway->id,
                'external_id' => $result['response']['id'] ?? null,
                'status' => 'success',
                'amount' => $totalAmount,
                'card_last_numbers' => substr($validated['cardNumber'], -4),
            ]);
            //insere no banco cada produto da transação 
            foreach ($validated['products'] as $item) {
                TransactionProduct::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'Pagamento processado com sucesso',
                'transaction' => $transaction,
                'gateway_response' => $result['response'],
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar pagamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $gateway = Gateway::findOrFail($id);
            $gateway->is_active = $validated['is_active'];
            $gateway->save();

            return response()->json([
                'message' => 'Status do gateway atualizado com sucesso',
                'gateway' => $gateway
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar status do gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePriority(Request $request, $id)
    {
        $validated = $request->validate([
            'priority' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $gateway = Gateway::findOrFail($id);
            $newPriority = $validated['priority'];
            $oldPriority = $gateway->priority;

            if ($newPriority == $oldPriority) {
                return response()->json([
                    'message' => 'A prioridade já está definida com esse valor',
                    'gateway' => $gateway
                ]);
            }

            if ($newPriority < $oldPriority) {
                // Mover outros gateways para baixo
                Gateway::where('priority', '>=', $newPriority)
                    ->where('priority', '<', $oldPriority)
                    ->increment('priority');
            } else {
                // Mover outros gateways para cima
                Gateway::where('priority', '<=', $newPriority)
                    ->where('priority', '>', $oldPriority)
                    ->decrement('priority');
            }

            // Atualiza a prioridade do gateway alvo
            $gateway->priority = $newPriority;
            $gateway->save();

            DB::commit();

            return response()->json([
                'message' => 'Prioridade do gateway atualizada com sucesso',
                'gateway' => $gateway
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao atualizar prioridade do gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function refundTransaction($id)
    {
        try {
            $transaction = Transaction::with('gateway')->findOrFail($id);

            $gateway = $transaction->gateway;

            // Simulação do reembolso — você pode chamar o mesmo GatewayManager
            $result = $this->gatewayManager->refundPayment([
                'external_id' => $transaction->external_id,
                'amount' => $transaction->amount,
            ], $gateway->name);

            // Atualiza status no banco
            $transaction->update(['status' => 'refunded']);

            return response()->json([
                'message' => 'Reembolso realizado com sucesso.',
                'transaction' => $transaction,
                'gateway_response' => $result,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar reembolso',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



}
