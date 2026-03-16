<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    // STORES
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

    // STORE DETAIL
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

    // STORE NEARBY
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
