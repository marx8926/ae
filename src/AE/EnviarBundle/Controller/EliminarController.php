<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Celula;
use AE\DataBundle\Entity\TemaCelula;
use AE\DataBundle\Entity\Archivo;
use AE\DataBundle\Entity\Horario;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class EliminarController extends Controller
{
      public function eliminarCelulaAction()
    {
        $request    = $this->get('request');
        
        $name     = $request->request->get('formName');
        
        $em = $this->getDoctrine()->getEntityManager();
        $return = NULL;
        
        $datos = array();

        parse_str($name,$datos);
        
        $celula = $datos['idcelula'];
        try
        {
            $em->beginTransaction();
            $sql = "select delete_celula(:idx)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$celula));
            
            $em->commit();
            $return=array("responseCode"=>200, "greeting"=>$celula);  

        }
        catch(Exception $e)
        {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
            $em->rollback();
            $em->close();
            
            throw $e;
        }
       // return $this->render('AEEnviarBundle:Default:busqueda_celula.html.twig');
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type     
  
    }
    
   
   
}
