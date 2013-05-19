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
                
                $em = $this->getDoctrine()->getEntityManager();

		
		if($form!=NULL){
			
			$nombre = $datos["inputNombre"];
			$descripcion = $datos["inputDescripcion"];
			
			$fecha_inicio = $datos["fecha_inicio"];
			$fecha_inicio_Y = date("Y", strtotime($fecha_inicio));
			$fecha_inicio_m = date("m", strtotime($fecha_inicio));
			$fecha_inicio_d = date("d", strtotime($fecha_inicio));
			
			$fecha_fin = $datos["fecha_inicio"];
			$fecha_fin_Y = date("Y", strtotime($fecha_fin));
			$fecha_fin_m = date("m", strtotime($fecha_fin));
			$fecha_fin_d = date("d", strtotime($fecha_fin));
			
			$direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
			
			$departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            
            
			$prev_div = $em->getRepository('AEDataBundle:Ubigeo');
			$ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
                        $em->clear();
			
			$date_fecha_inicio = new \DateTime();
			$date_fecha_inicio->setDate($fecha_inicio_Y, $fecha_inicio_m, $fecha_inicio_d);
			
			$date_fecha_fin = new \DateTime();
			$date_fecha_fin->setDate($fecha_fin_Y, $fecha_fin_m, $fecha_fin_d);
				
			$em->beginTransaction();
			try
			{                
				  //ubicacion
                $Ubicacion = new Ubicacion();
                $Ubicacion->setDireccion($direccion);
                $Ubicacion->setReferencia($referencia);
                $Ubicacion->setLatitud($latitud);
                $Ubicacion->setLongitud($longitud);
               	$Ubicacion->setIdUbigeo($ubigeo);
                            
                $em->persist($Ubicacion);
                $em->flush();
                $em->clear();
                
                $sql = "INSERT INTO evento(nombre, descripcion, \"fechaIni\", \"fechaFin\", id_ubicacion)
    					VALUES ('".$nombre."', '".$descripcion."','".date("Y-m-d", strtotime($fecha_inicio))."', '".date("Y-m-d", strtotime($fecha_fin))."', ".$Ubicacion->getId().");";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute();
                
                        $em->clear();
		
			}catch(Exception $e)
			{
				$em->rollback();
                                $em->clear();
				$em->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$em->commit();
			$return=array("responseCode"=>200, "id"=>$datos );
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
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