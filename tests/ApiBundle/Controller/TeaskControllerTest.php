<?php

namespace tests\ApiBundle\Controller;

use ApiBundle;
use StorageBundle;
use Symfony\Component\Routing\RouterInterface;
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
		$this->repository = $this->createMock(StorageBundle\Repository\TaskRepository::class);
		$this->doctrine->expects(static::once())->method('getRepository')->willReturn($this->repository);
		$this->controller = new ApiBundle\Controller\TasksController();
		$this->router = $this->getMock(RouterInterface::class);
		$map = [
			['doctrine.orm.default_entity_manager', null, $this->doctrine],
			['router', null, $this->router],
		];
		$container = $this->createMock(\Symfony\Component\DependencyInjection\Container::class);
		$container->method('get')->willReturnMap($map);
		$this->controller->setContainer($container);
	}

	public function testCget()
	{
		$task = new StorageBundle\Entity\Task();
		$this->repository->expects(static::once())->method('findAll')->willReturn([$task]);
		$results = $this->controller->cgetAction();
		$this->assertContains($task, $results, 'sample task returned');
	}

	public function testGet()
	{
		$task = new StorageBundle\Entity\Task();
		$result = $this->controller->getAction($task);
		$this->assertSame($task, $result, 'sample task returned');
	}

	public function testPost()
	{
		$task = new StorageBundle\Entity\Task();
		$errors = $this->getMock(ConstraintViolationListInterface::class);
		$this->doctrine->expects(static::once())->method('persist')->with($task);
		$this->controller->postAction($task, $errors);
	}

	public function testPut()
	{
		$task = new StorageBundle\Entity\Task();
		$this->doctrine->expects(static::once())->method('merge')->with($task);
		$this->controller->putAction($task);
	}

	public function testDelete()
	{
		$task = new StorageBundle\Entity\Task();
		$this->doctrine->expects(static::once())->method('remove')->with($task);
		$this->doctrine->expects(static::once())->method('flush');
		$this->controller->deleteAction($task);
	}
}
