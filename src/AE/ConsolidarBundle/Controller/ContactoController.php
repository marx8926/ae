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


class ContactoController extends Controller
{
    public function contactoAction()
    {
        return $this->render('AEConsolidarBundle:Default:contacto.html.twig');
    }

    public function contactoherramientaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
        $em = $this->getDoctrine()->getEntityManager();         

        
        if($name!=NULL){
                 
            $consolida = $datos['consolida'];
            $tool = $datos['tools'];
            $dia = $datos['dia'];
            $hora = $datos['hora'];
            

            $em->beginTransaction();
            try
            {
                $sql = "select update_consolida_herramienta(:tool,:consol,:tiempo)";
                $smt = $em->getConnection()->prepare($sql);
                
                $smt->execute(array(':tool'=>$tool,':consol'=>$consolida,':tiempo'=>$dia.' '.$hora));
                
                $em->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
            }
        
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }
        $return=array("responseCode"=>200,  "greeting"=>'ook');

        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
}

