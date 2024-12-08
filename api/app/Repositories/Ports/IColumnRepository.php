<?php

namespace App\Repositories\Ports;

use App\Models\Column;
use App\Models\User;

interface IColumnRepository
{
    public function create(Column $column);
    public function getAll(User $user, int $project_id);
    public function getOne(User $user, int $id);
    public function delete(Column $column);
    public function update(Column $column);
}
