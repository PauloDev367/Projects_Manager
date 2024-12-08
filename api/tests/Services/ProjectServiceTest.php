<?php

namespace Services;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Services\ProjectsService;
use App\Repositories\Ports\IProjectRepository;
use App\Http\Requests\V1\CreateProjectsRequest;
use Illuminate\Http\Request;

class ProjectServiceTest extends TestCase
{
    public function test_create_project_success()
    {
        $repositoryMock = Mockery::mock(IProjectRepository::class);

        $user = new User();
        $user->id = 1;

        $request = new CreateProjectsRequest([
            'title' => 'New Project',
            'position' => 1
        ]);

        $project = new Project();
        $project->title = 'New Project';
        $project->position = 1;

        $repositoryMock->shouldReceive('create')
            ->once()
            ->with($user, Mockery::on(function ($projectParam) use ($project) {
                return $projectParam->title === $project->title && $projectParam->position === $project->position;
            }))
            ->andReturn($project);

        $service = new ProjectsService($repositoryMock);

        $createdProject = $service->create($user, $request);

        $this->assertInstanceOf(Project::class, $createdProject);
        $this->assertEquals('New Project', $createdProject->title);
        $this->assertEquals(1, $createdProject->position);
    }

    public function test_create_project_failure_due_to_missing_title()
    {
        $repositoryMock = $this->createMock(IProjectRepository::class);

        $request = new CreateProjectsRequest();
        $request->merge([]);

        $request->setContainer(app());
        $request->setRedirector(app(\Illuminate\Routing\Redirector::class));

        $user = new User();
        $user->id = 1;

        $service = new ProjectsService($repositoryMock);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request->validateResolved();
        $service->create($user, $request);

        $repositoryMock->expects($this->never())->method('create');
    }
    public function test_get_one_project_success()
    {
        $repositoryMock = $this->createMock(IProjectRepository::class);

        $user = new User();
        $user->id = 1;

        $project = new Project();
        $project->id = 1;
        $project->title = "Test Project";
        $project->position = 1;

        $repositoryMock->expects($this->once())
            ->method('getOne')
            ->with($user, 1)
            ->willReturn($project);

        $service = new ProjectsService($repositoryMock);

        $result = $service->getOne($user, 1);
        $this->assertSame($project, $result);
    }

    public function test_get_one_project_not_found()
    {
        $repositoryMock = $this->createMock(IProjectRepository::class);

        $user = new User();
        $user->id = 1;

        $repositoryMock->expects($this->once())
            ->method('getOne')
            ->with($user, 1)
            ->willReturn(null);

        $service = new ProjectsService($repositoryMock);

        $result = $service->getOne($user, 1);

        $this->assertNull($result);
    }
    public function test_get_all_projects_success()
    {
        $repositoryMock = $this->createMock(IProjectRepository::class);
        $request = new Request();

        $user = new User();
        $user->id = 1;
        $p1 = new Project();
        $p1->title = "Project 1";
        $p1->id =  1;
        $p2 = new Project();
        $p2->title = "Project 2";
        $p2->id =  2;

        $projectData = collect([$p1, $p2]);

        $repositoryMock->method('getAll')
            ->with($user, $request)
            ->willReturn($projectData);

        $service = new ProjectsService($repositoryMock);

        $result = $service->getAll($user, $request);

        $this->assertCount(2, $result);
        $this->assertEquals('Project 1', $result[0]->title);
        $this->assertEquals('Project 2', $result[1]->title);
    }

    public function test_get_all_projects_empty_result()
    {
        $repositoryMock = $this->createMock(IProjectRepository::class);
        $request = new Request();

        $user = new User();
        $user->id = 1;

        $repositoryMock->method('getAll')
            ->with($user, $request)
            ->willReturn(collect([]));

        $service = new ProjectsService($repositoryMock);

        $result = $service->getAll($user, $request);

        $this->assertCount(0, $result);
    }
}
