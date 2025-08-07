<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('marital_statuses')->insert([
            ['code' => 'single', 'name' => 'Single'],
            ['code' => 'married', 'name' => 'Married'],
            ['code' => 'divorced', 'name' => 'Divorced'],
            ['code' => 'widowed', 'name' => 'Widowed'],
        ]);

    }
}
