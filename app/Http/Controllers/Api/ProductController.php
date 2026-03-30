<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="Product operations"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test-swagger",
     *     summary="Test Swagger",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="ok")
     *         )
     *     )
     * )
     */
    public function testSwagger()
    {
        return response()->json(['message' => 'ok']);
    }

    /**
     * @OA\Get(
     *     path="/stores/{id}/products",
     *     tags={"Product"},
     *     summary="Get products by store",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Store ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Nasi Goreng"),
     *                     @OA\Property(property="price", type="integer", example=15000),
     *                     @OA\Property(property="stock", type="integer", example=100),
     *                     @OA\Property(property="description", type="string", example="Nasi goreng spesial"),
     *                     @OA\Property(property="image", type="string", example="image.jpg"),
     *                     @OA\Property(property="is_available", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index($id)
    {
        $products = Product::where('store_id', $id)
            ->where('is_available', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     tags={"Product"},
     *     summary="Create product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"store_id","name","price","stock"},
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nasi Goreng"),
     *             @OA\Property(property="price", type="integer", example=15000),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="description", type="string", example="Nasi goreng spesial"),
     *             @OA\Property(property="image", type="string", example="image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product created"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nasi Goreng"),
     *                 @OA\Property(property="price", type="integer", example=15000),
     *                 @OA\Property(property="stock", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created',
            'data' => $product
        ]);
    }

    // search
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $products = Product::with('store')
            ->where('is_available', true)
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     summary="Update product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nasi Goreng Spesial"),
     *             @OA\Property(property="price", type="integer", example=18000),
     *             @OA\Property(property="stock", type="integer", example=80),
     *             @OA\Property(property="description", type="string", example="Update deskripsi"),
     *             @OA\Property(property="image", type="string", example="image.jpg"),
     *             @OA\Property(property="is_available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product updated"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nasi Goreng Spesial")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if ($request->hasFile('image')) {

            // hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'name' => $request->name ?? $product->name,
            'price' => $request->price ?? $product->price,
            'stock' => $request->stock ?? $product->stock,
            'description' => $request->description ?? $product->description,
            'is_available' => $request->is_available ?? $product->is_available
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated',
            'data' => $product
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     summary="Delete product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product deleted")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted'
        ]);
    }
}