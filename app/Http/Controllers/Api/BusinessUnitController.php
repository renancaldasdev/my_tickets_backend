<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Identity\Services\BusinessUnitService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessUnitRequest;
use App\Http\Resources\BusinessUnitResource;
use Illuminate\Http\JsonResponse;

class BusinessUnitController extends Controller
{
    public function __construct(
        protected BusinessUnitService $businessUnitService
    ) {}

    public function store(StoreBusinessUnitRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $manager = $request->user();

        $businessUnit = $this->businessUnitService->createBusinessUnit($validatedData, $manager);

        return response()->json([
            'message' => 'Unidade de Negócio criada com sucesso!',
            'data' => new BusinessUnitResource($businessUnit),
        ], 201);
    }
}
