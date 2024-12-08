<?php

namespace App\Repositories\Ports;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

interface IProjectRepository
{
    public function create(User $user, Project $project);
    public function getOne(User $user, int $id);
    public function getAll(User $user, Request $request);
    public function delete(User $user, Project $project);
    public function update(User $user, Project $project);
}
