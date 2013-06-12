<?php
namespace AE\AdministrarBundle\Controller;

use AE\DataBundle\Entity\EventoRealizado;

use AE\DataBundle\Entity\Evento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\TemaLeche;
use AE\DataBundle\Entity\Archivo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdministrarEventoController extends Controller{
	
	
	public function indexAction(){
		return $this->render('AEAdministrarBundle:Evento:administrar_evento.html.twig');
	}
	
	public function VerEventosAction(){
		return $this->render('AEAdministrarBundle:Evento:ver_eventos.html.twig');
	}
	
	public function AsistenciaEventoAction(){
	{
		$request = $this->get('request');
		$idEvento = $request->request->get('id');				
		return $this->render('AEAdministrarBundle:Evento:asistencia_evento.html.twig',array(
            'idEvento' => $idEvento
        ));
	}
	}
	
	public function RegistrarEventoAction(){
            
            $request = $this->get('request');
		$form=$request->request->get('formName');
              		
		$datos = array();
		
		parse_str($form,$datos);
		
		$nombre = null;
		$descripcion = null;
		$fecha_inicio = null;
		$fecha_fin = null;
		$referencia = null;
		$direccion = null;
		$departamento = null;
		$provincia = null;
		$distrito = null;
		$latitud = null;
		$longitud = null;
                $tipo = null;
                
                $em = $this->getDoctrine()->getEntityManager();

                
                if($form!=NULL){
                    
			$nombre = $datos["inputNombre"];
			$descripcion = $datos["inputDescripcion"];
			
			$fecha_i = explode('/', $datos["fecha_inicio"],3);
			$fecha_inicio = $fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0]; 
			
			$fecha_f = explode('/', $datos["fecha_fin"],3);
			$fecha_fin = $fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
			
			$direccion = $datos['inputDireccion'];
                        $referencia = $datos['inputReferencia'];
			
			$departamento = $datos['departamento_lista'];
                        $provincia = $datos['provincia_lista'];
                        $distrito = $datos['distrito_lista'];
                        $latitud = $datos['latitud'];
                        $longitud = $datos['longitud'];
                        $tipo = $datos['tipo'];
                        
                        
                        
                        $em->beginTransaction();

                        try {
                            $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                            $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
                            //$em->clear();
                            
                            //ubicacion
                             $ubicacion = new Ubicacion();
                             $ubicacion->setDireccion($direccion);
                             $ubicacion->setReferencia($referencia);
                             $ubicacion->setLatitud($latitud);
                             $ubicacion->setLongitud($longitud);
                             $ubicacion->setIdUbigeo($ubigeo);
                            
                             $em->persist($ubicacion);
                             $em->flush();
                             
                             
                             //evento 
                             
                            /*
                             $evento = new Evento();
                
                            $evento->setNombre($nombre);
                            $evento->setDescripcion($descripcion);
                            $evento->setTipo($tipo);
                            $evento->setIdUbicacion($ubicacion);
                
                            $inicio = new \DateTime($fecha_inicio);
                            $fin = new \DateTime($fecha_fin);
                
                            $evento->setFechaini($inicio);
                            $evento->setFechafin($fin);
                            
                            $em->persist($evento);
                            $em->flush();
                            */
                             
                            
                             $inicio = new \DateTime($fecha_inicio);
                            $fin = new \DateTime($fecha_fin);
                
                              $sql = "INSERT INTO evento(
                                    nombre, descripcion, id_ubicacion, tipo, fecha_ini, fecha_fin)
                                    VALUES (:nombre,:desc, :ubi, :tipo, :ini,:fin)";
                            
                              $smt = $em->getConnection()->prepare($sql);
                              
                              $smt->execute(array(':nombre'=>$nombre,':desc'=>$descripcion,
                                  ':ubi'=>$ubicacion->getId(),':tipo'=>$tipo ,
                                  ':ini'=>$inicio->format('Y-m-d'), ':fin'=>$fin->format('Y-m-d')));
                              
                            // $em->clear();
                            
                            $em->commit();
                        } catch (Exception $exc) {
                            $em->rollback();
                            $em->close();
                            throw $exc;
                        }



                        $return = array("responseCode"=>200, "greeting"=>$datos);            
                }
                else
                {
                        $return = array("responseCode"=>400, "greeting"=>'Bad');

                }
		
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
	
	public function EliminarEventoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');		
		$datos = array();		
		parse_str($form,$datos);
		$idEvento = null;
		$em = $this->getDoctrine()->getEntityManager();

		if($form!=NULL){						
			$em->beginTransaction();                        
          
			$idEvento =$datos["id"];
			$sql = "delete FROM evento where id =".$idEvento;
							
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
                          
                         
                        $em->commit();
			$return=array("responseCode"=>200, "datos"=>$datos);
                        $em->clear();
		}
		else{
                    $em->rollback();
                    $em->clear();
                    $em->close();
                    
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
	
	public function RegistrarPersonaEventoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
		parse_str($form,$datos);
		
		$idpersona = null;
		$idevento = null;
		$em = $this->getDoctrine()->getEntityManager();

		if($form!=NULL){
			$idpersona = $datos['id'];
			$idevento = $datos['idevento'];
		
			$em->beginTransaction();
			
			$sql = "INSERT INTO evento_realizado(
					id_persona, id_evento)
			VALUES (".$idpersona.", ".$idevento.")";
			
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
			
			$em->commit();
			$return=array("responseCode"=>200, "id"=>$datos );
                        
                        $em->clear();
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		}
}