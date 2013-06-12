<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\Consolida;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEConsolidarBundle:Default:index.html.twig', array('name' => $name));
    }


}
