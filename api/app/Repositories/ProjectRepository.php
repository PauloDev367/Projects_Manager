<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Ports\IProjectRepository;

class ProjectRepository implements IProjectRepository
{
    public function create(User $user, Project $project)
    {
        $project->user_id = $user->id;
        $project->save();
        return $project;
    }
    public function getOne(User $user, int $id)
    {
        $project = Project::where("id", $id)
            ->where("user_id", $user->id)
            ->first();
        return $project;
    }
    public function getAll(User $user, Request $request)
    {
        $data = Project::orderBy("position")
            ->where("user_id", $user->id)
            ->paginate(30);
        return $data;
    }
    public function delete(User $user, Project $project)
    {
        $project->delete();
    }
    public function update(User $user, Project $project)
    {
        $project->save();
        return $project;
    }
}
