<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('religions')->insert([
            ['code' => 'islam', 'name' => 'Islam'],
            ['code' => 'christianity', 'name' => 'Christianity'],
            ['code' => 'hinduism', 'name' => 'Hinduism'],
            ['code' => 'buddhism', 'name' => 'Buddhism'],
        ]);

    }
}
