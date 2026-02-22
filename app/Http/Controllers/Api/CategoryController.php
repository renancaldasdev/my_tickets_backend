<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Core\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->listCategories();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $manager = $request->user();
        $validatedData = $request->validated();

        $category = $this->categoryService->createCategory($validatedData, $manager);

        return response()->json([
            'message' => 'Categoria criada com sucesso!',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(string $uuid): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->getCategory($uuid);

        return response()->json([
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function update(UpdateCategoryRequest $request, string $uuid): JsonResponse
    {
        $validatedData = $request->validated();
        $manager = $request->user();

        $categoryUpdate = $this->categoryService->updateCategory($uuid, $validatedData, $manager);

        return response()->json([
            'message' => 'Categoria atualizada com sucesso!',
            'data' => new CategoryResource($categoryUpdate),

        ], 200);
    }

    public function deactivate(string $uuid): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->deactivateCategory($uuid);

        return response()->json([
            'message' => 'Categoria desativada com sucesso!',
            'data' => new CategoryResource($category),
        ], 200);
    }
}
