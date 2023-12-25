<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class TaskSeeder extends Seeder
{
    public function run()
    {
        DB::table('tasks')->insert([
            'name' =>'プリント提出',
            'description' =>'アンケート調査用紙の提出',
            'deadline'=>new DateTime("2023-12-12 17:00:00"),
            'is_completed'=>0,
            'created_at' =>new DateTime(),
            'updated_at' =>new DateTime(),
            'deleted_at'=>null,
            "user_id"=>1
       ]);
    }
}