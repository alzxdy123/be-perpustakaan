<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'owner@gmail.com');
        $aldy = User::where('email', 'aldy@gmail.com');
        $syarif = User::where('email', 'syarif@gmail.com');
        $azi = User::where('email', 'azi@gmail.com');

        $book1 = Book::where('title'. 'Book 1')->first();
        $book2 = Book::where('title'. 'Book 2')->first();
        $book3 = Book::where('title'. 'Book 3')->first();

        $now = Carbon::now();

        Borrow::create([
            'user_id' => $aldy->id,
            'book_id' => $book1->id,
            'status' => 'P'
        ]);
        Borrow::create([
            'user_id' => $syarif->id,
            'book_id' => $book1->id,
            'status' => 'P'
        ]);
        Borrow::create([
            'user_id' => $syarif->id,
            'book_id' => $book2->id,
            'status' => 'P'
        ]);
        Borrow::create([
            'user_id' => $azi->id,
            'book_id' => $book3->id,
            'status' => 'P'
        ]);
        Borrow::create([
            'user_id' => $azi->id,
            'book_id' => $book2->id,
            'status' => 'P'
        ]);
        Borrow::create([
            'user_id' => $aldy->id,
            'book_id' => $book3->id,
            'status' => 'P'
        ]);
    }
}
