<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Core\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

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
}
