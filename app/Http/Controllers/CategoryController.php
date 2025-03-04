<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('order')->get();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Generate slug from name if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = Category::create($data);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load(['parent', 'children']);

        return response()->json([
            'data' => $category
        ]);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->validated();

        // Update slug if name changed and slug wasn't explicitly provided
        if (isset($data['name']) && !isset($data['slug']) && $data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with associated products',
                'errors' => ['category' => 'This category has products associated with it']
            ], 422);
        }

        // Move child categories to parent category if it exists
        if ($category->children()->exists()) {
            $parentId = $category->parent_id;
            $category->children()->update(['parent_id' => $parentId]);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    public function tree(): JsonResponse
    {
        // Get only root categories with their descendants
        $categories = Category::whereNull('parent_id')
            ->with('children.children')
            ->orderBy('order')
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }
}
