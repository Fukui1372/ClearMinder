<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'deadline',
        'user_id',
        ];
    
    public function getPaginateByLimit(int $limit_count = 5)
    {
       return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}

