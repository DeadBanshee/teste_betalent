<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

//ROTA DE TESTE DE CONEXÃO
Route::get('/ping', function () {
    return response()->json(['status' => 'pong']);
});

//ROTA DE LOGIN/REGISTER
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


//ROTA DE PAGAMENTOS (pública)
Route::post('/payments', [GatewayController::class, 'processPayment']);

Route::middleware('auth:sanctum')->group(function () {

    //ROTAS DO USUÁRIO
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);


    //ROTAS DO CLIENTE
    Route::apiResource('clients', ClientController::class);

    //ROTAS DE GATEWAY E PAGAMENTOS
    Route::patch('/gateways/{id}/status', [GatewayController::class, 'updateStatus']);
    Route::patch('/gateways/{id}/priority', [GatewayController::class, 'updatePriority']);
    Route::post('/transactions/{transaction}/chargeback', [GatewayController::class, 'refundTransaction']);

    //ROTAS DOS PRODUTOS
    Route::apiResource('/products', ProductController::class);

    //AUDITORIA DAS TRANSAÇÕES
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/clients/{id}/transactions', [TransactionController::class, 'listByClient']);
});
