<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Swagger\Annotations as SWG;

class TasksController extends AbstractFOSRestController implements ClassResourceInterface
{
	private $doctrine;

	private $router;

	private $repository;


	public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router)
	{
		$this->doctrine = $em;
		$this->router = $router;
		$this->repository = $this->doctrine->getRepository(Task::class);
	}
	
	/** 
	 * @SWG\Response(response=200, description="Task", @SWG\Schema(ref=@Model(type=Task::class)))
	 * @FOS\View()
	 */
	public function cgetAction()
	{
		return $this->repository->findAll();
	}

	/**
	 * @SWG\Response(response=200, description="Task", @SWG\Schema(ref=@Model(type=Task::class)))
	 * @SWG\Parameter(name="task", description="Task ID", in="path", type="integer")
	 * @FOS\View()
	 */
	public function getAction(Task $task)
	{
		return $task;
	}
	
	/**
	 * @SWG\Response(response=204, description="Task")
	 * @SWG\Parameter(name="task", in="body", @SWG\Schema(ref=@Model(type=Task::class)))
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
	 * @FOS\RequestParam(name="action", requirements="complete|uncomplete")
	 */
	public function patchAction(Task $task, Request $request)
	{
		$action = $request->get('action');
		switch ($action) {
			case "complete":
				$task->setCompleted(true);
				$this->doctrine->flush();
				break;
			case "uncomplete":
				$task->setCompleted(false);
				$this->doctrine->flush();
				break;
		}
	}
	
	/**
	 * @SWG\Response(response=204, description="Task")
	 * @SWG\Parameter(name="task", in="body", @SWG\Schema(ref=@Model(type=Task::class)))
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