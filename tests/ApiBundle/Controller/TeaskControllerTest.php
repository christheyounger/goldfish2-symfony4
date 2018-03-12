<?php

namespace tests\ApiBundle\Controller;

use ApiBundle;
use StorageBundle;

class TasksControllerTest extends \PHPUnit\Framework\TestCase
{
	private $doctrine;

	private $repo;
	
	private $controller;

	public function setup()
	{
		$this->doctrine = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
		$this->repository = $this->createMock(StorageBundle\Repository\TaskRepository::class);
		$this->doctrine->expects(static::once())->method('getRepository')->willReturn($this->repository);
		$this->controller = new ApiBundle\Controller\TasksController();
		$container = $this->createMock(\Symfony\Component\DependencyInjection\Container::class);
		$container->method('get')->willReturnMap([['doctrine.orm.default_entity_manager', null, $this->doctrine]]);
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

	public function testPut()
	{
		$task = new StorageBundle\Entity\Task();
		$this->doctrine->expects(static::once())->method('merge')->with($task);
		$this->controller->putAction($task);
	}
}
