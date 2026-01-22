<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Quote::insert([
            ['content' => 'Life is either a daring adventure or nothing at all.', 'author' => 'Helen Keller', 'created_at' => now(), 'updated_at' => now()],
            ['content' => 'The biggest adventure you can take is to live the life of your dreams.', 'author' => 'Oprah Winfrey', 'created_at' => now(), 'updated_at' => now()],
            ['content' => 'To live is the rarest thing in the world. Most people exist, that is all.', 'author' => 'Oscar Wilde', 'created_at' => now(), 'updated_at' => now()],
            ['content' => 'Happiness is not something ready-made. It comes from your own actions.', 'author' => 'Dalai Lama', 'created_at' => now(), 'updated_at' => now()],
            ['content' => 'Adventure is worthwhile in itself.', 'author' => 'Amelia Earhart', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
