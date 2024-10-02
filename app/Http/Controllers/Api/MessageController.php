<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Group;

class MessageController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(), // Get the ID of the authenticated user
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }

    public function index(Group $group)
    {
        // Retrieve messages along with the user who sent each message
        $messages = $group->messages()->with('user:id,name')->get(); // Adjust 'id' and 'name' as per your User model

        return response()->json($messages);
    }
}
