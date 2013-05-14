<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\RedSocial;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\NuevoConvertido;

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
	function InformeGanarFechasAction(){
		return $this->render('AEGanarBundle:Default:informeganarfechas.html.twig');
	}
        
        public function InformeSemanalAction()
        {
            return $this->render('AEGanarBundle:Default:informesemanal.html.twig');
        }
        
        public function InformeConvertidosAction()
        {
            return $this->render('AEGanarBundle:Default:informeconvertidos.html.twig');
        }

}
