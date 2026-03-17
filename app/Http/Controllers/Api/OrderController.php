<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Order",
 *     description="Order operations"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/orders",
     *     tags={"Order"},
     *     summary="Checkout (create order)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"store_id","order_type"},
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(property="order_type", type="string", example="pickup"),
     *             @OA\Property(property="buyer_lat", type="number", example=-6.2),
     *             @OA\Property(property="buyer_lng", type="number", example=106.9)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Order created"),
     *     @OA\Response(response=400, description="Cart empty / invalid"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Checkout failed")
     * )
     */
    // CHECKOUT (CREATE ORDER)
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'order_type' => 'required|in:pickup,delivery'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {

            $productTotal = 0;

            foreach ($cartItems as $item) {
                $productTotal += $item->product->price * $item->quantity;
            }

            $deliveryFee = 0;

            if ($request->order_type == 'delivery') {

                $store = Store::find($request->store_id);

                $distance = $this->distance(
                    $request->buyer_lat,
                    $request->buyer_lng,
                    $store->latitude,
                    $store->longitude
                );

                $deliveryFee = $distance * 2000;
            }

            $platformFee = 2000;

            $totalPrice = $productTotal + $deliveryFee + $platformFee;

            $order = Order::create([
                'buyer_id' => $user->id,
                'store_id' => $request->store_id,
                'product_total' => $productTotal,
                'delivery_fee' => $deliveryFee,
                'platform_fee' => $platformFee,
                'total_price' => $totalPrice,
                'order_type' => $request->order_type
            ]);

            foreach ($cartItems as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created',
                'data' => $order
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed'
            ], 500);
        }
    }

    // DISTANCE FUNCTION (ONGKIR)
    private function distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lng2 - $lng1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * @OA\Get(
     *     path="/orders",
     *     tags={"Order"},
     *     summary="Get user orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of orders")
     * )
     */
    // GET USER ORDERS
    public function index(Request $request)
    {
        $orders = Order::with('items')
            ->where('buyer_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     tags={"Order"},
     *     summary="Get order detail",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order detail"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    // ORDER DETAIL
    public function show($id)
    {
        $order = Order::with('items.product')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders/{id}/accept",
     *     tags={"Order"},
     *     summary="Seller accept order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order accepted"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    // SELLER ACCEPT ORDER
    public function accept($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->status = 'accepted';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order accepted'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders/{id}/complete",
     *     tags={"Order"},
     *     summary="Complete order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order completed"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    // COMPLETE ORDER
    public function complete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order completed'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders/{id}/cancel",
     *     tags={"Order"},
     *     summary="Cancel order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order cancelled"),
     *     @OA\Response(response=400, description="Cannot cancel order"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    // CANCEL ORDER
    public function cancel($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // hanya buyer atau seller terkait
        if (
            $order->buyer_id !== auth()->id() &&
            $order->store->user_id !== auth()->id()
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // hanya boleh cancel di status tertentu
        if (!in_array($order->status, ['pending', 'accepted'])) {
            return response()->json(['message' => 'Order tidak bisa dibatalkan'], 400);
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Order cancelled'
        ]);
    }
}

