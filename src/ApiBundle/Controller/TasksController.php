<?php

namespace ApiBundle\Controller;

use StorageBundle\Entity\Task;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Swagger\Annotations as SWG;

class TasksController extends FOSRestController implements ClassResourceInterface
{
	private $doctrine;

	private $router;

	private $repository;


	public function setContainer(?ContainerInterface $container = null)
	{
		parent::setContainer($container);
		$this->doctrine = $container->get('doctrine.orm.default_entity_manager', null);
		$this->router = $container->get('router', null);
		$this->repository = $this->doctrine->getRepository(Task::class);
	}
	
	/** 
	 * @SWG\Response(response=200, description="Task", @SWG\Schema(@Model(type=Task::class)))
	 * @FOS\View()
	 */
	public function cgetAction()
	{
		return $this->repository->findAll();
	}

	/**
	 * @SWG\Response(response=200, description="Task", @SWG\Schema(@Model(type=Task::class)))
	 * @SWG\Parameter(name="task", description="Task ID", in="path", type="integer")
	 * @FOS\View()
	 */
	public function getAction(Task $task)
	{
		return $task;
	}
	
	/**
	 * @SWG\Response(response=204, description="Task")
	 * @SWG\Parameter(name="task", in="body", @SWG\Schema(@Model(type=Task::class)))
	 * @FOS\Post("/tasks")
	 * @FOS\View()
	 * @ParamConverter("task", converter="fos_rest.request_body")
	 */
	public function postAction(Task $task, ConstraintViolationListInterface $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view(['errors' => $validationErrors])->setStatusCode(400);
		}	
		$this->doctrine->persist($task);
		$this->doctrine->flush();
		$location = $this->router->generate('get_tasks', ['task' => $task->getId()]);
		return $this->view([])->setHeader('Location', $location)->setStatusCode(201);
		
	}
	
	/**
	 * @SWG\Response(response=204, description="Task")
	 * @SWG\Parameter(name="task", in="body", @SWG\Schema(@Model(type=Task::class)))
	 * @FOS\Put*=("/tasks")
	 * @ParamConverter("task", converter="fos_rest.request_body")
	 */
	public function putAction(Task $task)
	{
		$task = $this->doctrine->merge($task);
		$this->doctrine->flush();
	}

	/**
	 * @SWG\Response(response=204, description="Task")
	 */
	public function deleteAction(Task $task)
	{
		$this->doctrine->remove($task);
		$this->doctrine->flush();
	}
}