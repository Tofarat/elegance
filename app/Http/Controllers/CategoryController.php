<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     * GET /categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all categories from the database
        $categories = Category::all();

        // Return categories in JSON format for the API response
        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     * POST /categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request to ensure required fields are provided
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id' // Allow null or valid existing category ID
        ]);

        // Generate a slug from the name
        $validatedData['slug'] = Str::slug($validatedData['name']);

        // Create a new category with the validated data
        $category = Category::create($validatedData);

        // Return the created category with a success response
        return response()->json(['message' => 'User created successfully', 'user' =>$category], 201);

    }

    /**
     * Display the specified category.
     * GET /categories/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the category by ID; if not found, return a 404 error
        $category = Category::findOrFail($id);

        // Return the found category in JSON format
        return response()->json($category);
    }

    /**
     * Update the specified category in storage.
     * PUT /categories/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request for updating the category
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id, // Unique validation, excluding the current category
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id' // Parent ID must be a valid category or null
        ]);
    
        // Find the category by ID; if not found, return a 404 error
        $category = Category::findOrFail($id);
    
        // Update the category with validated data
        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['name']); // Update the slug based on the new name
        $category->description = $validatedData['description'];
        $category->parent_id = $validatedData['parent_id'];
    
        // Save the changes
        $category->save();
    
        // Return the updated category in JSON format
        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }
    

    /**
     * Remove the specified category from storage.
     * DELETE /categories/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the category by ID; if not found, return a 404 error
        $category = Category::findOrFail($id);

        // Delete the category from the database
        $category->delete();

        // Return a success message with a 204 No Content response
        return response()->json(['message' => 'Category deleted successfully'], 204);
    }
}
