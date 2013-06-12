<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class RolesController extends Controller
{
  
    public function vistaAction()
    {
        return $this->render('AEAdministrarBundle:Roles:update_rol.html.twig');
    }
}

