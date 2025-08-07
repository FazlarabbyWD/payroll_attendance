<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blood_groups')->insert([
            ['code' => 'a+', 'name' => 'A+'],
            ['code' => 'a-', 'name' => 'A-'],
            ['code' => 'b+', 'name' => 'B+'],
            ['code' => 'b-', 'name' => 'B-'],
            ['code' => 'ab+', 'name' => 'AB+'],
            ['code' => 'ab-', 'name' => 'AB-'],
            ['code' => 'o+', 'name' => 'O+'],
            ['code' => 'o-', 'name' => 'O-'],
        ]);

    }
}
