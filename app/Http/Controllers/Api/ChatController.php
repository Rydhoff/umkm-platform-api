<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // create / get conversation
    public function createConversation(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id'
        ]);

        $user = $request->user();

        $store = \App\Models\Store::find($request->store_id);

        $conversation = \App\Models\Conversation::where('buyer_id', $user->id)
            ->where('seller_id', $store->owner_id)
            ->where('store_id', $store->id)
            ->first();

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'buyer_id' => $user->id,
                'seller_id' => $store->owner_id,
                'store_id' => $store->id
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }

    // send message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string'
        ]);

        $user = $request->user();

        $message = \App\Models\Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $user->id,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    // get messages
    public function getMessages($id, Request $request)
    {
        $user = $request->user();

        $conversation = \App\Models\Conversation::find($id);

        if (!$conversation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // keamanan
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = \App\Models\Message::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    // list chat / inbox
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = \App\Models\Conversation::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }
}
