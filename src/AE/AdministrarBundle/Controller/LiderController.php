<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;


class LiderController extends Controller
{
      public function adminlider12Action()
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
            
        return $this->render('AEAdministrarBundle:Lider:docered.html.twig',array('red'=>$red));

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
            
        return $this->render('AEAdministrarBundle:Lider:ciento44red.html.twig',array('red'=>$red));

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
            
        return $this->render('AEAdministrarBundle:Lider:milred.html.twig',array('red'=>$red));
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


}
