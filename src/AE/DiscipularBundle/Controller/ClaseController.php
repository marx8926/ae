<?php
namespace AE\DiscipularBundle\Controller;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class ClaseController extends Controller {
	
	public function indexAction()
	{
		$request = $this->get('request');
		$idclase = $request->request->get('idclase');
		
		$Clase = $this->getDoctrine()
		->getRepository('AEDataBundle:ClaseCurso')
		->findOneById($idclase);
		
		$Tema =$this->getDoctrine()
		->getRepository('AEDataBundle:TemaCurso')
		->findOneById($Clase->getTema());
		
		$Archivo= $this->getDoctrine()
		->getRepository('AEDataBundle:Archivo')
		->findOneBy(array('idTemaCurso' => $Tema->getId()));
		
		$Asignacion = $Clase->getIdCursoImpartido();
		$Curso = $Asignacion->getIdCurso();	
		$Docente = $Asignacion->getIdPersonaDocente();
		$Persona = $Docente->getIdPersona();
		$nombreDocente = $Persona->getNombre()." ".$Persona->getApellidos();
		$Fecha = "";
		if( $Clase->getFechaDicto()!=null)
			$Fecha = $Clase->getFechaDicto()->format("Y-m-d");
		
		$Ofrenda = "0";
		if( $Clase->getOfrenda()!=null)
			$Ofrenda = $Clase->getOfrenda();
		
		
		return $this->render('AEDiscipularBundle:Default:clase.html.twig',array(
				'idclase' => $idclase ,
				'fechadicto' => $Fecha,
				'docente' => $nombreDocente ,
				'tema' => $Tema->getDescripcion() ,
				'curso' => $Curso->getTitulo(),
				'idasignacion'=>$Asignacion->getId(),
				'urlfile'=> $Archivo->getDireccion(),
				'namefile'=>$Archivo->getNombre(),
				'ofrenda'=>$Ofrenda
		));
	}
	
	public function ListaClasesAction()
	{
		$request = $this->get('request');
		$idasignacion = $request->request->get('datos');
		
		return $this->render('AEDiscipularBundle:Default:listaclases.html.twig',array(
				'idasignacion' => $idasignacion,
		));
	}
	
	public function ActualizarClaseAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		
		$datos = array();
		
		parse_str($form,$datos);
		
		$asistencias = null;
		$notas= null;
		$idestudiante = null;
		$idclase = null;
		
		if($form!=NULL){
				
			$asistencias = $datos['asistencia'];
			$notas = $datos['nota'];
			$idestudiantes = $datos['idestudiante'];
			$idclase = $datos["idclase"];
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->beginTransaction();
			try
			{
				$count = count($idestudiantes);
				for($i = 0; $i<$count;$i++){
				$asistencia=$asistencias[$i];
				$idestudiante = $idestudiantes[$i];
				$nota = $notas[$i];
					
				$sql = "UPDATE asistencia_clase_curso
						   SET nota=".$nota.", asistencia=".$asistencia."
						 WHERE id_persona_estudiante=".$idestudiante." and id_clase_curso=".$idclase;
					
				$smt = $em->getConnection()->prepare($sql);
				$smt->execute();
				}		
			}catch(Exception $e)
			{
				$em->rollback();
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
		
		public function ActualizarFechaClaseAction(){
			
			$request = $this->get('request');
			$form=$request->request->get('formName');
		
			$datos = array();
		
			parse_str($form,$datos);			
			$fecha=null;
			$idclase = null;
			
			if($form!=NULL){
				$idclase = $datos['idclase'];
				$fecha_inicio = explode('/', $datos["fecha"],3);
				$fecha = $fecha_inicio[2]."-".$fecha_inicio[1]."-".$fecha_inicio[0];
				$date = date("Y-m-d", strtotime($fecha));
				$em = $this->getDoctrine()->getEntityManager();
				$this->getDoctrine()->getEntityManager()->beginTransaction();				
				try
				{
					$sql = "UPDATE clase_curso SET fecha_dicto='".$date."' WHERE id=".$idclase;
					$smt = $em->getConnection()->prepare($sql);
					$smt->execute();
				}catch(Exception $e)
				{
					$this->getDoctrine()->getEntityManager()->rollback();
					$this->getDoctrine()->getEntityManager()->close();
					$return=array("responseCode"=>400, "greeting"=>"Bad");
						
					throw $e;
				}
				$this->getDoctrine()->getEntityManager()->commit();
				$return=array("responseCode"=>200, "id"=>$date );
		}
		
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
	}

	
	public function ActualizarOfrendaClaseAction(){
			
		$request = $this->get('request');
		$form=$request->request->get('formName');
	
		$datos = array();
	
		parse_str($form,$datos);
		$ofrenda=null;
		$idclase = null;
			
		if($form!=NULL){
			$idclase = $datos['idclase'];
			$ofrenda = $datos['ofrenda'];
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{
				$sql = "UPDATE clase_curso SET ofrenda=".$ofrenda." WHERE id=".$idclase;
				$smt = $em->getConnection()->prepare($sql);
				$smt->execute();
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
}
