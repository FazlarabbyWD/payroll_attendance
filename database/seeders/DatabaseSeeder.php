<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            MaritalStatusSeeder::class,
            GenderSeeder::class,
            EmploymentTypeSeeder::class,
            EmploymentStatusSeeder::class,
            BloodGroupSeeder::class,
            ReligionSeeder::class,
            BankSeeder::class,
        ]);
    }
}
