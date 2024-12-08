<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskToDo extends Model
{
    use HasFactory;
    protected $table = "tasktodos";
    protected $hidden = [
        "updated_at",
        "user_id"
    ];

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'task_to_do_ticket');
    }
}
