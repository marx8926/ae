<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\RedSocial;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\NuevoConvertido;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEGanarBundle:Default:index.html.twig', array('name' => $name));
    }

    //vista de asignar miembro
    public function asignarAction()
    {
        return $this->render('AEGanarBundle:Default:asignar_red.html.twig');
    }
    
    //actualizar asignar miembro
    public function asignarupdateAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');

        $datos = array();

        parse_str($name,$datos);
        
        if($name!=NULL){
            
            $red   = $datos['red_lista'];
            
             $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                if($red != '-1')
                {
                    $celula = $datos['celula_lista'];
                
                    //nuevo convertido
                    $sql = "UPDATE nuevo_convertido SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                
                    //miembro
                    $sql = "UPDATE miembro SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                }
                else{
                
                     $sql = "UPDATE nuevo_convertido SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>NULL,':red'=>NULL, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                
                    //miembro
                    $sql = "UPDATE miembro SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>NULL,':red'=>NULL, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                }
               
                
           
                 $this->getDoctrine()->getEntityManager()->commit();
                 
                 $return=array("responseCode"=>200,  "greeting"=>'OK');

        
            }catch(Exception $e)
            {
               $this->getDoctrine()->getEntityManager()->rollback();
               $this->getDoctrine()->getEntityManager()->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
               
            }
            
        }
        else $return=array("responseCode"=>400, "greeting"=>"Bad");     

        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
      
    }

    public function testAction()
    {
        return $this->render('AEGanarBundle:Default:test.html.twig');
    }
}
