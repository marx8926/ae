<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\Consolida;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEConsolidarBundle:Default:index.html.twig', array('name' => $name));
    }

     
     
     public function vistaAction($id)
     {
         return $this->render('AEConsolidarBundle:Default:vista.html.twig', array('id'=>$id));
     }
     
     public function activar_nuevoAction()
     {
            $request = $this->get('request');
        $name=$request->request->get('formName');
              
        $em = $this->getDoctrine()->getEntityManager();
        
        // $this->getDoctrine()->getEntityManager()->beginTransaction();
        $return = null;
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();
        try{
            
     
        $sql = "select activa_consolida(:id)";
        
         $smt = $em->getConnection()->prepare($sql);
         
         $smt->execute(array(':id'=>$name));
        
         
         
         $return=array("responseCode"=>200, "greeting"=>'Good');

         $this->getDoctrine()->getEntityManager()->commit();
        }
        catch(Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            $return=array("responseCode"=>400, "greeting"=>'Bad');

        }
        
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
     }
     
     public function cambiar_consolidadorAction()
     {
         return $this->render('AEConsolidarBundle:Default:cambiar_consolidador.html.twig');
     }
     
     public function cambiar_consolidador_updateAction()
     {
            $request = $this->get('request');
        $name=$request->request->get('formName');
        $lista = $request->request->get('lista');
        
         $datos = array();

        parse_str($name,$datos);
        
     
        $em = $this->getDoctrine()->getEntityManager();
        
        // $this->getDoctrine()->getEntityManager()->beginTransaction();
        $return = null;
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();
        try{
            
     
        $sql = "select update_consolida_consolidador(:id1,:id2,:idp)";
        
         $smt = $em->getConnection()->prepare($sql);
         
         $smt->execute(array(':id1'=>$lista[0],':id2'=>$datos['select_consolidador'],':idp'=>$lista[6]));
 
         $return=array("responseCode"=>200, "greeting"=>'Good');

         $this->getDoctrine()->getEntityManager()->commit();
        }
        catch(Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            $return=array("responseCode"=>400, "greeting"=>'Bad');

        }
        
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
     }

}
