<?php

namespace App\Http\Controllers;

use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;

class BooksReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $bookReview = new BookReview();
        $bookReview->book_id = $bookId;
        $bookReview->user_id = $user->id;
        $bookReview->review = $request->review;
        $bookReview->comment = $request->comment;
        $bookReview->save();
        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
    }
}
