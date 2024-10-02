<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Challenge;
use App\Models\Group;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Show the admin login form
    public function showLoginForm()
    {
        return view('admin/adminLogin');
    }

    // Handle the admin login
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the admin exists and the password matches
        if (Auth::guard('admins')->attempt($credentials)) {
            // Authentication passed
            return redirect()->intended(route('adminDashboard'));
        }

        return redirect()->route('adminLogin')->with('error', 'Invalid credentials');
    }

    // Show the admin dashboard
    public function dashboard()
    {
        // Fetch all users with their groups
        $users = User::with('groups')->get(); // Ensure to eager load groups

        // Analytics data
        $totalUsers = $users->count();
        $recentUsers = $users->where('created_at', '>=', now()->subDays(7))->count(); // Users registered in the last 7 days

        // Aggregate genre counts
        $genreCounts = [];
        foreach ($users as $user) {
            $genres = $user->genre; // Directly access the genre array
            if ($genres) {
                foreach ($genres as $genre) {
                    if (isset($genreCounts[$genre])) {
                        $genreCounts[$genre]++;
                    } else {
                        $genreCounts[$genre] = 1;
                    }
                }
            }
        }

        return view('admin.adminDashboard', compact('users', 'totalUsers', 'recentUsers', 'genreCounts'));
    }

    public function groups()
    {
        // Fetch groups with user count
        $groups = Group::withCount('users')->get();

        return view('admin.groups', compact('groups'));
    }

    public function showReviews()
    {
        $reviews = Review::with('user')->get();

        // Analytics
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');

        return view('admin.reviews', compact('reviews', 'totalReviews', 'averageRating'));
    }

    public function challengesOverview()
    {
        // Retrieve challenges with the count of participants
        $challenges = Challenge::withCount('participants')->get();

        return view('admin.challenges', compact('challenges'));
    }

    public function challengesParticipants()
    {
        // Retrieve participants with their respective challenges using query builder
        $participants = DB::table('challenge_user')
            ->join('users', 'challenge_user.user_id', '=', 'users.id')
            ->join('challenges', 'challenge_user.challenge_id', '=', 'challenges.id')
            ->select('users.name as user_name', 'challenges.title as challenge_title', 'challenge_user.status', 'challenge_user.progress')
            ->get();

        return view('admin.challengesParticipants', compact('participants'));
    }

    // Handle the admin logout
    public function logout()
    {
        Auth::guard('admins')->logout();
        return redirect()->route('adminLogin')->with('success', 'Logged out successfully');
    }
}
