<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Domains\Identity\Services\RegisterCustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomerRequest;
use App\Http\Resources\RegisterCustomerResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected RegisterCustomerService $registerService
    ) {}

    public function register(RegisterCustomerRequest $request): JsonResponse
    {
        $result = $this->registerService->handle($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Cadastro realizado com sucesso!',
            'data' => [
                'token' => $result['token'],
                'user' => new RegisterCustomerResource($result['user']),
            ],
        ], 201);
    }
}
