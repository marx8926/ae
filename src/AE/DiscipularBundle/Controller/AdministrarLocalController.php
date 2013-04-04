<?php
namespace AE\DiscipularBundle\Controller;

use AE\DataBundle\Entity\Local;

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


class AdministrarLocalController extends Controller{

	public function indexAction(){
		return $this->render('AEDiscipularBundle:Default:administrarlocal.html.twig');
	}
	public function RegistrarLocalAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		
		$datos = array();
		
		parse_str($form,$datos);
		$nombre = null;
		$tipo = null;
		$codigo = null;
		
		if($form!=NULL){
				
			$codigo = $datos["codigo"];
			$nombre = $datos["nombre"];
			$tipo = $datos["tipo"];
						
			$em = $this->getDoctrine()->getEntityManager();	
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			try
			{
				$Local = new Local();
				$Local->setCodigo($codigo);
				$Local->setNombe($nombre);
				$Local->setTipo($tipo);		
				$em->persist($Local);
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
			
		$return=json_encode($return);
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
	}
}
