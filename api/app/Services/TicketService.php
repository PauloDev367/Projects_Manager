<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\V1\CreateTicketRequest;
use App\Http\Requests\V1\UpdateTicketRequest;
use App\Models\Ticket;
use App\Repositories\Ports\ITicketRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketService
{
    public function __construct(
        private readonly ITicketRepository $repository
    ) {}

    public function create(User $user, CreateTicketRequest $request)
    {
        $ticket = new Ticket();
        $ticket->title = $request->title;
        $ticket->bg_color = $request->bg_color;
        $ticket->text_color = $request->text_color;
        $ticket->user_id = $user->id;
        $created = $this->repository->create($ticket);
        return $created;
    }
    public function getOne(User $user, int $id)
    {
        $ticket = $this->repository->getOne($user, $id);
        if ($ticket == null) {
            throw new ModelNotFoundException("Ticket not founded!");
        }
        return $ticket;
    }
    public function getAll(User $user)
    {
        return $this->repository->getAll($user);
    }
    public function delete(User $user, int $id)
    {
        $ticket = $this->repository->getOne($user, $id);
        if ($ticket == null) {
            throw new ModelNotFoundException("Ticket not founded!");
        }
        $this->repository->delete($ticket);
    }
    public function update(User $user, UpdateTicketRequest $request, int $id)
    {
        $ticket = $this->repository->getOne($user, $id);
        if ($ticket == null) {
            throw new ModelNotFoundException("Ticket not founded!");
        }
        $ticket->title = $request->title != null ? $request->title : $ticket->title;
        $ticket->bg_color = $request->bg_color != null ? $request->bg_color : $ticket->bg_color;
        $ticket->text_color = $request->text_color != null ? $request->text_color : $ticket->text_color;

        $updated = $this->repository->update($ticket);
        return $updated;
    }
}
