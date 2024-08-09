<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BorrowRequest;


class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('owner')->except(['store', 'checkIfBorrowed']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrow::with(['book', 'user:id,username'])->get();
        return response()->json(['data' => $borrows], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    
    
    //  public function store(BorrowRequest $request)
    //  {
    //      $user = auth()->user();
    //      $response = null;
     
    //      try {
    //          DB::transaction(function () use ($request, $user, &$response) {
    //              $existingBorrow = Borrow::where('user_id', $user->id)
    //                                      ->where('book_id', $request->book_id)
    //                                      ->first();
     
    //              if ($existingBorrow) {
    //                  $response = response()->json(['message' => 'User cannot borrow the same book more than once || Pengguna tidak bisa meminjam buku yang sama lebih dari sekali'], 400);
    //              } else {
    //                  $book = Book::findOrFail($request->book_id);
    //                  $book->decrement('stok');
     
    //                  $tanggalPinjam = Carbon::now(); 
    //                  $tanggalKembali = $tanggalPinjam->copy()->addDays(7);
     
    //                  $borrow = Borrow::create([
    //                      'user_id' => $user->id,
    //                      'book_id' => $request->book_id,
    //                      'tanggal_pinjam' => $tanggalPinjam,
    //                      'tanggal_kembali' => $tanggalKembali,
    //                      'status' => 'P'
    //                  ]);
     
    //                  $response = response()->json([
    //                      'message' => 'Borrow book created successfully || Peminjaman buku berhasil',
    //                      'borrow' => $borrow
    //                  ], 201);
    //              }
    //          });
     
    //      } catch (\Exception $e) {
    //          $response = response()->json(['message' => $e->getMessage()], 400);
    //      }
     
    //      return $response;
    //  }

    public function store(BorrowRequest $request)
{
    $user = auth()->user();
    $response = null;

    try {
        DB::transaction(function () use ($request, $user, &$response) {
            $existingBorrow = Borrow::where('user_id', $user->id)
                                    ->where('book_id', $request->book_id)
                                    ->first();

            // Cek apakah pengguna sudah meminjam buku yang sama
            if ($existingBorrow) {
                $response = response()->json([
                    'message' => 'Pengguna tidak bisa meminjam buku yang sama lebih dari sekali'
                ], 400);
                return;
            }

            $book = Book::findOrFail($request->book_id);

            // Cek apakah stok buku habis
            if ($book->stok <= 0) {
                $response = response()->json([
                    'message' => 'Stock is empty || Stok habis'
                ], 400);
                return;
            }

            // Kurangi stok buku dan buat catatan peminjaman
            $book->decrement('stok');
            $tanggalPinjam = Carbon::now(); 
            $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

            $borrow = Borrow::create([
                'user_id' => $user->id,
                'book_id' => $request->book_id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali' => $tanggalKembali,
                'status' => 'P'
            ]);

            $response = response()->json([
                'message' => 'Peminjaman buku berhasil',
                'borrow' => $borrow
            ], 201);
        });

    } catch (\Exception $e) {
        $response = response()->json(['message' => $e->getMessage()], 400);
    }

    return $response;
}

     

    public function storeAdmin(BorrowRequest $request)
    {
        $response = null;
    
        try {
            DB::transaction(function () use ($request, &$response) {
                $existingBorrow = Borrow::where('user_id', $request->user_id)
                                        ->where('book_id', $request->book_id)
                                        ->first();
    
                // Cek apakah pengguna sudah meminjam buku yang sama
                if ($existingBorrow) {
                    $response = response()->json([
                        'message' => 'Pengguna tidak bisa meminjam buku yang sama lebih dari sekali'
                    ], 400);
                    return;
                }
    
                $book = Book::findOrFail($request->book_id);
    
                // Cek apakah stok buku habis
                if ($book->stok <= 0) {
                    $response = response()->json([
                        'message' => 'Stock is empty || Stok habis'
                    ], 400);
                    return;
                }
    
                // Kurangi stok buku dan buat catatan peminjaman
                $book->decrement('stok');
                $tanggalPinjam = Carbon::now(); 
                $tanggalKembali = $tanggalPinjam->copy()->addDays(7);
    
                $borrow = Borrow::create([
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'P'
                ]);
    
                $response = response()->json([
                    'message' => 'Peminjaman buku berhasil',
                    'borrow' => $borrow
                ], 201);
            });
    
        } catch (\Exception $e) {
            $response = response()->json(['message' => $e->getMessage()], 400);
        }
    
        return $response;
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        return response()->json(['data' => $borrow->load(['book.category', 'user'])], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrow $borrow)
    {
        try {
            DB::transaction(function () use ($request, $borrow) {
                $borrow->update($request->all());
            });
    
            return response()->json(['message' => 'Peminjaman diperbarui'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui peminjaman'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        try {
            DB::transaction(function () use ($borrow) {
                $book = $borrow->book;
                $book->increment('stok');
    
                $borrow->delete();
            });
    
            return response()->json(['message' => 'Peminjaman dihapus'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus peminjaman'], 400);
        }
    }

    public function checkIfBorrowed(Request $request)
    {
        $user = auth()->user();

        $isBorrowed = Borrow::where('user_id', $user->id)
                            ->where('book_id', $request->book_id)
                            ->exists();
        return response()->json(['isBorrowed' => $isBorrowed], 200);
    }
}
