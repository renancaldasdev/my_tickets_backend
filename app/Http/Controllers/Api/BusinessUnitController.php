<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Identity\Services\BusinessUnitService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessUnitRequest;
use App\Http\Requests\UpdateBusinessUnitRequest;
use App\Http\Resources\BusinessUnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BusinessUnitController extends Controller
{
    public function __construct(
        protected BusinessUnitService $businessUnitService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $businessUnits = $this->businessUnitService->listBusinessUnits();

        return BusinessUnitResource::collection($businessUnits);
    }

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

    public function show(string $slug, Request $request): BusinessUnitResource
    {
        $manager = $request->user();

        $businessUnit = $this->businessUnitService->getBusinessUnit($slug, $manager);

        return new BusinessUnitResource($businessUnit);
    }

    public function update(UpdateBusinessUnitRequest $request, string $slug): \Illuminate\Http\JsonResponse
    {
        $manager = $request->user();
        $validatedData = $request->validated();

        $businessUnit = $this->businessUnitService->updateBusinessUnit($slug, $validatedData, $manager);

        return response()->json([
            'message' => 'Unidade de Negócio atualizada com sucesso!',
            'data' => new BusinessUnitResource($businessUnit),
        ], 200);
    }

    public function deactivate(Request $request): \Illuminate\Http\JsonResponse
    {
        $manager = $request->user();
        $slug = $request->route('slug');
        $businessUnit = $this->businessUnitService->deactivateBusinessUnit($slug, $manager);

        return response()->json([
            'message' => 'Unidade de Negócio desativada com sucesso!',
            'data' => new BusinessUnitResource($businessUnit),
        ], 200);
    }
}
