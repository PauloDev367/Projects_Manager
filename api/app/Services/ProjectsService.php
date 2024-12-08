<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests\V1\CreateProjectsRequest;
use App\Http\Requests\V1\UpdateProjectsRequest;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Ports\IProjectRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectsService
{
    public function __construct(
        private readonly IProjectRepository $repository
    ) {
        
    }

    public function create(User $user, CreateProjectsRequest $request)
    {
        $project = new Project();
        $project->title = $request->title;
        $project->position = $request->position;
        $created = $this->repository->create($user, $project);
        return $created;
    }
    public function getOne(User $user, int $id)
    {
        $project = $this->repository->getOne($user, $id);
        return $project;
    }
    public function getAll(User $user, Request $request)
    {
        $data = $this->repository->getAll($user, $request);
        return $data;
    }
    public function delete(User $user, int $id)
    {
        $project = $this->repository->getOne($user, $id);
        if ($project == null) {
            throw new ModelNotFoundException("Project not founded");
        }
        $this->repository->delete($user, $project);
    }
    public function update(User $user, int $id, UpdateProjectsRequest $request)
    {
        $project = $this->repository->getOne($user, $id);
        if ($project == null) {
            throw new ModelNotFoundException("Project not founded");
        }
        $project->title = $request->title != null ? $request->title : $project->title;
        $updated = $this->repository->update($user, $project);
        return $updated;
    }
}
