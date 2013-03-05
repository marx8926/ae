<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Celula;

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
        return $this->render('AEEnviarBundle:Default:index.html.twig', array('name' => $name));
    }
    public function celulaAction()
    {
        return $this->render('AEEnviarBundle:Default:regcelula.html.twig');

    }
    public function addcelulaAction()
    {
          $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);

       if($name!=NULL){
                   
            $familia = $datos['inputFam'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $telefono = $datos['inputTel'];
            $tipocell = $datos['tipo_cell'];
            //$id_iglesia = $datos['iglesia_lista'];
            $id_red = $datos['red_lista'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            
            
            
            
            $em = $this->getDoctrine()->getEntityManager();         
            
            //   $em->persist($user);
            //$em->flush();
            
             
           
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //ubigeo
               $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
             
                
                  //ubicacion
                $ubicacion = new Ubicacion();
                $ubicacion->setDireccion($direccion);
                $ubicacion->setReferencia($referencia);
                $ubicacion->setLatitud($latitud);
                $ubicacion->setLongitud($longitud);
                $ubicacion->setIdUbigeo($ubigeo);
                
                
                $em->persist($ubicacion);
                $em->flush();
                
                
                //iglesia
               // $iglesias = $em->getRepository('AEDataBundle:Iglesia');
                //$iglesia = $iglesias->findOneBy(array('id'=>$id_iglesia));
                
                
                //Red
                $redes = $em->getRepository('AEDataBundle:Red');
                $red = $redes->findOneBy(array('id'=>$id_red));
                
                
                //celula
                $celula = new Celula();
                $celula->setFamilia($familia);
                $celula->setIdRed($red);
                $celula->setIdUbicacion($ubicacion);
                $celula->setTelefono($telefono);
                $celula->setTipo($tipocell);
                $celula->setActivo(TRUE);
                $celula->setFechaCreacion(new \DateTime());
                
                $em->persist($celula);
                $em->flush();
                
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
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
}
