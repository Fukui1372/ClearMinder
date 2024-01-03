<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
       return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}

