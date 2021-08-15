<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class TaskDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_details')->insert([
            "title"             => "Driving To Petro Office",
            "task_id"           => 1,
            "task_type_id"      => 1,
            "additional_fields" => "[]",
            "prerequisites"     => "[]",
            "sequence_number"   => 1,
            "created_at"        => Date::now(),
        ]);

        DB::table('task_details')->insert([
            "title"             => "Getting the documents",
            "task_id"           => 1,
            "task_type_id"      => 1,
            "additional_fields" => "[]",
            "prerequisites"     => "[]",
            "sequence_number"   => 1,
            "created_at"        => Date::now(),
        ]);

        DB::table('task_details')->insert([
            "title"             => "Calculating the weight on departure",
            "task_id"           => 1,
            "task_type_id"      => 1,
            "additional_fields" => "[]",
            "prerequisites"     => "[]",
            "sequence_number"   => 1,
            "created_at"        => Date::now(),
        ]);

        DB::table('task_details')->insert([
            "title"                         => "Handing Over Invoice",
            "task_id"                       => 1,
            "task_type_id"                  => 2,
            "additional_fields"             => json_encode([
                "quantity" => 1200.23,
                "currency" => "TRY",
            ]),
            "prerequisites"                 => "[]",
            "sequence_number"               => 2,
            "created_at"                    => Date::now(),
        ]);

        DB::table('task_details')->insert([
            "title"                         => "Checking the weight after Transport",
            "task_id"                       => 1,
            "task_type_id"                  => 1,
            "additional_fields"             => "[]",
            "sequence_number"               => 2,
            "prerequisites"                 => "[]",
            "created_at"                    => Date::now(),
        ]);

        DB::table('task_details')->insert([
            "title"                         => "Checking the documents for the particular country",
            "task_id"                       => 1,
            "task_type_id"                  => 3,
            "additional_fields"             => json_encode([
                "country" => "TR",
            ]),
            "prerequisites"                 => "[]",
            "sequence_number"               => 3,
            "created_at"                    => Date::now(),
        ]);
    }
}
