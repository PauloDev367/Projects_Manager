<?php

namespace App\Repositories;

use App\Models\TaskToDo;
use App\Models\User;
use App\Repositories\Ports\ITaskToDoRepository;

class TaskToDoRepository implements ITaskToDoRepository
{
    public function create(TaskToDo $taskToDo)
    {
        $taskToDo->save();
        return $taskToDo;
    }
    public function getOne(User $user, int $id)
    {
        return TaskToDo::where("user_id", $user->id)
            ->where("id", $id)
            ->with("tickets")
            ->first();
    }
    public function getOneFromColumn(User $user, int $id, int $column_id)
    {
        return TaskToDo::where("user_id", $user->id)
            ->where("id", $id)
            ->where("column_id", $column_id)
            ->with("tickets")
            ->first();
    }
    public function countTotalFromColumns(User $user, int $column_id)
    {
        return TaskToDo::where("user_id", $user->id)
            ->where("column_id", $column_id)
            ->with("tickets")
            ->count();
    }
    public function getAll(User $user, int $column_id)
    {
        return TaskToDo::where("user_id", $user->id)
            ->where("column_id", $column_id)
            ->orderBy("position")
            ->with("tickets")
            ->get();
    }
    public function delete(TaskToDo $taskToDo)
    {
        $taskToDo->delete();
    }
    public function update(TaskToDo $taskToDo)
    {
        $taskToDo->save();
        return $taskToDo;
    }
}
