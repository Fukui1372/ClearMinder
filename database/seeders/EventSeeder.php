<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class EventSeeder extends Seeder
{
    public function run()
    {
        DB::table("events")->insert([
            "name" =>"飲み会",
            "discription" =>"バイト先の親睦会",
            "created_at" =>new DateTime(),
            "updated_at" =>new DateTime(),
            "started_at"=>new DateTime(),
            "ended_at"=>new DateTime(),
            "deleted_at"=>null,
            "user_id"=>1
       ]);
    }
}
