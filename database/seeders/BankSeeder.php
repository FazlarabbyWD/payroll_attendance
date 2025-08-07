<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            // Government Banks
            ['name' => 'Sonali Bank Limited', 'type' => 'Government'],
            ['name' => 'Janata Bank Limited', 'type' => 'Government'],
            ['name' => 'Agrani Bank Limited', 'type' => 'Government'],
            ['name' => 'Rupali Bank Limited', 'type' => 'Government'],
            ['name' => 'Basic Bank Limited', 'type' => 'Government'],
            ['name' => 'Bangladesh Development Bank Limited (BDBL)', 'type' => 'Government'],
            ['name' => 'Bangladesh Krishi Bank', 'type' => 'Specialized'],
            ['name' => 'Rajshahi Krishi Unnayan Bank (RAKUB)', 'type' => 'Specialized'],

            // Private Commercial Banks (Conventional)
            ['name' => 'AB Bank Limited', 'type' => 'Private'],
            ['name' => 'Bank Asia Limited', 'type' => 'Private'],
            ['name' => 'BRAC Bank Limited', 'type' => 'Private'],
            ['name' => 'City Bank Limited', 'type' => 'Private'],
            ['name' => 'Dhaka Bank Limited', 'type' => 'Private'],
            ['name' => 'Dutch-Bangla Bank Limited', 'type' => 'Private'],
            ['name' => 'Eastern Bank Limited', 'type' => 'Private'],
            ['name' => 'IFIC Bank Limited', 'type' => 'Private'],
            ['name' => 'Jamuna Bank Limited', 'type' => 'Private'],
            ['name' => 'Mercantile Bank Limited', 'type' => 'Private'],
            ['name' => 'Midland Bank Limited', 'type' => 'Private'],
            ['name' => 'Modhumoti Bank Limited', 'type' => 'Private'],
            ['name' => 'Mutual Trust Bank Limited', 'type' => 'Private'],
            ['name' => 'National Bank Limited', 'type' => 'Private'],
            ['name' => 'National Credit and Commerce Bank Limited (NCC Bank)', 'type' => 'Private'],
            ['name' => 'NRB Commercial Bank Limited (NRBC)', 'type' => 'Private'],
            ['name' => 'NRB Bank Limited', 'type' => 'Private'],
            ['name' => 'One Bank Limited', 'type' => 'Private'],
            ['name' => 'Premier Bank Limited', 'type' => 'Private'],
            ['name' => 'Prime Bank Limited', 'type' => 'Private'],
            ['name' => 'Pubali Bank Limited', 'type' => 'Private'],
            ['name' => 'Shimanto Bank Limited', 'type' => 'Private'],
            ['name' => 'South Bangla Agriculture and Commerce Bank Limited (SBAC)', 'type' => 'Private'],
            ['name' => 'Southeast Bank Limited', 'type' => 'Private'],
            ['name' => 'Standard Bank Limited', 'type' => 'Private'],
            ['name' => 'Trust Bank Limited', 'type' => 'Private'],
            ['name' => 'United Commercial Bank Limited (UCB)', 'type' => 'Private'],
            ['name' => 'Uttara Bank Limited', 'type' => 'Private'],

            // Islamic Shariah-based Private Banks
            ['name' => 'Al-Arafah Islami Bank Limited', 'type' => 'Islamic'],
            ['name' => 'EXIM Bank Limited', 'type' => 'Islamic'],
            ['name' => 'First Security Islami Bank Limited', 'type' => 'Islamic'],
            ['name' => 'ICB Islamic Bank Limited', 'type' => 'Islamic'],
            ['name' => 'Islami Bank Bangladesh Limited', 'type' => 'Islamic'],
            ['name' => 'Shahjalal Islami Bank Limited', 'type' => 'Islamic'],
            ['name' => 'Social Islami Bank Limited', 'type' => 'Islamic'],
            ['name' => 'Union Bank Limited', 'type' => 'Islamic'],
        ];

        DB::table('banks')->insert($banks);
    }
}
