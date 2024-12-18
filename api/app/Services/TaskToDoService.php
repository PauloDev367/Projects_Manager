<?php

namespace App\Services;

use App\Models\User;
use App\Models\TaskToDo;
use App\Http\Requests\V1\CreateTaskToDoRequest;
use App\Http\Requests\V1\UpdateTaskToDoRequest;
use App\Repositories\Ports\ITaskToDoRepository;
use App\Http\Requests\V1\AddTicketsToTaskToDoRequest;
use App\Http\Requests\V1\UpdateTaskToDoPositionRequest;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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
        $task->end_data = $request->end_data != null ? $request->end_data : $task->end_data;
        $task->description = $request->description != null ? $request->description : $task->description;
        $task->column_id = $request->column_id != null ? $request->column_id : $task->column_id;

        $updated = $this->repository->update($task);
        return $updated;
    }
    public function setTickets(User $user, AddTicketsToTaskToDoRequest $request, int $id)
    {
        $task = $this->repository->getOne($user, $id);
        if ($task == null) {
            throw new ModelNotFoundException("Task not founded");
        }

        $task->tickets()->attach($request->tickets);
        return $task;
    }
    public function updateTasksPosition(User $user, int $column_id, UpdateTaskToDoPositionRequest $request){
        $tasksData = $request->newPositions;
        $totalColumns = $this->repository->countTotalFromColumns($user, $column_id);

        if (count($tasksData) < $totalColumns || count($tasksData) > $totalColumns) {
            throw new DomainException("You should informate all tasks correctly");
        }

        DB::transaction(function () use ($tasksData, $user) {
            foreach ($tasksData as $data) {
                $task = $this->repository->getOne($user, $data["id"]);
                if ($task) {
                    $task->position = $data["position"];
                    $this->repository->update($task);
                } else {
                    throw new ModelNotFoundException("Task with id " . $data["id"] . " not founded");
                }
            }
        });
    }
}
