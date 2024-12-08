<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateProjectsRequest;
use App\Http\Requests\V1\UpdateProjectsRequest;
use App\Models\User;
use App\Services\ProjectsService;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    private readonly ?User $authUser;

    public function __construct(
        private readonly ProjectsService $service
    ) {
        $this->authUser = auth()->user();
    }

    public function create(CreateProjectsRequest $request)
    {
        $created = $this->service->create($this->authUser, $request);
        return response()->json(["success" => $created], 201);
    }
    public function getOne(int $id)
    {
        $data = $this->service->getOne($this->authUser, $id);
        if ($data == null) {
            return response()->json(["error" => "Project not founded"], 404);
        }
        return response()->json(["success" => $data]);
    }
    public function getAll(Request $request)
    {
        $data = $this->service->getAll($this->authUser, $request);
        return response()->json(["success" => $data]);
    }
    public function delete(int $id)
    {
        $this->service->delete($this->authUser, $id);
        return response()->noContent();
    }
    public function update(int $id, UpdateProjectsRequest $request)
    {
        $update = $this->service->update($this->authUser, $id, $request);
        return response()->json(["success" => $update]);
    }
}
