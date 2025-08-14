<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('bank_branches')->insert([
            ['bank_id' => 35,'branch_name'=>'Tejgaon','routing_no'=>245264485 ],
            ['bank_id' => 35,'branch_name'=>'Gulshan','routing_no'=>245261725 ],

        ]);
    }
}
