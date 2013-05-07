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
use AE\DataBundle\Entity\ClaseCell;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ClaseController extends Controller
{
    public function lista_clasesAction()
    {
        return $this->render('AEEnviarBundle:Default:lista_clases.html.twig');
    } 
    
      public function lista_clases_descargaAction()
    {
       $request = $this->get('request');
        
       $val=$request->request->get('formName');
    
       if($val!=NULL){
                   
            $id = $val[0];
             $em = $this->getDoctrine()->getEntityManager();
      
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                $sql = 'select * from  ruta_celula(:ruta) AS ("direccion" TEXT)';
                $smt = $em->getConnection()->prepare($sql);
                
                if(!$smt->execute(array(':ruta'=>$id)))
                {
                  $this->getDoctrine()->getEntityManager()->rollback();
                  $this->getDoctrine()->getEntityManager()->close();
                  $return=array("responseCode"=>400, "greeting"=>"Bad");  
                  
                }
                else
                {                   
                    $dato = $smt->fetch();
                    $this->getDoctrine()->getEntityManager()->commit();
  
                    $return=array("responseCode"=>200,  "greeting"=>$dato['direccion']);
                }
          
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
