<?php
namespace AE\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;

class DiscipularServicioController extends Controller
{
	public function getCursosOptionAction(){
		
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select * from curso";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		$result = "hola";
	
	    foreach ($todo as $key => $val){
	        $result = $result.$val['titulo'];
	    }
		
		return new Response($result);
	}
}
