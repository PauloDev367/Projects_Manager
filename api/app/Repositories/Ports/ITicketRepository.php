<?php

namespace App\Repositories\Ports;

use App\Models\User;
use App\Models\Ticket;

interface ITicketRepository
{
    public function create(Ticket $ticket);
    public function getOne(User $user, int $id);
    public function getAll(User $user);
    public function update(Ticket $ticket);
    public function delete(Ticket $ticket);
}
