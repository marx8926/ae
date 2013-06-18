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

class CelulaController extends Controller
{
    
    public function celulaAction()
    {
        
        $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_LIDER_RED'))
        {
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
            
                if(count($req)>0)
                    $red = $req['red'];
            
                if($securityContext->isGranted('ROLE_ENVIAR'))
                    $red = NULL;
            }

            return $this->render('AEEnviarBundle:Default:regcelula.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

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
            if($id_red!='-1')
                $id   = $datos['ids'];
            else {
                $id_red=NULL;
                $id;
            }
            $dia  = $datos['dia'];
            $hora = $datos['hora'];
            $creacion_b = $datos['creacion'];
            $creacion_a =explode('/', $creacion_b,3);
            $creacion = $creacion_a[2].'-'.$creacion_a[1].'-'.$creacion_a[0];
            
            
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
                
                $sql = "select insert_celula(:tip, :fam, :tel , :ubi, :red,:caso,:idx,:dia, :hora, :creacion)";
                
                $smt = $em->getConnection()->prepare($sql);
                
                switch (intval($tipo)) {
                    case 0: //lider
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>2,':idx'=>$id,
                            ':dia'=>$dia ,':hora'=>$hora,':creacion'=>$creacion));

                        break;
                    case 1: //misionero
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>1,':idx'=>$id,
                            ':dia'=>$dia ,':hora'=>$hora,':creacion'=>$creacion));
                        break;
                    
                    case 2:
                      //pastor ejecutivo
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>0,':idx'=>$id,
                            ':dia'=>$dia ,':hora'=>$hora,':creacion'=>$creacion));                        
                        break;
                    
                    case 3:
                      //lider
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>3,':idx'=>$id,
                            ':dia'=>$dia ,':hora'=>$hora,':creacion'=>$creacion));                        
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
