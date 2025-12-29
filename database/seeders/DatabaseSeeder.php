<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Reader;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $authors = Author::factory()->count(20)->create();

        $books = Book::factory()->count(50)->create();

        foreach ($books as $book) {
            $book->authors()->attach(
                $authors->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        Reader::factory()->count(30)->create();
    }
}
