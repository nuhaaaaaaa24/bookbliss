<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Retrieve all groups with creator information
        $groups = Group::with(['creator'])->get(); // assuming 'creator' is defined in the Group model

        return response()->json($groups);
    }

    public function store(Request $request)
    {
        // Validate and create a new group with custom error messages
        $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:1000', // description is optional
        ], [
            'name.unique' => 'Group name already exists. Choose another one.', // Custom error message
        ]);

        // Create the group
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description ?? 'No description provided', // Default value
            'creator_id' => auth()->id(),
        ]);

        return response()->json($group->load('creator'), 201); // Load creator details
    }




    public function joinGroup(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Find the group by ID
        $group = Group::findOrFail($id);

        // Check if the user is already a member
        if ($group->users()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'message' => 'You are already a member of this group',
            ], 400);
        }

        // Attach the user to the group
        $group->users()->attach($request->user_id);

        return response()->json([
            'message' => 'Successfully joined the group',
            'group' => $group,
        ], 200);
    }

    public function show($id)
    {
        // Retrieve a specific group by ID with creator information
        $group = Group::with('creator')->findOrFail($id);
        return response()->json($group);
    }

    public function update(Request $request, $id)
    {
        // Validate and update a specific group
        $group = Group::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:groups,name,' . $group->id,
            ],
            'description' => 'nullable|string|max:1000',
        ]);

        $group->update($request->all());
        return response()->json($group);
    }

    public function destroy($id)
    {
        // Delete a specific group by ID
        $group = Group::findOrFail($id);
        $group->delete();
        return response()->json(null, 204);
    }
}
