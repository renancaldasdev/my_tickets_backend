<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Identity\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->listUsers();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $manager = $request->user();

        $user = $this->userService->createUser($request->validated(), $manager);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $user = $this->userService->getUser($uuid);

        return response()->json([
            'data' => new UserResource($user),
        ], 200);
    }

    public function update(UpdateUserRequest $request, string $uuid): JsonResponse
    {
        $user = $this->userService->updateUser($uuid, $request->validated());

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'data' => new UserResource($user),
        ], 200);
    }

    public function deactivate(string $uuid): JsonResponse
    {
        $user = $this->userService->deactivateUser($uuid);

        return response()->json([
            'message' => 'Usuário desativado com sucesso!',
            'data' => new UserResource($user),
        ], 200);
    }
}
