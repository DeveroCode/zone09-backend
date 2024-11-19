<?php

namespace Database\Seeders;

use App\Models\Computer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('computers')->insert([
            [
                'name' => 'PC-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-9',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}