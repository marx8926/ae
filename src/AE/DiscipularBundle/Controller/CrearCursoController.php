<?php
namespace AE\DiscipularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AE\DataBundle\Entity\Curso;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Prerequisito;

class CrearCursoController extends Controller{

	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:crearcurso.html.twig');
	}

	public function RegistrarCursoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		
		/*
		
		$datos = array();
		
		parse_str($form,$datos);
		
		$return=json_encode($return);//jscon encode the array
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
                */
		$datos = array();
	
		parse_str($form,$datos);
		
		$titulo = NULL;
		$descripcion = NULL;
		$numsesiones = NULL;
		$prerequisitos = NULL;
		$estado = NULL;
		$fecha = NULL;
	
		if($form!=NULL){
				$titulo = $datos['titulo'];
				$descripcion = $datos['descripcion'];
				$numsesiones = $datos['numsesiones'];
				if(strcmp($datos['estado'],"true")==0)
					$estado = true;
				else
					$estado = false;

	
			$em = $this->getDoctrine()->getEntityManager();
	
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{
				$curso = new Curso();
					
				//if($fecha ==NULL)
					//$curso->setFechaCreacion(new \DateTime());
				//else
					$curso->setFechaCreacion(new \DateTime($fecha));
					
				$curso->setTitulo($titulo);
				$curso->setDescripcion($descripcion);
				$curso->setActivo($estado);
				$curso->setNumeroSesiones($numsesiones);
					
				$em->persist($curso);
				$em->flush();
				
				$num = count($datos);
				for($i=0;$i<$num;i++){
					$this->getDoctrine()->getEntityManager()->commit();
				}
				
				$pre = new Prerequisito();
				
				$return=array("responseCode"=>300, "id"=>$curso->getId() );
				
				
	
	
			}catch(Exception $e)
			{
				$this->getDoctrine()->getEntityManager()->rollback();
				$this->getDoctrine()->getEntityManager()->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
		}
		else
		{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
	
		}
			
		$return=json_encode($return);//jscon encode the array
	
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
	}
}