<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_types')->insert([
            ['code' => 'permanent', 'name' => 'Permanent'],
            ['code' => 'contract', 'name' => 'Contract'],
            ['code' => 'part_time', 'name' => 'Part Time'],
            ['code' => 'intern', 'name' => 'Intern'],
        ]);

    }
}
