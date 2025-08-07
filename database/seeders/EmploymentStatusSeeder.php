<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_statuses')->insert([
            ['code' => 'active', 'name' => 'Active'],
            ['code' => 'inactive', 'name' => 'Inactive'],
            ['code' => 'terminated', 'name' => 'Terminated'],
            ['code' => 'resigned', 'name' => 'Resigned'],
        ]);

    }
}
