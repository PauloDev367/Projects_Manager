<?php

namespace App\Repositories;

use App\Models\Column;
use App\Models\User;
use App\Repositories\Ports\IColumnRepository;

class ColumnRepository implements IColumnRepository
{
    public function create(Column $column)
    {
        $column->save();
        return $column;
    }
    public function getAll(User $user, int $project_id)
    {
        return Column::where("user_id", $user->id)
            ->where("project_id", $project_id)
            ->orderBy("position")
            ->get();
    }
    public function getOne(User $user, int $id)
    {
        return Column::where("user_id", $user->id)
            ->where("id", $id)
            ->first();
    }
    public function delete(Column $column)
    {
        $column->delete();
    }
    public function update(Column $column)
    {
        $column->save();
        return $column;
    }
    public function countTotal(User $user, int $project_id)
    {
        return Column::where("user_id", $user->id)
            ->where("project_id", $project_id)
            ->count();
    }
}
