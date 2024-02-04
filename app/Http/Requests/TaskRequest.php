<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'task.name' => 'required|string|max:20',
            'task.deadline' => 'required|string|max:20',
        ];
    }
}
