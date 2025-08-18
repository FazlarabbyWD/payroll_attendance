<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = 2025;
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        $fridays = [];

        // Loop through all days of the year
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isFriday()) {
                $fridays[] = [
                    'title' => 'Weekly Holiday (Friday)',
                    'start_date' => $date->format('Y-m-d'),
                    'end_date' => $date->format('Y-m-d'),
                    'is_recurring' => true,
                    'description' => 'Weekly holiday',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('holidays')->insert($fridays);

        $this->command->info(count($fridays) . " Fridays of $year inserted successfully!");
    }
}
