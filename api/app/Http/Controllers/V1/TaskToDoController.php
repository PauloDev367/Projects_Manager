<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TaskToDoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateTaskToDoRequest;
use App\Http\Requests\V1\UpdateTaskToDoRequest;
use App\Http\Requests\V1\AddTicketsToTaskToDoRequest;
use App\Http\Requests\V1\UpdateTaskToDoPositionRequest;

class TaskToDoController extends Controller
{
    private readonly ?User $authUser;

    public function __construct(
        private readonly TaskToDoService $service
    ) {
        $this->authUser = auth()->user();
    }
    public function create(CreateTaskToDoRequest $request)
    {
        $created = $this->service->create($this->authUser, $request);
        return response()->json(["success" => $created], 201);
    }
    public function getOne(int $id)
    {
        $data = $this->service->getOne($this->authUser, $id);
        if ($data == null) {
            return response()->json(["error" => "Task not founded"], 404);
        }
        return response()->json(["success" => $data]);
    }
    public function getAll(int $column_id)
    {
        $data = $this->service->getAll($this->authUser, $column_id);
        return response()->json(["success" => $data]);
    }
    public function delete(int $id)
    {
        $this->service->delete($this->authUser, $id);
        return response()->noContent();
    }
    public function update(UpdateTaskToDoRequest $request, int $id)
    {
        $updated = $this->service->update($this->authUser, $request, $id);
        return response()->json(["success" => $updated]);
    }
    public function setTickets(AddTicketsToTaskToDoRequest $request, int $id)
    {
        $data = $this->service->setTickets($this->authUser, $request, $id);
        return response()->json(["success" => $data]);
    }

    public function updateTasksPosition(UpdateTaskToDoPositionRequest $request, int $column_id)
    {
        $this->service->updateTasksPosition($this->authUser, $column_id, $request);
        return response()->noContent();
    }
}
