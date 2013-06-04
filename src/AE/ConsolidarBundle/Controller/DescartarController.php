<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Descartado;


class DescartarController extends Controller
{
   public function descartarAction()
     {
         return $this->render('AEConsolidarBundle:Default:descartar.html.twig');
     }
     
     public function descartar_updateAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
              
        $em = $this->getDoctrine()->getEntityManager();
        
        $em->beginTransaction();
        $id= NULL;
        $descripcion = NULL;
        $idP = NULL; //id persona
        
        if($name!=NULL)
        {
        try
        {
             $datos = array();

            parse_str($name,$datos);
            
            $id = $datos['nomb'];
            $descripcion = $datos['motivo'];
            $idP = $datos['pers'];
        
            $descartado = new Descartado();
            $descartado->setFechaDescarte(new \DateTime());
            $descartado->setCometario($descripcion);
           
            $persona = $em->getRepository('AEDataBundle:Persona')->findOneBy(array('id'=>$idP));
            
            $descartado->setId($persona);
            
            $em->persist($descartado);
            $em->flush();
           
            $sql = "select update_consolida(:id)";
            
            $smt = $em->getConnection()->prepare($sql);
                         
            if(!$smt->execute(array(':id'=>$id)))
            {
                $return=array("responseCode"=>400, "greeting"=>"Bad");

                $return=json_encode($return);//jscon encode the array
     
                return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
            }
            
            $em->commit();
            $em->clear();
            
        }catch(Exception $e)
        {
             $em->rollback();
             $em->close();
                
             $return=array("responseCode"=>400, "greeting"=>"Bad");

             throw $e;
        }
        $return=array("responseCode"=>200, "greeting"=>$descripcion);
        }
        else  $return=array("responseCode"=>400, "greeting"=>"Bad");
            
  
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     }
     
     public function lista_descartarAction()
     {
         return $this->render('AEConsolidarBundle:Default:lista_descartados.html.twig');
     }
}


