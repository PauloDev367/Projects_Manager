<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateTicketRequest;
use App\Http\Requests\V1\UpdateTicketRequest;
use App\Models\User;
use App\Services\TicketService;

class TicketController extends Controller
{
    private readonly ?User $authUser;

    public function __construct(
        private readonly TicketService $service
    ) {
        $this->authUser = auth()->user();
    }
    public function create(CreateTicketRequest $request)
    {
        $created = $this->service->create($this->authUser, $request);
        return response()->json(['sucess' => $created]);
    }
    public function getOne(int $id)
    {
        $data = $this->service->getOne($this->authUser, $id);
        return response()->json(['sucess' => $data]);
    }
    public function getAll()
    {
        $data = $this->service->getAll($this->authUser);
        return response()->json(['sucess' => $data]);
    }
    public function delete(int $id)
    {
        $this->service->delete($this->authUser, $id);
        return response()->noContent();
    }
    public function update(UpdateTicketRequest $request, int $id)
    {
        $updated = $this->service->update($this->authUser, $request, $id);
        return response()->json(['sucess' => $updated]);
    }
}
