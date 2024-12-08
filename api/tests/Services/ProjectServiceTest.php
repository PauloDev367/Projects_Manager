<?php

namespace Services;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use InvalidArgumentException;
use App\Services\ProjectsService;
use App\Repositories\Ports\IProjectRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\V1\CreateProjectsRequest;

class ProjectServiceTest extends TestCase
{
    public function test_create_project_success()
    {
        // Criando um mock do repositório IProjectRepository
        $repositoryMock = Mockery::mock(IProjectRepository::class);

        // Criando um usuário fictício
        $user = new User();
        $user->id = 1;

        // Criando uma request fictícia com título e posição do projeto
        $request = new CreateProjectsRequest([
            'title' => 'New Project',
            'position' => 1
        ]);

        // Criando o projeto que seria retornado pelo repositório
        $project = new Project();
        $project->title = 'New Project';
        $project->position = 1;

        // Configurando o mock para retornar o projeto criado
        $repositoryMock->shouldReceive('create')
            ->once()
            ->with($user, Mockery::on(function ($projectParam) use ($project) {
                return $projectParam->title === $project->title && $projectParam->position === $project->position;
            }))
            ->andReturn($project);

        // Instanciando o serviço com o repositório mockado
        $service = new ProjectsService($repositoryMock);

        // Chamando o método create
        $createdProject = $service->create($user, $request);

        // Asserts para verificar se o projeto foi criado corretamente
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
}
