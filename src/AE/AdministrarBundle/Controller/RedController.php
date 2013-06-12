<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\LecheEspiritual;
use AE\DataBundle\Entity\TemaLeche;
use AE\DataBundle\Entity\Archivo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class RedController extends Controller
{
        public function redAction()
    {
        return $this->render('AEAdministrarBundle:Red:regredes.html.twig');
    }
    public function addredAction()
    {
         $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);

       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $tipo_red = $datos['tipo_red'];
            $id_iglesia = $datos['iglesia_lista'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            $tip_red = $datos['tip_red'];
            $id_persona = $datos['ids'];
            $pastor = $datos['pastor'];

            $em = $this->getDoctrine()->getEntityManager();         
            
            $em->beginTransaction();
            try
            {
                
                //checar si el codigo de la red existe 
                 $sql = 'select * from red where red.id = :red';
            
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':red'=>$nombre));
 
                $redes = $smt->fetchAll(); 
                
                if(count($redes)>0)
                {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     
                     $return=array("responseCode"=>400, "greeting"=>"Bad");     

                     $return=json_encode($return);//jscon encode the array
        
                     return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                }
                
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
                
                //persona 
                $personas = $em->getRepository('AEDataBundle:Persona');
                $persona = $personas->findOneBy(array('id'=>$id_persona));
                
                $pastor_eje = $personas->findOneBy(array('id'=>$pastor));

                //iglesia
                $iglesias = $em->getRepository('AEDataBundle:Iglesia');
                $iglesia = $iglesias->findOneBy(array('id'=>$id_iglesia));

                //Red
                $red = new Red();
                $red->setIdIglesia($iglesia);
                $red->setIdUbicacion($ubicacion);
                $red->setTipo($tipo_red);
                $red->setId($nombre);
                $red->setActivo(TRUE);
                $red->setInicio(new \DateTime());
                
                if($pastor_eje!= NULL)
                {
                    $pastor_em = $em->getRepository('AEDataBundle:PastorEjecutivo');
                    $pastor_final = $pastor_em->findOneBy(array('id'=>$pastor_eje));
                   
                    $red->setPastor ($pastor_final);
                   
                }

                //añadir al lider
                if($id_persona!=-1)
                {
                    //0 para lider
                    //1 para pastor asociado
                    if($tip_red==0)
                    {
                         //lider de red
                        //$lider = $query->getResult();
                        $lider_red = $em->getRepository('AEDataBundle:LiderRed');
                        $lider = $lider_red->findOneBy(array('id'=>$persona));
                                         
                        $sql = 'select * from red where red.id_lider_red = :red';
            
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$id_persona));
 
                        $redes = $smt->fetchAll();  
                       
                        if(count($redes)>0)
                        {
                            $return=array("responseCode"=>400, "greeting"=>"Bad");     
                            
               
                            $return=json_encode($return);//jscon encode the array
                            
                            $em->rollback();
                            $em->close();
        
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                        }
                          if($lider!=NULL)
                            $red->setIdLiderRed($lider);
                    }
                    else
                    {
                        //pastor asociado 
                        $pastor_asoc = $em->getRepository('AEDataBundle:PastorAsociado');
                        $past_asoc   = $pastor_asoc->findOneBy(array('id'=>$persona));
                        
                        
                        $sql = 'select * from red where red.id_pastor_asociado = :red';
            
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$id_persona));
 
                        $redes = $smt->fetchAll();  
                        
                        if(count($redes)>0)
                        {
                            $return=array("responseCode"=>400, "greeting"=>"Bad");     

                            $return=json_encode($return);//jscon encode the array
                            $em->rollback();
                            $em->close();
        
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                        }
                        
                        if($pastor_asoc!=NULL)
                            $red->setIdPastorAsociado($past_asoc);
                    }
                }
                $em->persist($red);
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
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
         
        $em->clear();
        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    }
    
    public function asignaredAction()
    {
        return $this->render('AEAdministrarBundle:Red:asignarlider.html.twig');
        
    }
    
     public function addasignarliderAction()
    {
                return $this->render('AEAdministrarBundle:Red:asignarlider.html.twig');

    }
    
}
