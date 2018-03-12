<?php

namespace ApiBundle\Controller;

use StorageBundle\Entity\Task;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TasksController extends FOSRestController implements ClassResourceInterface
{
	/** 
	 * @ApiDoc(resource=true)
	 * @FOS\View()
	 */
	public function cgetAction()
	{
		return $this->getDoctrine()->getRepository(Task::class)->findAll();
	}

	/** @FOS\View() */
	public function getAction(Task $task)
	{
		return $task;
	}
	
	/**
	 * @FOS\Post("/tasks")
	 * @FOS\View()
	 * @ParamConverter("task", converter="fos_rest.request_body")
	 */
	public function postAction(Task $task, ConstraintViolationListInterface $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view(['errors' => $validationErrors])->setStatusCode(400);
		}
		$this->getDoctrine()->getManager()->persist($task);
		$this->getDoctrine()->getManager()->flush();
		return ['id' => $task->getId()];
	}
	
	/**
	 * @FOS\Put*=("/tasks")
	 * @ParamConverter("task", converter="fos_rest.request_body")
	 */
	public function putAction(Task $task)
	{
		$task = $this->getDoctrine()->getManager()->merge($task);
		$this->getDoctrine()->getManager()->flush();
	}

	public function deleteAction(Task $task)
	{
		$this->getDoctrine()->getManager()->remove($task);
		$this->getDoctrine()->getManager()->flush();
	}
}