<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VirtualBookshelf extends Controller
{
    public function store(Request $request)
    {
        // Debugging: Log the request data
        \Log::info('Store method called with data:', $request->all());

        // Check if the book already exists for the user in the same status category
        $existingBook = Books::where('user_id', Auth::id())
            ->where('title', $request->input('title'))
            ->where('status', $request->input('status'))
            ->first();

        if ($existingBook) {
            return response()->json(['error' => 'You already have this book in the ' . $request->input('status') . ' category.'], 409);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:want_to_buy,want_to_read,favorites',
        ]);

        // Debugging: Log the validated data
        \Log::info('Validated data:', $validated);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Books::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'author' => $validated['author'],
            'cover_image' => $coverImagePath,
            'status' => $validated['status'],
        ]);

        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Books::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($book);
    }

    public function getAllBooks()
    {
        try {
            // Ensure the user is authenticated
            $user = Auth::user();

            // Fetch books related to the authenticated user
            if ($user) {
                $books = $user->books; // Ensure the relationship is set correctly
                return response()->json($books, 200);
            }

            return response()->json(['error' => 'No authenticated user found'], 401);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error fetching books: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while fetching books.'], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $book = Books::where('user_id', Auth::id())->findOrFail($id);

        // Validate the incoming request data to only include the status
        $validated = $request->validate([
            'status' => 'required|in:want_to_buy,want_to_read,favorites',
        ]);

        // Check if the user already has this book in the new status category
        $existingBook = Books::where('user_id', Auth::id())
            ->where('title', $book->title) // Use the current book's title
            ->where('status', $validated['status'])
            ->where('id', '!=', $id) // Exclude the current book
            ->first();

        if ($existingBook) {
            return response()->json(['error' => 'You already have this book in the ' . $validated['status'] . ' category.'], 409);
        }

        // Update only the status of the book
        $book->status = $validated['status'];
        $book->save(); // Save the updated book model

        return response()->json($book);
    }



    public function destroy($id)
    {
        $book = Books::where('user_id', Auth::id())->findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
