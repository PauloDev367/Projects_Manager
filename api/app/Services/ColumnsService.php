<?php

namespace App\Services;

use App\Http\Requests\V1\CreateColumnRequest;
use App\Http\Requests\V1\UpdateColumnRequest;
use App\Http\Requests\V1\UpdateColumnsPositionRequest;
use App\Models\Column;
use App\Models\User;
use App\Repositories\Ports\IColumnRepository;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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
        $updated = $this->repository->update($column);
        return $updated;
    }
    public function updateColumnsPosition(User $user, int $project_id, UpdateColumnsPositionRequest $request)
    {
        $columnsData = $request->newPositions;
        $totalColumns = $this->repository->countTotal($user, $project_id);

        if (count($columnsData) < $totalColumns || count($columnsData) > $totalColumns) {
            throw new DomainException("You should informate all columns correctly");
        }

        DB::transaction(function () use ($columnsData, $user) {
            foreach ($columnsData as $data) {
                $column = $this->repository->getOne($user, $data["id"]);
                if ($column) {
                    $column->position = $data["position"];
                    $this->repository->update($column);
                } else {
                    throw new ModelNotFoundException("Column with id " . $data["id"] . " not founded");
                }
            }
        });
    }
}
