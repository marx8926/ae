<?php
namespace AE\AdministrarBundle\Controller;

use AE\DataBundle\Entity\Evento;

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

class AdministrarEventoController extends Controller{
	
	
	public function indexAction(){
		return $this->render('AEAdministrarBundle:Evento:administrar_evento.html.twig');
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
            
            $em = $this->getDoctrine()->getEntityManager();
            
			$prev_div = $em->getRepository('AEDataBundle:Ubigeo');
			$ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
			
			$date_fecha_inicio = new \DateTime();
			$date_fecha_inicio->setDate($fecha_inicio_Y, $fecha_inicio_m, $fecha_inicio_d);
			
			$date_fecha_fin = new \DateTime();
			$date_fecha_fin->setDate($fecha_fin_Y, $fecha_fin_m, $fecha_fin_d);
				
			$this->getDoctrine()->getEntityManager()->beginTransaction();
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
                
                // Evento
                
               	$Evento =  new Evento();
                $Evento->setNombre($nombre);
                $Evento->setDescripcion($descripcion);
                $Evento->setFechaini($date_fecha_inicio);
                $Evento->setFechafin($date_fecha_fin);
                $Evento->setIdUbicacion($Ubicacion);
                $em->persist($Evento);
                $em->flush();
		
			}catch(Exception $e)
			{
				$this->getDoctrine()->getEntityManager()->rollback();
				$this->getDoctrine()->getEntityManager()->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$this->getDoctrine()->getEntityManager()->commit();
			$return=array("responseCode"=>200, "id"=>$datos );
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
	
	public function EliminarAsignacionAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');		
		$datos = array();		
		parse_str($form,$datos);
		$idAsignacion = null;
		
		if($form!=NULL){						
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();                        
          
			$idAsignacion = intval($datos["id"]);
			$sql = "select delete_curso_impartido(:idx)";
							
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute(array(':idx'=> $idAsignacion));
                          
                         
            $this->getDoctrine()->getEntityManager()->commit();
			$return=array("responseCode"=>200, "datos"=>$idAsignacion);
		}
		else{
                    $this->getDoctrine()->getEntityManager()->rollback();
                    $this->getDoctrine()->getEntityManager()->close();
                    
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
}