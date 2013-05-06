<?php

namespace AE\ConsolidarBundle\Controller;

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


class VistaController extends Controller
{
   public function lista_consolidarAction()
   {
        return $this->render('AEConsolidarBundle:Default:lista_consolidador.html.twig');
   }
   
    public function lista_consolidadosAction()
    {
         return $this->render('AEConsolidarBundle:Default:lista_consolidados.html.twig');
    }
    
     public function vistaAction($id)
     {
         return $this->render('AEConsolidarBundle:Default:vista.html.twig', array('id'=>$id));
     }
     

}

