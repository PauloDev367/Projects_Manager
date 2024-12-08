<?php

namespace Services;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Services\TicketService;
use App\Http\Requests\V1\CreateTicketRequest;
use App\Http\Requests\V1\UpdateTicketRequest;
use App\Repositories\Ports\ITicketRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketServiceTest extends TestCase
{
    public function test_create_ticket_success()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $request = new CreateTicketRequest([
            'title' => 'Sample Ticket',
            'bg_color' => 'red',
            'text_color' => 'white'
        ]);

        $ticket = new Ticket();
        $ticket->title = 'Sample Ticket';
        $ticket->bg_color = 'red';
        $ticket->text_color = 'white';
        $ticket->user_id = 1;

        $repositoryMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($ticket) {
                return $arg instanceof Ticket &&
                    $arg->title === $ticket->title &&
                    $arg->bg_color === $ticket->bg_color &&
                    $arg->text_color === $ticket->text_color &&
                    $arg->user_id === $ticket->user_id;
            }))
            ->andReturn($ticket);

        $service = new TicketService($repositoryMock);

        $createdTicket = $service->create($user, $request);

        $this->assertEquals('Sample Ticket', $createdTicket->title);
        $this->assertEquals('red', $createdTicket->bg_color);
        $this->assertEquals('white', $createdTicket->text_color);
        $this->assertEquals(1, $createdTicket->user_id);
    }


    public function test_get_one_ticket_success()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->id = 1;
        $ticket->title = 'Sample Ticket';

        $repositoryMock->shouldReceive('getOne')
            ->once()
            ->with($user, 1)
            ->andReturn($ticket);

        $service = new TicketService($repositoryMock);

        $result = $service->getOne($user, 1);

        $this->assertEquals('Sample Ticket', $result->title);
    }


    public function test_get_one_ticket_not_found()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $repositoryMock->shouldReceive('getOne')
            ->once()
            ->with($user, 1)
            ->andReturn(null);
        $service = new TicketService($repositoryMock);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Ticket not founded!');

        $service->getOne($user, 1);
    }

    public function test_get_all_tickets_success()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->title = 'Sample Ticket';

        // Usando Mockery corretamente para definir a expectativa
        $repositoryMock->shouldReceive('getAll')
            ->once()  // Espera que o método seja chamado uma vez
            ->with($user)  // Expectativa dos parâmetros
            ->andReturn([$ticket]);  // Retorna um array com o ticket

        $service = new TicketService($repositoryMock);

        $result = $service->getAll($user);

        $this->assertCount(1, $result);
        $this->assertEquals('Sample Ticket', $result[0]->title);
    }
    public function test_delete_ticket_success()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->id = 1;
        $ticket->title = 'Sample Ticket';

        $repositoryMock->shouldReceive('getOne')
            ->once()
            ->with($user, 1)
            ->andReturn($ticket);

        $repositoryMock->shouldReceive('delete')
            ->once()
            ->with($ticket);
        $service = new TicketService($repositoryMock);

        $service->delete($user, 1);
    }

    public function test_delete_ticket_not_found()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $repositoryMock->shouldReceive("getOne")
            ->once()
            ->with($user, 1)
            ->andReturn(null);

        $service = new TicketService($repositoryMock);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Ticket not founded!');

        $service->delete($user, 1);
    }

    public function test_update_ticket_success()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->id = 1;
        $ticket->title = 'Sample Ticket';
        $ticket->bg_color = 'red';
        $ticket->text_color = 'white';

        $request = new UpdateTicketRequest([
            'title' => 'Updated Ticket',
            'bg_color' => 'blue',
            'text_color' => 'yellow'
        ]);

        $repositoryMock->shouldReceive("getOne")
            ->once()
            ->with($user, 1)
            ->andReturn($ticket);

        $repositoryMock->shouldReceive("update")
            ->once()
            ->with($ticket)
            ->andReturn($ticket);

        $service = new TicketService($repositoryMock);

        $updatedTicket = $service->update($user, $request, 1);

        $this->assertEquals('Updated Ticket', $updatedTicket->title);
        $this->assertEquals('blue', $updatedTicket->bg_color);
        $this->assertEquals('yellow', $updatedTicket->text_color);
    }

    public function test_update_ticket_not_found()
    {
        $repositoryMock = Mockery::mock(ITicketRepository::class);

        $user = new User();
        $user->id = 1;

        $repositoryMock->shouldReceive("getOne")
            ->once()
            ->with($user, 1)
            ->andReturn(null);

        $request = new UpdateTicketRequest([
            'title' => 'Updated Ticket',
        ]);

        $service = new TicketService($repositoryMock);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Ticket not founded!');

        $service->update($user, $request, 1);
    }
}
