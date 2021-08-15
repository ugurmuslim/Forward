<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            "id"      => "TRY",
            "symbol"     => "₺",
            "created_at" => Date::now(),
        ]);

        DB::table('currencies')->insert([
            "id"      => "Euro",
            "symbol"     => "€",
            "created_at" => Date::now(),
        ]);

        DB::table('currencies')->insert([
            "id"      => "USD",
            "symbol"     => "$",
            "created_at" => Date::now(),
        ]);

        DB::table('currencies')->insert([
            "id"      => "GBP",
            "symbol"     => "£",
            "created_at" => Date::now(),
        ]);
    }
}
