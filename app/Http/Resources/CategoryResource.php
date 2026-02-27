<?php

namespace App\Http\Resources;

use App\Domains\Core\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Category $category */
        $category = $this->resource;

        $bu = new BusinessUnitResource($category->businessUnit);

        return [
            'id' => $category->uuid,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->is_active,
            'business_unit' => [
                'business_unit_name' => $bu->name,
                'business_unit_slug' => $bu->slug,
            ],

        ];
    }
}
