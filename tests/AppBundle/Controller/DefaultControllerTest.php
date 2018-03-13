<?php

namespace Tests\AppBundle\Controller;

use AppBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
    	$controller = new AppBundle\Controller\DefaultController();
    	$request = new Request();
    	$result = $controller->indexAction($request);
    	$this->assertInstanceOf(Response::class, $result, 'returns a response');
    }
}
