<?php

namespace AE\DiscipularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class InformeController extends Controller
{
	function InformeVisionAction(){
            
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
            
                if($securityContext->isGranted('ROLE_DISCIPULAR'))
                    $red = NULL;

            }


            return $this->render('AEDiscipularBundle:Default:informevision.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

        }
	
	function InformeCursoRedesAction(){
            
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
            
                if($securityContext->isGranted('ROLE_DISCIPULAR'))
                    $red = NULL;

            }


            return $this->render('AEDiscipularBundle:Default:informecursored.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
	}
	
	function InformeSemanalIndeliAction()
        {
            
             $securityContext = $this->get('security.context');
        
            if($securityContext->isGranted('ROLE_LIDER_RED'))
            {            
                return $this->render('AEDiscipularBundle:Default:informesemanalindeli.html.twig');
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
	}

}
