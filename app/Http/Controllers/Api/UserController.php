<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'genre' => 'required|array',
                'genre.*' => 'string|distinct',
                'bio' => 'required|string',
                'password' => 'required|min:6|confirmed'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bio' => $request->bio,  // Add bio
                'genre' => $request->genre,  // Add genre as an array
            ]);

            // Ensure the createToken method is called correctly.
            $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            // Log error for debugging purposes
            \Log::error('User registration failed: ' . $th->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error occurred while creating user',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Throttle login attempts
        if (RateLimiter::tooManyAttempts($request->ip(), 'login')) {
            return response()->json(['error' => 'Too many login attempts. Please try again later.'], 429);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($request->ip()); // Clear attempts on successful login
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        RateLimiter::hit($request->ip()); // Increment attempts on failure
        return response()->json(['error' => 'Unauthorized'], 401);
    }



    public function userProfile(Request $request)
    {
        // Get the authenticated user
        $user = $request->user(); // Make sure you have authentication set up correctly

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving profile information',
                'errors' => 'User not found'
            ], 404);
        }

        // Return user data
        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }



    // Update user profile
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user(); // Get the currently authenticated user

            // Validate the incoming request, including bio and optional profile photo
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id, // Ignore the user's own email
                'bio' => 'nullable|string', // Make bio optional
                'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate profile photo
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            // Handle profile photo upload if provided
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $user->profile_photo_path = $path; // Store the path in the database
            }

            // Update the user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'bio' => $request->bio, // Update bio if provided
                'profile_photo_path' => $user->profile_photo_path, // Update only if photo is uploaded
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $user->only(['id', 'name', 'email', 'bio', 'profile_photo_path', 'created_at', 'updated_at']),
            ], 200);
        } catch (\Throwable $th) {
            // Log error for debugging purposes
            \Log::error('Profile update failed: ' . $th->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error updating profile information',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent.'])
            : response()->json(['message' => 'User not found.'], 404);
    }


    public function resetPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ]);

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __('passwords.reset')])
            : response()->json(['message' => __('passwords.token')], 400);
    }
}
