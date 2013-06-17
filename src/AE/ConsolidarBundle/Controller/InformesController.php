<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class InformesController extends Controller
{
    public function informeNoconsolidadoAction()
    {
        
        $securityContext = $this->get('security.context');
        
        $ganador = $securityContext->getToken()->getUser()->getIdPersona();
        $red = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        
        if($ganador != NULL)
        {
            $sql = "select * from get_red_persona(:id)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$ganador->getId()));
            $req = $smt->fetch();
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:informeNoconsolidado.html.twig',array('red'=>$red));
    }
    
    public function informeHerramientasAction()
    {
        $securityContext = $this->get('security.context');
        
        $ganador = $securityContext->getToken()->getUser()->getIdPersona();
        $red = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        
        if($ganador != NULL)
        {
            $sql = "select * from get_red_persona(:id)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$ganador->getId()));
            $req = $smt->fetch();
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:informeHerramientas.html.twig',array('red'=>$red));
    }
    
    public function informeLecheEspiritualAction()
    {
        $securityContext = $this->get('security.context');
        
        $ganador = $securityContext->getToken()->getUser()->getIdPersona();
        $red = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        
        if($ganador != NULL)
        {
            $sql = "select * from get_red_persona(:id)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$ganador->getId()));
            $req = $smt->fetch();
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:InformeLecheEspiritual.html.twig',array('red'=>$red));
    }
    
    public function informeDescartadosAction()
    {
        $securityContext = $this->get('security.context');
        
        $ganador = $securityContext->getToken()->getUser()->getIdPersona();
        $red = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        
        if($ganador != NULL)
        {
            $sql = "select * from get_red_persona(:id)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$ganador->getId()));
            $req = $smt->fetch();
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:InformeDescartados.html.twig', array('red'=>$red));
    }
    
    public function informeDetalladoAction()
    {
         $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_CONSOLIDADOR'))
        {
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $consolidador=NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
               if(count($req)>0)
                $red = $req['red'];
            
            
            
            if($securityContext->isGranted('ROLE_CONSOLIDADOR'))
                $consolidador = $ganador->getId();
            
            if($securityContext->isGranted('ROLE_LIDER_RED'))
                $consolidador = NULL;
            
            if($securityContext->isGranted('ROLE_CONSOLIDAR'))
            {
                $red = NULL;
                $consolidador = NULL;
            }
            }
        return $this->render('AEConsolidarBundle:Default:informedetallado.html.twig',array('red'=>$red,
            'consolidador'=>$consolidador));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
    public function informeResumidoAction()
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
                    $red = NULL;
            }
        return $this->render('AEConsolidarBundle:Default:informegeneral.html.twig',array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
}

