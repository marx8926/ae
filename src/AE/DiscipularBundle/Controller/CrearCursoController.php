<?php
namespace AE\DiscipularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CrearCursoController extends Controller{

	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:crearcurso.html.twig');
	}

	public function RegistrarCursoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formRegCurso');
		
		/*
		$return = array("responseCode"=>200, "greeting"=>$form);
		
		
			
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
	
			if(strpos($form,'titulos')!=false)
			{
				$titulo = $datos['titulos'];
			}
	
			if(strpos($form, 'descripcion')!=false)
			{
				$descripcion = $datos['descripcion'];
			}

			if(strpos($form,'inputFecha')!=false)
			{
				$fecha = $datos['inputFecha'];
			}

			if(strpos($form,'prerequisitos')!=false)
			{
				$fecha = $datos['prerequisitos'];
			}
	
			if(strpos($form, 'numsesiones')!=false)
			{
				$numsesiones = $datos['numsesiones'];
			}
			
			if(strpos($form, 'estado')!=false)
			{
				$estado = $datos['estado'];
			}
	
			$em = $this->getDoctrine()->getEntityManager();
	
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{
				$curso = new Curso();
					
				if($fecha ==NULL)
					$curso->setFechaCreacion(new \DateTime());
				else
					$curso->setFechaCreacion(new \DateTime($fecha));
					
				$curso->setTitulo($titulo);
				$curso->setDescripcion($descripcion);
				$curso->setActivo($estado);
				$curso->setActivo($estado);
				$curso->setNumeroSesiones($numsesiones);
					
				$em->persist($leche);
				$em->flush();
					
				$this->getDoctrine()->getEntityManager()->commit();
				$return=array("responseCode"=>300, "id"=>$leche->getId() );
	
	
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