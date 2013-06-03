<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        public function InformeLiderAction()
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
                if(count($req)>0)
                $red = $req['red'];
            }
            return $this->render('AEGanarBundle:Default:informeporlider.html.twig', array('red'=>$red));
        }
        
        public function InformeLider12Action()
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
                if(count($req)>0)
                $red = $req['red'];
            }
            return $this->render('AEGanarBundle:Default:informeporlider12.html.twig', array('red'=>$red));
        }
        
        public function InformeLider144Action()
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
                if(count($req)>0)
                $red = $req['red'];
            }
            
            return $this->render('AEGanarBundle:Default:informeporlider144.html.twig');
        }
        
        public function InformeLider1728Action()
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
            
                if(count($req)>0)
                $red = $req['red'];
            }
            return $this->render('AEGanarBundle:Default:informeporlider12.html.twig', array('red'=>$red));
        }
}
