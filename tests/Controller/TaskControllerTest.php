<?php

namespace tests\App\Controller;

use App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class TasksControllerTest extends \PHPUnit\Framework\TestCase
{
	private $doctrine;

	private $repo;

	private $router;
	
	private $controller;

	public function setup()
	{
		$this->doctrine = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
		$this->repository = $this->createMock(App\Repository\TaskRepository::class);
		$this->doctrine->expects(static::once())->method('getRepository')->willReturn($this->repository);
		$this->router = $this->createMock(UrlGeneratorInterface::class);
		$this->controller = new App\Controller\TasksController($this->doctrine, $this->router);
	}

	public function testCget()
	{
		$task = new App\Entity\Task();
		$this->repository->expects(static::once())->method('findAll')->willReturn([$task]);
		$results = $this->controller->cgetAction();
		$this->assertContains($task, $results, 'sample task returned');
	}

	public function testGet()
	{
		$task = new App\Entity\Task();
		$result = $this->controller->getAction($task);
		$this->assertSame($task, $result, 'sample task returned');
	}

	public function testPost()
	{
		$task = new App\Entity\Task();
		$errors = $this->createMock(ConstraintViolationListInterface::class);
		$this->doctrine->expects(static::once())->method('persist')->with($task);
		$this->controller->postAction($task, $errors);
	}

	public function testPostBad()
	{
		$task = new App\Entity\Task();
		$errors = $this->createMock(ConstraintViolationListInterface::class);
		$errors->expects(static::once())->method('count')->willReturn(2);
		$return = $this->controller->postAction($task, $errors);
		$data = $return->getData();
		$this->assertArrayHasKey('errors', $data, 'reports there were errors');
		$this->assertEquals($errors, $data['errors'], 'reports actuall errors');
	}

	public function testPatch()
	{
		$this->doctrine->expects(static::exactly(2))->method('flush');
		$task = new App\Entity\Task();
		$task->setCompleted(false);
		$request = new Request(['action' => 'complete']);
		$this->controller->patchAction($task, $request);
		$this->assertTrue($task->isCompleted());
		$request = new Request(['action' => 'uncomplete']);
		$this->controller->patchAction($task, $request);
		$this->assertFalse($task->isCompleted());
	}

	public function testPut()
	{
		$task = new App\Entity\Task();
		$this->doctrine->expects(static::once())->method('merge')->with($task);
		$this->controller->putAction($task);
	}

	public function testDelete()
	{
		$task = new App\Entity\Task();
		$this->doctrine->expects(static::once())->method('remove')->with($task);
		$this->doctrine->expects(static::once())->method('flush');
		$this->controller->deleteAction($task);
	}
}
