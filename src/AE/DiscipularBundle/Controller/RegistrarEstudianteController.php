<?php
namespace AE\DiscipularBundle\Controller;

use AE\DataBundle\Entity\Estudiante;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class RegistrarEstudianteController extends Controller {
	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:convertirestudiante.html.twig');
	}
	
	public function RegistrarEstudianteAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
	
		parse_str($form,$datos);
	
		$idpersona = NULL;
	
		if($form!=NULL){
				
			$idpersona = $datos['id'];
			$em = $this->getDoctrine()->getEntityManager();
			$this->getDoctrine()->getEntityManager()->beginTransaction();
				
			try
			{
				$Persona = $this->getDoctrine()
				->getRepository('AEDataBundle:Persona')
				->find($idpersona);
	
				$Estudiante = new Estudiante();
				$Estudiante->setFechaInicio(new \DateTime());
				$Estudiante->setActivo(true);
				$Estudiante->setId($Persona);
				$em->persist($Estudiante);
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
}