<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\TemaCelula;
use AE\DataBundle\Entity\Archivo;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;

class TemasController extends Controller
{
    public function temacelulaAction()
    {
        
          $securityContext = $this->get('security.context');
       
       if($securityContext->isGranted('ROLE_ENVIAR'))
        return $this->render('AEEnviarBundle:Default:tema_celula.html.twig');
       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
        
    public function temacelula_updatedAction()
    {
        $request = $this->get('request');
        
        $name=$request->request->get('formName');

        $datos = array();

        parse_str($name,$datos);
        
        $titulo = NULL;
        $author = NULL;
        $descripcion = NULL;
        $file = NULL;
        $tipo = NULL;

       if($name!=NULL){
                   
            $titulo = $datos['titulo'];
            $author = $datos['author'];
            $descripcion = $datos['descripcion'];
            $file = $datos['filename0'];
            $tipo = $datos['tipo'];
       
            $em = $this->getDoctrine()->getEntityManager();         
      
            $em->beginTransaction();
            try
            {
                $celula = new TemaCelula();
                $celula->setAutor($author);
                $celula->setDescripcion($descripcion);
                $celula->setTitutlo($titulo);
                $celula->setTipo($tipo);
                $celula->setFecha(new \DateTime());
                
                $em->persist($celula);
                $em->flush();
                
		$uploadFileName = date("Y-m-d-").$file;
		$url = "uploads/".$uploadFileName;
                       
                $archivo = new Archivo();
                $archivo->setNombre($uploadFileName);
                $archivo->setFecha(new \DateTime());
                $archivo->setDireccion($url);
                $archivo->setIdTemaCelula($celula);
                
                $em->persist($archivo);
                $em->flush();
                
                $em->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');   
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }

}
