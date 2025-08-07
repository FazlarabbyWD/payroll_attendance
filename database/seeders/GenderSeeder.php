<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genders')->insert([
            ['code' => 'male', 'name' => 'Male'],
            ['code' => 'female', 'name' => 'Female'],
        ]);

    }
}
