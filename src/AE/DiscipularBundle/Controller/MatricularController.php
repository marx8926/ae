<?php
namespace AE\DiscipularBundle\Controller;

use AE\DataBundle\Entity\Matric;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class MatricularController extends Controller {
	public function indexAction()
	{
		$request = $this->get('request');
		$idCurso = $request->request->get('datos');				
		return $this->render('AEDiscipularBundle:Default:matricular.html.twig',array(
            'idCurso' => $idCurso
        ));
	}
	
	public function MatricularEstudianteAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
		
		parse_str($form,$datos);
		
		$idestudiante = NULL;
		$idasignacion = NULL;
                $retorno = NULL;
		
		if($form!=NULL){
		
			$idestudiante = $datos['id'];
			$idasignacion = $datos['idasignacion'];
			
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{
                            
				$Estudiante = $this->getDoctrine()
				->getRepository('AEDataBundle:Estudiante')
				->findOneById($idestudiante);
                                
                               
				
				$Asignacion = $this->getDoctrine()
				->getRepository('AEDataBundle:CursoImpartido')
				->findOneById($idasignacion);
		                                
                                $sql = "select insert_matricula(:fecha,:persona,:curso)";
                            
                                $smt = $em->getConnection()->prepare($sql);
                                
                                $fecha = new \DateTime();
                            
                                $smt->execute(array(':fecha'=>$fecha->format('d-m-Y'), ':persona'=>$Estudiante->getId()->getId(),':curso'=>$Asignacion->getId()));
				$Estudiante->setActivo(true);
				$em->flush();
		
			}catch(Exception $e)
			{
				$this->getDoctrine()->getEntityManager()->rollback();
				$this->getDoctrine()->getEntityManager()->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$this->getDoctrine()->getEntityManager()->commit();
			$return=array("responseCode"=>200, "datos"=>$datos );
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));
	}
	
	public function EliminarMatriculaAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
	
		parse_str($form,$datos);
		$idmatric = NULL;
	
		if($form!=NULL){
			
			$idmatric = $datos['id'];
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			
		try
			{			
				$sql = "Select id_persona_estudiante from matric WHERE id=".$idmatric;
				
				$smt = $em->getConnection()->prepare($sql);
				$smt->execute();
				
				$todo = $smt->fetchAll();
				$idestudiante = $todo[0]['id_persona_estudiante'];
				
				$sql = "DELETE FROM matric WHERE id=".$idmatric;
				
				$smt = $em->getConnection()->prepare($sql);
				$smt->execute();
				
				$todo = $smt->fetchAll();
				
				$Estudiante = $this->getDoctrine()
				->getRepository('AEDataBundle:Estudiante')
				->findOneById($idestudiante);
				
				$Estudiante->setActivo(false);
				$em->flush();
				
			}catch(Exception $e)
			{
				$this->getDoctrine()->getEntityManager()->rollback();
				$this->getDoctrine()->getEntityManager()->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$this->getDoctrine()->getEntityManager()->commit();
			$return=array("responseCode"=>200, "datos"=>$datos );
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));
	}

}
