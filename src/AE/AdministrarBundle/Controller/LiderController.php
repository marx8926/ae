<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\AsistenciaCulto;


class LiderController extends Controller
{
      public function adminlider12Action()
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
                
                if($securityContext->isGranted('ROLE_PASTOR_EJECUTIVO'))
                    $red =null;
            }
            
            return $this->render('AEAdministrarBundle:Lider:docered.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    public function adminlider12upAction()
    {
                   
        $request = $this->get('request');
        $name=$request->request->get('formName');
      
        $datos = array();

        parse_str($name,$datos);
       
       $em = $this->getDoctrine()->getEntityManager();         

       if($name!=NULL){
                
           

            $em->beginTransaction();
            try
            {
                $id = $datos['personaid'];
                
                $sql = "UPDATE lider SET  tipo=1 WHERE id=:idx";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$id));
                
                $sql1 = "select insert_user_rol(:persona,:rol)";
                $smt1 = $em->getConnection()->prepare($sql1);
                $smt1->execute(array(':persona'=>$id,':rol'=>'ROLE_LIDER12'));
                
               
                $em->commit();
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
        
    }

    
    public function adminlider144Action()
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
             if($securityContext->isGranted('ROLE_PASTOR_EJECUTIVO'))
                    $red =null;
             
            return $this->render('AEAdministrarBundle:Lider:ciento44red.html.twig',array('red'=>$red));

          }
          else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }

    
    public function adminlider144upAction()
    {
       $request = $this->get('request');
       $name=$request->request->get('formName');
      
       $datos = array();

       parse_str($name,$datos);
       
       $em = $this->getDoctrine()->getEntityManager();         

       if($name!=NULL){
   
            $em->beginTransaction();
            try
            {
                $id = $datos['personaid'];
                $padre = $datos['padre'];
                
                $sql = "UPDATE lider SET  tipo=12, padre=:pad WHERE id=:idx";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$id,':pad'=>$padre));
               
                 $sql1 = "select insert_user_rol(:persona,:rol)";
                $smt1 = $em->getConnection()->prepare($sql1);
                $smt1->execute(array(':persona'=>$id,':rol'=>'ROLE_LIDER144'));
               
                $em->commit();
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }

    public function adminlider1728Action()
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
                
                 if($securityContext->isGranted('ROLE_PASTOR_EJECUTIVO'))
                    $red =null;
            }
            
            return $this->render('AEAdministrarBundle:Lider:milred.html.twig',array('red'=>$red));
          }
          else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
      
    public function adminlider1728upAction()
    {
       $request = $this->get('request');
       $name=$request->request->get('formName');
      
       $datos = array();

       parse_str($name,$datos);
       
       $em = $this->getDoctrine()->getEntityManager();         

       if($name!=NULL){
   
            $em->beginTransaction();
            try
            {
                $id = $datos['personaid'];
                $padre = $datos['padre'];
                $abuelo = $datos['abuelo'];
                
                $sql = "UPDATE lider SET  tipo=144, padre=:pad WHERE id=:idx";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$id,':pad'=>$padre));
                
                 $sql1 = "select insert_user_rol(:persona,:rol)";
                $smt1 = $em->getConnection()->prepare($sql1);
                $smt1->execute(array(':persona'=>$id,':rol'=>'ROLE_LIDER1728'));
               
                $em->commit();
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }

    public function asistencia_cultoAction()
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
                
                if($securityContext->isGranted('ROLE_PASTOR_EJECUTIVO'))
                    $red =null;
            }
            
            return $this->render('AEAdministrarBundle:otro:asistenciaculto.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

         
    }
    
    public function asistencia_culto_upAction()
    {
                         
        $request = $this->get('request');
        $name=$request->request->get('formName');
      
        $datos = array();

        parse_str($name,$datos);
       
       $em = $this->getDoctrine()->getEntityManager();  
       $red   = $datos['red_lista'];


       if($name!=NULL && $red!='-1'){
                
            $fecha_b = $datos['fecha'];
            $fecha_a =explode('/', $fecha_b,3);
            $fecha = $fecha_a[2].'-'.$fecha_a[1].'-'.$fecha_a[0];
            
            $asistencia = $datos['asistencia'];
            
            

            $em->beginTransaction();
            try
            {
                $red_q = $em->getRepository('AEDataBundle:Red');
                $redi = $red_q->findOneBy(array('id'=>$red));
                
                $asiste = new AsistenciaCulto();
                $asiste->setAsistentes($asistencia);
                $asiste->setCulto(new \DateTime($fecha));
                $asiste->setIdRed($redi);
                
                $em->persist($asiste);
                $em->flush();
                
                $em->commit();
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
        
    }

    
    public function asistencia_listaAction()
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
                if($securityContext->isGranted('ROLE_PASTOR_ASOCIADO'))
                    $red = NULL;
            }
            
            return $this->render('AEAdministrarBundle:otro:asistencialista.html.twig',array('red'=>$red));
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
}
