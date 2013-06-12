<?php

namespace AE\DiscipularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class InformeController extends Controller
{
	function InformeVisionAction(){
		return $this->render('AEDiscipularBundle:Default:informevision.html.twig');
	}
	
	function InformeCursoRedesAction(){
		return $this->render('AEDiscipularBundle:Default:informecursored.html.twig');
	}
	
	function InformeSemanalIndeliAction(){
		return $this->render('AEDiscipularBundle:Default:informesemanalindeli.html.twig');
	}

}
