<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VacationPlanController;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login'])
    ->name('login');

// Grupo de rotas protegidas por autenticação
Route::middleware(['auth:api', 'throttle:60,1', 'validateVacationPlan'])->group(function () {

    // Listar todos os planos de férias
    Route::get('vacation-plans', [VacationPlanController::class, 'index'])
        ->name('vacation-plans.index');

    // Criar um novo plano de férias
    Route::post('vacation-plans', [VacationPlanController::class, 'store'])
        ->name('vacation-plans.store');

    // Exibir um plano de férias específico
    Route::get('vacation-plans/{id}', [VacationPlanController::class, 'show'])
        ->name('vacation-plans.show')
        ->where('id', '[0-9]+'); 

    // Atualizar um plano de férias específico
    Route::put('vacation-plans/{id}', [VacationPlanController::class, 'update'])
        ->name('vacation-plans.update')
        ->where('id', '[0-9]+'); 

    // Deletar um plano de férias específico
    Route::delete('vacation-plans/{id}', [VacationPlanController::class, 'destroy'])
        ->name('vacation-plans.destroy')
        ->where('id', '[0-9]+'); 

    // Gerar PDF de um plano de férias específico
    Route::get('vacation-plans/{id}/pdf', [VacationPlanController::class, 'generatePdf'])
        ->name('vacation-plans.generatePdf')
        ->where('id', '[0-9]+');
});

// Rota de fallback para métodos não suportados ou rotas inexistentes
Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found or Method not supported',
        'message' => 'The requested method or route does not exist. Please check the API documentation for valid routes and methods.'
    ], 404);
});