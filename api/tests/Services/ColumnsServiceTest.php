<?php

namespace Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Column;
use App\Services\ColumnsService;
use App\Http\Requests\V1\CreateColumnRequest;
use App\Repositories\Ports\IColumnRepository;
use Illuminate\Validation\ValidationException;

class ColumnsServiceTest extends TestCase
{

    public function test_should_create_new_column()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);

        $request = new CreateColumnRequest();
        $request->merge([
            'title' => 'Test Column',
            'position' => 1,
            'project_id' => 100,
        ]);

        $this->assertTrue(
            validator($request->all(), $request->rules())->passes(),
            'A validação do request falhou.'
        );

        $column = new Column();
        $column->title = 'Test Column';
        $column->position = 1;
        $column->project_id = 100;

        $repositoryMock->method('create')->willReturn($column);

        $service = new ColumnsService($repositoryMock);

        $user = new User();
        $user->id = 1;

        $created = $service->create($user, $request);

        $this->assertEquals($column, $created);
    }
    public function test_should_not_create_new_column_if_request_is_not_valid()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);

        $request = new CreateColumnRequest();
        $request->merge([]);

        $request->setContainer(app());
        $request->setRedirector(app(\Illuminate\Routing\Redirector::class));

        $user = new User();
        $user->id = 1;

        $service = new ColumnsService($repositoryMock);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request->validateResolved();
        $service->create($user, $request);

        $repositoryMock->expects($this->never())->method('create');
    }
}
