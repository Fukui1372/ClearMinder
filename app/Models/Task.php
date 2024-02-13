<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'deadline',
        'user_id',
        ];
    
    public function getPaginateByLimit(int $limit_count = 5)
    {
       return $this->orderBy('updated_at', 'DESC')->where('user_id', Auth::id())->paginate($limit_count);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}

