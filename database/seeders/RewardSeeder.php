<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class RewardSeeder extends Seeder
{
    public function run()
    {
        DB::table('tasks')->insert([
            'name' =>'焼肉',
            'required_points'=>20,
            'created_at' =>new DateTime(),
            'updated_at' =>new DateTime(),
            'deleted_at'=>null,
       ]);
    }
}
