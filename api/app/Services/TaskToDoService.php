<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\V1\CreateTaskToDoRequest;
use App\Http\Requests\V1\UpdateTaskToDoRequest;
use App\Models\TaskToDo;
use App\Repositories\Ports\ITaskToDoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskToDoService
{

    public function __construct(
        private readonly ITaskToDoRepository $repository
    ) {}

    public function create(User $user, CreateTaskToDoRequest $request)
    {
        $task = new TaskToDo();
        $task->title = $request->title;
        $task->position = $request->position;
        $task->column_id = $request->column_id;
        $task->user_id = $user->id;

        $created = $this->repository->create($task);
        return $created;
    }
    public function getOne(User $user, int $id)
    {
        return $this->repository->getOne($user, $id);
    }
    public function getAll(User $user, int $column_id)
    {
        return $this->repository->getAll($user, $column_id);
    }
    public function delete(User $user, int $id)
    {
        $task = $this->repository->getOne($user, $id);
        if ($task == null) {
            throw new ModelNotFoundException("Task not founded");
        }

        $this->repository->delete($task);
    }
    public function update(User $user, UpdateTaskToDoRequest $request, int $id)
    {
        $task = $this->repository->getOne($user, $id);
        if ($task == null) {
            throw new ModelNotFoundException("Task not founded");
        }
        $task->title = $request->title != null ? $request->title : $task->title;
        $task->position = $request->position != null ? $request->position : $task->position;
        $task->end_data = $request->end_data != null ? $request->end_data : $task->end_data;
        $task->description = $request->description != null ? $request->description : $task->description;
        $task->column_id = $request->column_id != null ? $request->column_id : $task->column_id;

        $updated = $this->repository->update($task);
        return $updated;
    }
}
