<?php

namespace AE\DiscipularBundle\Controller;

use AE\DataBundle\Entity\Horario;

use AE\DataBundle\Entity\CursoImpartido;

use AE\DataBundle\Entity\Archivo;

use Doctrine\Tests\Models\DirectoryTree\File;

use AE\DataBundle\Entity\TemaCurso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AE\DataBundle\Entity\Prerequisito;
use AE\DataBundle\Entity\Curso;
use Doctrine\ORM\TransactionRequiredException;


class AsignarCursoController extends Controller{

	public function indexAction(){
		return $this->render('AEDiscipularBundle:Default:asignarcurso.html.twig');
	}
	
	public function RegistrarAsignacionAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		
		$datos = array();
		
		parse_str($form,$datos);
		$docenteid = null;
		$cursoid = null;
		$localid = null;
		$hora_inicio = null;
		$hora_fin = null;
		$dia = null;
		$fecha_inicio = null;
		
		if($form!=NULL){
			
			$docenteid = $datos["profesor"];
			$cursoid = $datos["curso"];
			$localid = $datos["local"];
			
			$fecha_inicio = $datos["fecha_inicio"];
			$fecha_inicio_Y = date("Y", strtotime($fecha_inicio));
			$fecha_inicio_m = date("m", strtotime($fecha_inicio));
			$fecha_inicio_d = date("d", strtotime($fecha_inicio));
			
			$hora_inicio = $datos["hora_inicio"];
			$hora_inicio_H = date("H", strtotime($hora_inicio));
			$hora_inicio_i = date("i", strtotime($hora_inicio));
			
			$hora_fin=$datos["hora_fin"];
			$hora_fin_H = date("H", strtotime($hora_fin));
			$hora_fin_i = date("i", strtotime($hora_fin));
			
			$dia = $datos["dia"];
			
			$Docente = $this->getDoctrine()
			->getRepository('AEDataBundle:Docente')
			->find($docenteid);
			
			$Curso = $this->getDoctrine()
			->getRepository('AEDataBundle:Curso')
			->find($cursoid);
			
			$Local = $this->getDoctrine()
			->getRepository('AEDataBundle:Local')
			->find($localid);
			
			$date_fecha_inicio = new \DateTime();
			$date_fecha_inicio->setDate($fecha_inicio_Y, $fecha_inicio_m, $fecha_inicio_d);
			
			$date_hora_inicio = new \DateTime();
			$date_hora_inicio->setTime(intval($hora_inicio_H),intval($hora_inicio_i),0);
			$date_hora_fin = new \DateTime();
			$date_hora_fin->setTime(intval($hora_fin_H),intval($hora_fin_i),0);
			
			$em = $this->getDoctrine()->getEntityManager();			
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{                
				$Horario = new Horario();
				$Horario->setDia($dia);
				$Horario->setHoraInicio($date_hora_inicio);
				$Horario->setHoraFin($date_hora_fin);
				
				$em->persist($Horario);
				$em->flush();
				
				$Asignacion = new CursoImpartido();
				$Asignacion->setFechaCreacion(new \DateTime());
				$Asignacion->setFechaInicio($date_fecha_inicio);
				$Asignacion->setActivo(true);
				$Asignacion->setEstadoMatricula(true);
				$Asignacion->setFechaFin(null);
				$Asignacion->setIdCurso($Curso);
				$Asignacion->setIdLocal($Local);
				$Asignacion->setIdPersonaDocente($Docente);
				$Asignacion->setIdHorario($Horario);
				
				$em->persist($Asignacion);
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
			$id = $datos["id"];
			$num = count($id);
			
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();                        
                        
			for($i=0; $i < $num; $i++)
                        {
                            $idAsignacion = $datos["asignacion".$id[$i]];
                            $sql = "select delete_curso_impartido(:idx)";
							
                            $smt = $em->getConnection()->prepare($sql);
                            $smt->execute(array(':idx'=> $idAsignacion));
			}
                         
                         
			$return=array("responseCode"=>200, "id"=>$datos);
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
}