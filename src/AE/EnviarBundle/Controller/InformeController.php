<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;

class InformeController extends Controller
{
    public function celulogramaAction()
    {
        return $this->render('AEEnviarBundle:Default:celulograma.html.twig');
    }
    
    public function celulograma12Action()
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
                
                if($securityContext->isGranted('ROLE_ENVIAR'))
                    $red = NULL;
                    
                return $this->render('AEEnviarBundle:Celulas:celulograma12.html.twig', array('red'=>$red));
            }
           else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
    public function celulograma144Action()
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
            
            
             if($securityContext->isGranted('ROLE_ENVIAR'))
             {
                 $red = NULL;
                 $lider = NULL;
             }
             
             if($securityContext->isGranted('ROLE_LIDER_RED'))
                    $lider = NULL;
              
            return $this->render('AEEnviarBundle:Celulas:celulograma144.html.twig', array('red'=>$red,
                'lider'=>$lider));
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
    public function celulograma1728Action()
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

                if($securityContext->isGranted('ROLE_ENVIAR'))
                {
                    $doce=NULL;
                    $ciento=NULL;
                    $red=NULL;
                }
            }
            
            return $this->render('AEEnviarBundle:Celulas:celulograma1728.html.twig', array('red'=>$red,'doce'=>$doce,
                'ciento'=>$ciento));
            }
            
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    
    public function celulograma20378Action()
    {
         $securityContext = $this->get('security.context');
             
             if($securityContext->isGranted('ROLE_LIDER1728'))
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
                
               
            }
            
            return $this->render('AEEnviarBundle:Celulas:celulograma20378.html.twig', array('red'=>$red));
            }
            
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    
    public function celulogramaUpAction()
    {
         $request = $this->get('request');
        
        $form=$request->request->get('formName');
  
        $datos = array();

        parse_str($form,$datos);   
        
        //$clase = $datos['claseid'];
        
        $em = $this->getDoctrine()->getEntityManager();
        
        //$ofrenda = $datos['ofrenda']; // ofrenda
            
        //$n = $datos['numfilas'];
        
        $ret=array("responseCode"=>200, "greeting"=>$datos);                
        
        $return=json_encode($ret);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type               
    }
    
    public function informe_semanalvistaAction()
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
            
        return $this->render('AEEnviarBundle:Default:informecelulas.html.twig',array('red'=>$red));
    
    }
    
    public function discipulos_por_redAction()
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
                if($securityContext->isGranted('ROLE_ENVIAR'))
                    $red = NULL;
            }
            
        return $this->render('AEEnviarBundle:Mentoreo:discipulosporred.html.twig',array('red'=>$red)); 
       }
       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    public function informe_redAction()
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
            
        return $this->render('AEEnviarBundle:Informes:informexred.html.twig',array('red'=>$red)); 
    }
    
    public function informe_resumidoAction()
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
            
        return $this->render('AEEnviarBundle:Informes:informeresumido.html.twig',array('red'=>$red)); 
    }
    
    public function informe_lider12Action()
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
                
                if($securityContext->isGranted('ROLE_ENVIAR'))
                    $red = NULL;
                    
                return $this->render('AEEnviarBundle:Informes:informe12.html.twig', array('red'=>$red));
            }
           else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
      
    public function informe_lider144Action()
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
            
            
             if($securityContext->isGranted('ROLE_ENVIAR'))
             {
                 $red = NULL;
                 $lider = NULL;
             }
             
             if($securityContext->isGranted('ROLE_LIDER_RED'))
                    $lider = NULL;
              
            return $this->render('AEEnviarBundle:Informes:informe144.html.twig', array('red'=>$red,
                'lider'=>$lider));
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
      
    public function informe_lider1728Action()
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
            
            return $this->render('AEEnviarBundle:Informes:informe1728.html.twig', array('red'=>$red,'doce'=>$doce,
                'ciento'=>$ciento));
            }
            
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
}
