<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;


class ChallengeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Check if the challenge with the same title exists for this user
        $existingChallenge = Challenge::where('user_id', $validated['user_id'])
            ->where('title', $validated['title'])
            ->first();

        if ($existingChallenge) {
            return response()->json([
                'message' => 'Challenge with this title already exists for the user. Please use a different title.'
            ], 400);
        }

        $challenge = Challenge::create($validated);

        return response()->json([
            'message' => 'Challenge created successfully',
            'challenge' => $challenge
        ], 201);
    }


    // Join a challenge
    public function joinChallenge(Request $request, $challengeId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $challenge = Challenge::findOrFail($challengeId);

        // Check if the user is already part of the challenge
        $existingParticipant = $challenge->participants()->where('user_id', $validated['user_id'])->first();

        if ($existingParticipant) {
            return response()->json([
                'message' => 'User has already joined this challenge.'
            ], 400); // 400 Bad Request
        }

        // Attach the user to the challenge if not already joined
        $challenge->participants()->attach($validated['user_id'], ['status' => 'ongoing', 'progress' => 0]);

        return response()->json([
            'message' => 'You have successfully joined the challenge',
            'challenge' => $challenge
        ], 200);
    }


    // Get user's progress in a challenge
    public function userProgress($challengeId, $userId)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $participant = $challenge->participants()->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['message' => 'User is not part of this challenge'], 404);
        }

        return response()->json([
            'status' => $participant->pivot->status,
            'progress' => $participant->pivot->progress,
        ]);
    }

    // Mark challenge as completed for a user
    public function completeChallenge(Request $request, $challengeId, $userId)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $participant = $challenge->participants()->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['message' => 'User is not part of this challenge'], 404);
        }

        // Mark as completed and set progress to 100%
        $challenge->participants()->updateExistingPivot($userId, [
            'status' => 'completed',
            'progress' => 100 // Set progress to 100% upon completion
        ]);

        return response()->json(['message' => 'Challenge marked as completed']);
    }
    // Update user progress in a challenge
    public function updateProgress(Request $request, $challengeId, $userId)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100', // Validate progress input
        ]);

        $challenge = Challenge::findOrFail($challengeId);
        $participant = $challenge->participants()->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['message' => 'User is not part of this challenge'], 404);
        }

        // Update the progress in the pivot table
        $challenge->participants()->updateExistingPivot($userId, ['progress' => $validated['progress']]);

        return response()->json(['message' => 'Progress updated successfully']);
    }

}
