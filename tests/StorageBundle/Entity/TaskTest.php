<?php

namespace tests\StorageBundle\Entity;

use StorageBundle;

class TaskTest extends \PHPUnit\Framework\TestCase
{
	public function testTask()
	{
		$task = new StorageBundle\Entity\Task();
		$task->setId(10);
		$this->assertEquals(10, $task->getId(), 'get the right ID');
		$task->setCompleted(true);
		$this->assertTrue($task->isCompleted(), 'set completed correctly');
		$task->setTitle('title');
		$this->assertEquals('title', $task->getTitle(), 'get the right title');
		$task->setDescription('description');
		$this->assertEquals('description', $task->getDescription(), 'get the right description');
		$task->setTime(100);
		$this->assertEquals(100, $task->getTime(), 'get the right time');
		$date = new \DateTime();
		$task->setDue($date);
		$this->assertEquals($date, $task->getDue(), 'get the right due date');
	}
}