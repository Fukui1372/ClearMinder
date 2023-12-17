<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' =>'Aさん',
            'email' =>'student@ac.jp',
            'password' =>'1010',
            'collected_points' =>10,
       ]);
    }
}
