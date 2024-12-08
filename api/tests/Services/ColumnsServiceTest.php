<?php

namespace Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Column;
use App\Services\ColumnsService;
use Illuminate\Routing\Redirector;
use PHPUnit\Framework\MockObject\MockObject;
use App\Http\Requests\V1\CreateColumnRequest;
use App\Http\Requests\V1\UpdateColumnRequest;
use App\Repositories\ColumnRepository;
use App\Repositories\Ports\IColumnRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function test_should_return_columns_data_when_getAll_is_called()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);
        $columnsService = new ColumnsService($repositoryMock);

        $user = new User();
        $project_id = 1;
        $c1 = new Column();
        $c1->title = "title";
        $c1->position = 1;
        $columns = [$c1, $c1];

        $repositoryMock
            ->expects($this->once())
            ->method('getAll')
            ->with($user, $project_id)
            ->willReturn($columns);

        $result = $columnsService->getAll($user, $project_id);

        $this->assertSame($columns, $result);
    }

    public function test_should_call_getAll_with_correct_parameters()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);
        $columnsService = new ColumnsService($repositoryMock);

        $user = new User();
        $user->id = 1;
        $project_id = 123;

        $repositoryMock
            ->expects($this->once())
            ->method('getAll')
            ->with($user, $project_id)
            ->willReturn([]);

        $columnsService->getAll($user, $project_id);
    }
    public function test_should_delete_column_when_column_exists()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);

        $user = new User();
        $columnId = 1;
        $column = new Column();

        $repositoryMock->expects($this->once())
            ->method('getOne')
            ->with($user, $columnId)
            ->willReturn($column);

        $repositoryMock->expects($this->once())
            ->method('delete')
            ->with($column);

        $columnsService = new ColumnsService($repositoryMock);
        $columnsService->delete($user, $columnId);
    }

    public function test_should_throw_exception_when_column_not_found()
    {
        $repositoryMock = $this->createMock(IColumnRepository::class);

        $user = new User();
        $columnId = 1;

        $repositoryMock->expects($this->once())
            ->method('getOne')
            ->with($user, $columnId)
            ->willReturn(null);

        $columnsService = new ColumnsService($repositoryMock);

        $this->expectException(ModelNotFoundException::class);

        $columnsService->delete($user, $columnId);
    }
    public function test_update_column_not_found()
    {
        $user = new User();
        $user->id = 1; 

        $request = new UpdateColumnRequest([
            'title' => 'New Title'
        ]);

        $repository = $this->createMock(ColumnRepository::class);
        $repository->method('getOne')->willReturn(null);

        $service = new ColumnsService($repository);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Column not founded');

        $service->update($user, 1, $request);
    }

    public function test_update_column_with_valid_data()
    {
        $user = new User();
        $user->id = 1;

        $column = new Column();
        $column->id = 1;
        $column->title = 'Old Title';

        $request = new UpdateColumnRequest([
            'title' => 'Updated Title'
        ]);

        $repository = $this->createMock(ColumnRepository::class);
        $repository->method('getOne')->willReturn($column);
        $repository->method('update')->willReturn($column);

        $service = new ColumnsService($repository);

        $updatedColumn = $service->update($user, $column->id, $request);

        $this->assertEquals('Updated Title', $updatedColumn->title);
    }

    public function test_update_column_without_changing_title()
    {
        $user = new User();
        $user->id = 1;

        $column = new Column();
        $column->id = 1;
        $column->title = 'Old Title';

        $request = new UpdateColumnRequest([
            'title' => null
        ]);

        $repository = $this->createMock(ColumnRepository::class);
        $repository->method('getOne')->willReturn($column);
        $repository->method('update')->willReturn($column); 

        $service = new ColumnsService($repository);

        $updatedColumn = $service->update($user, $column->id, $request);

        $this->assertEquals('Old Title', $updatedColumn->title);
    }
}
