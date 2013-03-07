<?php
namespace AE\DiscipularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CrearCursoController extends Controller{

	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:crearcurso.html.twig');
	}	
}