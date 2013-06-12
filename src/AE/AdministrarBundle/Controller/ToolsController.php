<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Herramienta;


class ToolsController extends Controller
{
    public function registrarAction()
    {
        return $this->render('AEAdministrarBundle:Tools:registrar.html.twig');
    }
    
      public function registrar_upAction()
    {
                
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
       $em = $this->getDoctrine()->getEntityManager();         

        
       if($name!=NULL){
                   
            $nombre = $datos['inputNombres'];
            $dias = $datos['dias'];
            $horas = $datos['horas'];
            

            $em->beginTransaction();
            try
            {
                $tool = new Herramienta();
                $tool->setNombre($nombre);
                $tool->setTiempoOptimo($dias.' '.$horas);
                $em->persist($tool);
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
    
    public function busquedaAction()
    {
        return $this->render('AEAdministrarBundle:Tools:busqueda.html.twig');
    }
        
    public function eliminarAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        try
        {
            $em->beginTransaction();
            
            $sql = "select delete_herramienta(:id)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
            
            $em->commit();
            $em->clear();
           $return=array("responseCode"=>200,  "greeting"=>'OK');

        }
        catch(Exception $e)
        {
            $return=array("responseCode"=>400,  "greeting"=>'OK');

            $em->rollback();
            $em->clear();
            $em->close();
            
            throw $e;
        }
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
    }
    
    public function modificarAction()
    {
         $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
       $em = $this->getDoctrine()->getEntityManager();         

        
       if($name!=NULL){
                   
            $nombre = $datos['inputNombres'];
            $dias = $datos['dias'];
            $horas = $datos['horas'];
            $id = $datos['toolid'];
            

            $em->beginTransaction();
            try
            {
                $tool = $em->getRepository('AEDataBundle:Herramienta')->findOneBy(array('id'=>$id));
                $em->clear();
                
                if($tool!=NULL)
                {
                    $tool->setNombre($nombre);
                    $tool->setTiempoOptimo($dias.' '.$horas);
                    $em->persist($tool);
                    $em->flush();
                    $em->commit();
                     $return=array("responseCode"=>200,  "greeting"=>'OK');
                }
                else                       $return=array("responseCode"=>400, "greeting"=>"Bad");

                
               
                
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

}

