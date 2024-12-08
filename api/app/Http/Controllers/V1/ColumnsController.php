<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateColumnRequest;
use App\Http\Requests\V1\UpdateColumnRequest;
use App\Models\User;
use App\Services\ColumnsService;

class ColumnsController extends Controller
{
    private readonly ?User $authUser;

    public function __construct(
        private readonly ColumnsService $service
    ) {
        $this->authUser = auth()->user();
    }

    public function create(CreateColumnRequest $request)
    {
        $created = $this->service->create($this->authUser, $request);
        return response()->json(["success" => $created], 201);
    }
    public function getAll(int $id)
    {
        $data = $this->service->getAll($this->authUser, $id);
        return response()->json(["success" => $data]);
    }
    public function delete(int $id)
    {
        $this->service->delete($this->authUser, $id);
        return response()->noContent();
    }
    public function update(UpdateColumnRequest $request, int $id)
    {
        $update = $this->service->update($this->authUser, $id, $request);
        return response()->json(["success" => $update]);
    }
}
