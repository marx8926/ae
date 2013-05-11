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

class CelulaController extends Controller
{
    
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
            
            $tipo = $datos['tip_red'];
            $id   = $datos['ids'];
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->beginTransaction();
            
            try {
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
                
                $sql = "select insert_celula(:tip, :fam, :tel , :ubi, :red,:caso,:idx)";
                
                $smt = $em->getConnection()->prepare($sql);
                
                switch (intval($tipo)) {
                    case 0: //lider
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>2,':idx'=>$id));

                        break;
                    case 1: //misionero
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>1,':idx'=>$id));
                        break;
                    
                    case 2:
                      //pastor ejecutivo
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>0,':idx'=>$id));                        
                        break;

                    default:
                        break;
                }
                
              
                
                $em->commit();
                
            } catch (Exception $exc) {
                $em->rollback();
                $em->close();
                throw $exc;
            }



            $return=array("responseCode"=>200, "greeting"=>$datos);  
        }
         else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
}
