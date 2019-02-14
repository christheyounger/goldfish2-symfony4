<?php

namespace Tests\App\Controller;

use App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
    	$controller = new App\Controller\DefaultController();
    	$request = new Request();
    	$result = $controller->indexAction($request);
    	$this->assertInstanceOf(Response::class, $result, 'returns a response');
    }
}
