<?php

namespace App\Http\Controllers;

use App\Book;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        $book = Book::query();
        $sortDirection = 'ASC';
        if ($request->has('sortDirection')) {
            if (in_array($request->sortDirection, ['ASC', 'DESC'])) {
                $sortDirection = $request->sortDirection;
            }
        }
        if ($request->has('sortColumn')) {
            if ($request->sortColumn == 'avg_review') {
                $book->withCount(['reviews as avg_review' => function($qry) {
                    $qry->select(DB::raw('coalesce(avg(review),0)'));
                }])->orderBy($request->sortColumn, $sortDirection);
            } else {
                $book->orderBy($request->sortColumn, $sortDirection);
            }
        }
        if ($request->has('title')) {
            $book->where('title', 'like', '%'.$request->title.'%');
        }
        if ($request->has('authors')) {
            $authors = explode(',', $request->authors);
            $book->whereHas('authors', function ($qry) use ($authors) {
                $qry->whereIn('id', $authors);
            });
        }
        $book = $book->paginate()->withQueryString();
        return BookResource::collection($book);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $book = new Book();

        $data = $request->validated();
        
        $book->isbn = $data['isbn'];
        $book->title = $data['title'];
        $book->description = $data['description'];
        $book->published_year = $data['published_year'];
        $book->save();

        $authors = Author::whereIn('id', $data['authors'])->get();
        $book->authors()->saveMany($authors);
        
        return new BookResource($book);
    }
}
