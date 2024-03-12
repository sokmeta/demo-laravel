<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Operations about categories",
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="List all categories",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="type", type="string", example="Gadgets"),
     *                 @OA\Property(property="description", type="string", example="A variety of electronic gadgets.", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *             @OA\Property(property="type", type="string", example="Gadgets"),
     *             @OA\Property(property="description", type="string", example="A variety of electronic gadgets.", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->input('description')
        ]);
    

        return response(['category'=> $category], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function show(string $id)
    {
        return Category::find($id);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *             @OA\Property(property="type", type="string", example="Gadgets"),
     *             @OA\Property(property="description", type="string", example="A variety of electronic gadgets.", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated",
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $category->update($request->all());
        return $category;
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        return Category::destroy($id);
    }
}
