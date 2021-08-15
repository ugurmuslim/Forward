<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_types')->insert([
            'title' => "common_ops",
            'required_fields' => json_encode(array()),
        ]);

        DB::table('task_types')->insert([
            'title' => "invoice_ops",
            'required_fields' => json_encode([
                "quantity" => "string",
                "currency" => "string"
            ])
        ]);

        DB::table('task_types')->insert([
            'title' => "custom_ops",
            'required_fields' => json_encode([
                "country" => "string"
            ])
        ]);


    }
}
