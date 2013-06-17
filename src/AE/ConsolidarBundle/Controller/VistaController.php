<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class VistaController extends Controller
{
   public function lista_consolidarAction()
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
                
                if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                    $red =NULL;
            }
        return $this->render('AEConsolidarBundle:Default:lista_consolidador.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

   }
   
    public function lista_consolidadosAction()
    {
         $securityContext = $this->get('security.context');
         if($securityContext->isGranted('ROLE_CONSOLIDADOR'))
         {
     
            $securityContext = $this->get('security.context');
        
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $consolidador =NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                {
                    $red = $req['red'];
                    $consolidador= $ganador->getId();
                }
                if($securityContext->isGranted('ROLE_LIDER_RED'))
                {
                    $consolidador = NULL;
                }
                
                if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                {
                    $red =NULL;
                    $consolidador=NULL;
                }
                
            }
            
            return $this->render('AEConsolidarBundle:Default:lista_consolidados.html.twig',array('red'=>$red
                    ,'consolidador'=>$consolidador));
         }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
     public function vistaAction($id)
     {
         return $this->render('AEConsolidarBundle:Default:vista.html.twig', array('id'=>$id));
     }
     

}

