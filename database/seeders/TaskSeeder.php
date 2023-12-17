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
            'discription' =>'アンケート調査用紙の提出',
            'deadline'=>2023_12_12_170000
            'is_completed'=>0
            'created_at' =>new DateTime(),
            'updated_at' =>new DateTime(),
            'deleted_at'=>null,
       ]);
    }
}