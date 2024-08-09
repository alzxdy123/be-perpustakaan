<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    
    public function __construct() {
        $this->middleware(['auth:api', 'owner'])->only('update', 'destroy', 'store');
    }

    public function index(Request $request)
    {
        $books = Book::with('category');
    
        if($request->input('title')) {
            $books->where('title', 'like', '%'.$request->input('title').'%');
        }
    
        if($request->input('status')) {
            $books->where('status', $request->input('status'));
        }
    
        if($request->input('category_id')) {
            $books->where('category_id', $request->input('category_id'));
        }
    
        $books->orderBy('created_at', 'desc');
    
        $books = $books->get();
    
        if($books->isEmpty()) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
    
        return response()->json(['data' => $books], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(BookRequest $request)
    {
        $coverData = null;

        if($request->hasFile('cover')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('cover')->getRealPath())->getSecurePath();
            $coverData = $uploadedFileUrl;
        }

        $request['status'] = $request->stok > 0 ? "A" : "N";

        $data = $request->all();
        $data['cover'] = $coverData;

        $book = Book::create($data);

        return response()->json(['message' => 'Buku berhasil ditambahkan', 'data' => $book], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = Book::with('category')->find($book->id);

        return response()->json(['data' => $book], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $coverData = $book->cover;

        if($request->hasFile('cover')) {
            if ($coverData) {
                $publicId = pathinfo($coverData, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            $uploadedFileUrl = Cloudinary::upload($request->file('cover')->getRealPath())->getSecurePath();
            $coverData = $uploadedFileUrl;
        }

        $request['status'] = $request->stok > 0 ? "A" : "N";

        $data = $request->all();
        $data['cover'] = $coverData;

        $book->update($data);

        return response()->json(['message' => 'Buku berhasil diperbarui', 'data' => $book], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover) {
            $publicId = pathinfo($book->cover, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }
    
        $book->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }



}
