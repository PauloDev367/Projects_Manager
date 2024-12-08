<?php

namespace App\Repositories\Ports;

use App\Models\TaskToDo;
use App\Models\User;

interface ITaskToDoRepository
{
    public function create(TaskToDo $taskToDo);
    public function getOne(User $user,int $id);
    public function getAll(User $user,int $column_id);
    public function delete(TaskToDo $taskToDo);
    public function update(TaskToDo $taskToDo);
}
