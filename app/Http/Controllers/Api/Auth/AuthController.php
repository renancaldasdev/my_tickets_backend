<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Services\ForgotPasswordService;
use App\Domains\Identity\Services\LoginService;
use App\Domains\Identity\Services\LogoutService;
use App\Domains\Identity\Services\RegisterCustomerService;
use App\Domains\Identity\Services\ResendVerificationEmailService;
use App\Domains\Identity\Services\ResetPasswordService;
use App\Domains\Identity\Services\VerifyEmailService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterCustomerRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\AuthUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected RegisterCustomerService $registerService,
        protected LoginService $loginService,
        protected LogoutService $logoutService,
        protected ForgotPasswordService $forgotPasswordService,
        protected ResetPasswordService $resetPasswordService,
        protected VerifyEmailService $verifyEmailService,
        protected ResendVerificationEmailService $resendVerificationEmailService
    ) {}

    public function register(RegisterCustomerRequest $request): JsonResponse
    {
        $result = $this->registerService->handle($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Cadastro realizado com sucesso!',
            'data' => [
                'token' => $result['token'],
                'user' => new AuthUserResource($result['user']),
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->loginService->handle($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Login realizado com sucesso!',
            'data' => [
                'token' => $result['token'],
                'user' => new AuthUserResource($result['user']),
            ],
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->logoutService->handle($user);

        return response()->json([
            'type' => 'success',
            'message' => 'Logout realizado com sucesso!',
        ], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->forgotPasswordService->handle($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Enviamos um link de recuperação para o seu e-mail.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->resetPasswordService->handle($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Sua senha foi redefinida com sucesso.',
        ]);
    }

    public function verifyEmail(string $id, string $hash): JsonResponse
    {
        $this->verifyEmailService->handle((int) $id, $hash);

        return response()->json([
            'type' => 'success',
            'message' => 'E-mail verificado com sucesso.',
        ]);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->resendVerificationEmailService->handle($user);

        return response()->json([
            'type' => 'success',
            'message' => 'Novo link de verificação enviado para o seu e-mail.',
        ]);
    }
}
