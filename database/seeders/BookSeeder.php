<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikan  = Category::where('name', 'Pendidikan')->first();
        $novel  = Category::where('name', 'Novel')->first();
        $sejarah  = Category::where('name', 'Sejarah')->first();
        $olahraga  = Category::where('name', 'Olahraga')->first();
        $biograpi  = Category::where('name', 'Biograpi')->first();

        Book::create([
            'title' => 'book 1',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $pendidikan->id
        ]);
        Book::create([
            'title' => 'book 2',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $novel->id
        ]);
        Book::create([
            'title' => 'book 3',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $sejarah->id
        ]);
        Book::create([
            'title' => 'book 4',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $olahraga->id
        ]);
        Book::create([
            'title' => 'book 5',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '0',
            'status' => 'D',
            'category_id' => $biograpi->id
        ]);
        Book::create([
            'title' => 'book 6',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $pendidikan->id
        ]);
        Book::create([
            'title' => 'book 7',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $novel->id
        ]);
        Book::create([
            'title' => 'book 8',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $sejarah->id
        ]);
        Book::create([
            'title' => 'book 9',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $olahraga->id
        ]);
        Book::create([
            'title' => 'book 10',
            'summary' => 'summary',
            'author' => 'author',
            'stok' => '10',
            'status' => 'A',
            'category_id' => $biograpi->id
        ]);
    }
}
