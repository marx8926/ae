<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class BusquedaController extends Controller
{
    public function busquedaAction()
    {
        
         $securityContext = $this->get('security.context');
        
         if($securityContext->isGranted('ROLE_LIDER_RED'))
         {
             
             $ganador = $securityContext->getToken()->getUser()->getIdPersona();
                $red = NULL;
                $em = $this->getDoctrine()->getEntityManager();
        
                if($ganador != NULL)
                {
                    $sql = "select * from get_red_persona(:id)";
                    $smt = $em->getConnection()->prepare($sql);
                    $smt->execute(array(':id'=>$ganador->getId()));
                    $req = $smt->fetch();
                    if(count($req)>0)
                    $red = $req['red'];
                }
                
               
              if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                    $red = NULL;
              
              return $this->render('AEConsolidarBundle:Default:busqueda.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
}

