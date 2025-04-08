<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = [
            [
                'user_id' => 1,
                'income'=> 100000,
                'outcome'=> 0,
                'description' => 'Gaji Bulanan',
                'status' => 'success',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at'=> now(),
            ],
            [
                'user_id' => 1,
                'income'=> 0,
                'outcome'=> 20000,
                'description' => 'Jajan',
                'status' => 'success',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at'=> now(),
            ],
        ];

        DB::table('wallets')->insert($wallets);
    }
}
