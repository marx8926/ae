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
                
                if($securityContext->isGranted('ROLE_GANAR'))
                    $red = NULL;
                    
                return $this->render('AEGanarBundle:Default:informeporlider.html.twig', array('red'=>$red));
            }
           else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

        }
        
        public function InformeLider12Action()
        {
            $securityContext = $this->get('security.context');
            
            if($securityContext->isGranted('ROLE_LIDER12'))
            {
        
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $lider = NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                $red = $req['red'];
                
                $lider = $ganador->getId();
            }
            
            
             if($securityContext->isGranted('ROLE_GANAR'))
             {
                 $red = NULL;
                 $lider = NULL;
             }
             
             if($securityContext->isGranted('ROLE_LIDER_RED'))
                    $lider = NULL;
              
            return $this->render('AEGanarBundle:Default:informeporlider12.html.twig', array('red'=>$red,
                'lider'=>$lider));
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    
        }
        
        public function InformeLider144Action()
        {
            
             $securityContext = $this->get('security.context');
             
             if($securityContext->isGranted('ROLE_LIDER144'))
            {
        
        
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $em = $this->getDoctrine()->getEntityManager();
            
            $doce = NULL;
            $ciento = NULL;
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                $red = $req['red'];
                
                if($securityContext->isGranted('ROLE_LIDER144'))
                {
                    $sql1= "select * from get_red_persona_padre(:persona)";
                    $smt1 = $em->getConnection()->prepare($sql1);
                    $smt1->execute(array(':persona'=>$ganador->getId()));
                    $tod = $smt1->fetch();
                    if(count($tod)>0){
                        $doce = $tod['padre'];
                    }
                    $ciento = $ganador->getId();

                }
                if($securityContext->isGranted('ROLE_LIDER12'))
                {
                    $doce = $ganador->getId();
                    $ciento=NULL;
                }
                
                if($securityContext->isGranted('ROLE_LIDER_RED'))
                {
                    $doce=NULL;
                    $ciento=NULL;
                }

                if($securityContext->isGranted('ROLE_GANAR'))
                {
                    $doce=NULL;
                    $ciento=NULL;
                    $red=NULL;
                }
            }
            
            return $this->render('AEGanarBundle:Default:informeporlider144.html.twig', array('red'=>$red,'doce'=>$doce,
                'ciento'=>$ciento));
            }
            
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

        }
        
        public function InformeLider1728Action()
        {
            $securityContext = $this->get('security.context');
        
             if($securityContext->isGranted('ROLE_LIDER1728'))
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
            return $this->render('AEGanarBundle:Default:informeporlider1728.html.twig', array('red'=>$red));

            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

        }
        
        public function InformeSemanalGanarAction()
        {
            $securityContext = $this->get('security.context');

            if($securityContext->isGranted('ROLE_GANAR'))
                   return $this->render('AEGanarBundle:Default:informesemanalganar.html.twig');                        
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
        }
}
