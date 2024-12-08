<?php

namespace App\Services;

use App\Http\Requests\V1\CreateColumnRequest;
use App\Http\Requests\V1\UpdateColumnRequest;
use App\Models\Column;
use App\Models\User;
use App\Repositories\Ports\IColumnRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColumnsService
{
    public function __construct(
        private readonly IColumnRepository $repository
    ) {}

    public function create(User $user, CreateColumnRequest $request)
    {
        $column = new Column();
        $column->title = $request->title;
        $column->position = $request->position;
        $column->project_id = $request->project_id;
        $column->user_id = $user->id;

        $created = $this->repository->create($column);
        return $created;
    }
    public function getAll(User $user, int $project_id)
    {
        $data = $this->repository->getAll($user, $project_id);
        return $data;
    }
    public function delete(User $user, int $id)
    {
        $column = $this->repository->getOne($user, $id);
        if ($column == null) {
            throw new ModelNotFoundException("Column not founded");
        }
        $this->repository->delete($column);
    }
    public function update(User $user, int $id, UpdateColumnRequest $request)
    {
        $column = $this->repository->getOne($user, $id);
        if ($column == null) {
            throw new ModelNotFoundException("Column not founded");
        }

        $column->title = $request->title != null ? $request->title : $column->title;
        $column->position = $request->position != null ? $request->position : $column->position;
        $updated = $this->repository->update($column);
        return $updated;
    }
}
