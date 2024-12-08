<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = "tickets";
    protected $hidden = [
        "created_at",
        "updated_at",
        "user_id",
        "pivot",
    ];

    public function tasks()
    {
        return $this->belongsToMany(TaskToDo::class, 'task_to_do_ticket');
    }
}
