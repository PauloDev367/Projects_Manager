<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Ports\ITicketRepository;

class TicketRepository implements ITicketRepository
{
    public function create(Ticket $ticket)
    {
        $ticket->save();
        return $ticket;
    }
    public function getOne(User $user, int $id)
    {
        return Ticket::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
    }
    public function getAll(User $user)
    {
        return Ticket::where('user_id', $user->id)
            ->get();
    }
    public function delete(Ticket $ticket) {
        $ticket->delete();
    }
    public function update(Ticket $ticket) {
        $ticket->save();
        return $ticket;
    }
}
