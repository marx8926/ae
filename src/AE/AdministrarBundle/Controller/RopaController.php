<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Herramienta;
use AE\DataBundle\Entity\Ropa;


class RopaController extends Controller
{
 
    public function vista_ropaAction()
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
         
         if($securityContext->isGranted('ROLE_GANAR')|| $securityContext->isGranted('ROLE_CONSOLIDAR')||
                 $securityContext->isGranted('ROLE_ENVIAR') ||$securityContext->isGranted('ROLE_DISCIPULAR'))
            $red = NULL;
         
        return $this->render('AEAdministrarBundle:Ropa:registroropa.html.twig',array('red'=>$red));
        }
       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
   
    }
    
    public function registro_ropa_upAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
       $em = $this->getDoctrine()->getEntityManager();         

        
       if($name!=NULL){
                   
            $shirt = $datos["shirt"];
     
            $pants = $datos['pants'];

            $polo = $datos['polo'];

            $shoes = $datos['shoes'];
 
            $id = $datos['idpersona'];
            
            $em->beginTransaction();
            try
            {
                $persona = $em->getRepository('AEDataBundle:Persona')->findOneBy(array('id'=>$id));
                
                $miembro = $em->getRepository('AEDataBundle:Miembro')->findOneBy(array('id'=>$persona));
                
                $blusa = new Ropa();
                $blusa->setMiembro($miembro);
                $blusa->setNombre('Camisa/Blusa');
                $blusa->setTalla($shirt);
                $em->persist($blusa);
                $em->flush();
                
                $pantalon = new Ropa();
                $pantalon->setMiembro($miembro);
                $pantalon->setNombre('Pantalon');
                $pantalon->setTalla($pants);
                $em->persist($pantalon);
                $em->flush();
                
                $t_shirt = new Ropa();
                $t_shirt->setMiembro($miembro);
                $t_shirt->setNombre('Polo');
                $t_shirt->setTalla($polo);
                $em->persist($t_shirt);
                $em->flush();
                
                $calzado = new Ropa();
                $calzado->setMiembro($miembro);
                $calzado->setNombre('Calzado');
                $calzado->setTalla($shoes);
                $em->persist($calzado);
                $em->flush();
                
                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
            }
        
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }

        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
    }
    
    public function informeroparedAction()
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
             if($securityContext->isGranted('ROLE_GANAR')|| $securityContext->isGranted('ROLE_CONSOLIDAR')||
                 $securityContext->isGranted('ROLE_ENVIAR') ||$securityContext->isGranted('ROLE_DISCIPULAR'))
                $red = NULL;
             
        return $this->render('AEAdministrarBundle:Ropa:informeropared.html.twig',array('red'=>$red));
         }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

   
    }
    
    public function informeropatipoAction()
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
             if($securityContext->isGranted('ROLE_GANAR')|| $securityContext->isGranted('ROLE_CONSOLIDAR')||
                 $securityContext->isGranted('ROLE_ENVIAR') ||$securityContext->isGranted('ROLE_DISCIPULAR'))
            $red = NULL;
             
            return $this->render('AEAdministrarBundle:Ropa:informeropadoce.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
      public function informeropaAction()
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
            if($securityContext->isGranted('ROLE_GANAR')|| $securityContext->isGranted('ROLE_CONSOLIDAR')||
                 $securityContext->isGranted('ROLE_ENVIAR') ||$securityContext->isGranted('ROLE_DISCIPULAR'))
            $red = NULL;
            
            return $this->render('AEAdministrarBundle:Ropa:informeropa.html.twig',array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
}

