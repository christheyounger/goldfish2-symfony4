<?php

namespace ApiBundle\Controller;

use StorageBundle\Entity\Task;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
	 * @ApiDoc(resource=true, output="StorageBundle\Entity\Task")
	 * @FOS\View()
	 */
	public function cgetAction()
	{
		return $this->repository->findAll();
	}

	/**
	 * @ApiDoc(resource=true, output="StorageBundle\Entity\Task") 
	 * @FOS\View()
	 */
	public function getAction(Task $task)
	{
		return $task;
	}
	
	/**
	 * @ApiDoc(resource=true, input="StorageBundle\Entity\Task")
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
	 * @ApiDoc(resource=true, input="StorageBundle\Entity\Task")
	 * @FOS\Put*=("/tasks")
	 * @ParamConverter("task", converter="fos_rest.request_body")
	 */
	public function putAction(Task $task)
	{
		$task = $this->doctrine->merge($task);
		$this->doctrine->flush();
	}

	/**
	 * @ApiDoc(resource=true)
	 */
	public function deleteAction(Task $task)
	{
		$this->doctrine->remove($task);
		$this->doctrine->flush();
	}
}