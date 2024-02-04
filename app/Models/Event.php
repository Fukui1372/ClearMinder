<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    //Controllerのfill用
    protected $fillable = [
        'name',
        'description',
        'started_at',
        'ended_at',
        'event_color',
        'event_border_color',
    ];
}
