<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;

class InformeController extends Controller
{
    public function celulogramaAction()
    {
        return $this->render('AEEnviarBundle:Default:celulograma.html.twig');
    }

}
