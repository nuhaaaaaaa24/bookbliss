<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_name', 'rating', 'review_text', 'review_image'];

    // Define the relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
