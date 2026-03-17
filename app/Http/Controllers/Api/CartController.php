<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cart",
     *     tags={"Cart"},
     *     summary="Get user cart",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of cart items"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cart
        ]);
    }

    /**
     * @OA\Post(
     *     path="/cart",
     *     tags={"Cart"},
     *     summary="Add product to cart",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Added to cart"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Added to cart',
            'data' => $cart
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/cart/{id}",
     *     tags={"Cart"},
     *     summary="Delete cart item",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Cart ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item removed"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed'
        ]);
    }

    /**
     * @OA\Put(
     *     path="/cart/{id}",
     *     tags={"Cart"},
     *     summary="Update cart quantity",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Cart ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cart updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        if ($request->quantity == 0) {
            $cart->delete();
            return response()->json(['message' => 'Item removed from cart']);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Cart updated',
            'data' => $cart
        ]);
    }
}