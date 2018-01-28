<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @FOS\Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return new Response("", 200);
    }
}
