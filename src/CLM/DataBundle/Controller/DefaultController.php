<?php

namespace CLM\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CLMDataBundle:Default:index.html.twig', array('name' => $name));
    }
}
