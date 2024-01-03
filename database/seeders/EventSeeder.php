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
            "description" =>"バイト先の親睦会",
            "created_at" =>new DateTime(),
            "updated_at" =>new DateTime(),
            "started_at"=>now()->toDateString(), 
            "ended_at"=>now()->addDays(2)->toDateString(),
            "deleted_at"=>null,
            "event_color"=>"白色",
            "event_border_color"=>"黒色",
            "user_id"=>1,
       ]);
    }
}
