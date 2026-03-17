<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Store",
 *     description="Store operations"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class StoreController extends Controller
{
    /**
     * @OA\Post(
     *     path="/stores",
     *     tags={"Store"},
     *     summary="Create store",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"store_name","address","latitude","longitude"},
     *             @OA\Property(property="store_name", type="string", example="Toko Maju Jaya"),
     *             @OA\Property(property="description", type="string", example="Toko sembako lengkap"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No. 10"),
     *             @OA\Property(property="latitude", type="number", format="float", example=-6.200000),
     *             @OA\Property(property="longitude", type="number", format="float", example=106.816666)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Store created"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="store_name", type="string", example="Toko Maju Jaya"),
     *                 @OA\Property(property="description", type="string", example="Toko sembako lengkap"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 10"),
     *                 @OA\Property(property="latitude", type="number", format="float", example=-6.2),
     *                 @OA\Property(property="longitude", type="number", format="float", example=106.816666)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="Only seller can create store"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'seller') {
            return response()->json([
                'success' => false,
                'message' => 'Only seller can create store'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'store_name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $store = Store::create([
            'owner_id' => $user->id,
            'store_name' => $request->store_name,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Store created',
            'data' => $store
        ]);
    }

    /**
     * @OA\Get(
     *     path="/stores/{id}",
     *     tags={"Store"},
     *     summary="Get store detail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Store ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store detail",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="store_name", type="string", example="Toko Maju Jaya"),
     *                 @OA\Property(property="description", type="string", example="Toko sembako lengkap"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 10"),
     *                 @OA\Property(property="latitude", type="number", format="float", example=-6.2),
     *                 @OA\Property(property="longitude", type="number", format="float", example=106.816666),
     *                 @OA\Property(property="products", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Store not found")
     * )
     */
    public function show($id)
    {
        $store = Store::with('products')->find($id);

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $store
        ]);
    }

    /**
     * @OA\Get(
     *     path="/stores/nearby",
     *     tags={"Store"},
     *     summary="Get nearby stores",
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         required=true,
     *         description="Latitude",
     *         @OA\Schema(type="number", format="float", example=-6.200000)
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         required=true,
     *         description="Longitude",
     *         @OA\Schema(type="number", format="float", example=106.816666)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nearby stores list",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="store_name", type="string", example="Toko Maju Jaya"),
     *                     @OA\Property(property="latitude", type="number", format="float", example=-6.2),
     *                     @OA\Property(property="longitude", type="number", format="float", example=106.816666),
     *                     @OA\Property(property="distance", type="number", example=1.23)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function nearby(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;

        $radius = 5; // km

        $stores = DB::table('stores')
            ->selectRaw("
                *,
                (6371 *
                    acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance
            ", [$lat, $lng, $lat])
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }
}