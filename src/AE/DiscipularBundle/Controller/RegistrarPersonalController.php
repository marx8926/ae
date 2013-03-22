<?php
namespace AE\DiscipularBundle\Controller;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class RegistrarPersonalController extends Controller {
	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:registrarpersonal.html.twig');
	}
	
	public function RegistroPersonalAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
		
		parse_str($form,$datos);
		
		$idpersona = NULL;
		$descripcion = NULL;
		$fechas = null;
	
		if($form!=NULL){
			
			$idpersona = $datos['id'];
			$descripcion = $datos['descripcion'];
	
			$em = $this->getDoctrine()->getEntityManager();	
			$this->getDoctrine()->getEntityManager()->beginTransaction();
			
			try
			{
				$per = $em->getRepository('AEDataBundle:Persona');
				$persona = $per->findOneBy(array('id'=>$idpersona));
				
				$docente = new Docente();
				$docente->setFechaInicio(new \DateTime());
				$docente->setActivo(true);
				$docente->setDescripcion($descripcion);
				$docente->setIdPersona($persona);
				$em->persist($docente);
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
	
	public function EliminarPersonalAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
		
		parse_str($form,$datos);
		$idDocente = NULL;
		
		if($form!=NULL){
			$idDocente = $datos['id'];
			
			$em = $this->getDoctrine()->getEntityManager();
			
			$sql = "DELETE FROM docente WHERE id_persona=".$idDocente;
			
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
			
			$todo = $smt->fetchAll();
			
			$return=array("responseCode"=>200, "id"=>$datos );
			/*try
			{
				$per = $em->getRepository('AEDataBundle:Docente');
				$Docente = $per->findOneBy(array('id'=>$idDocente));
				$em->remove($Docente);
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
			}*/
				
			$return=json_encode($return);//jscon encode the array
			
			return new Response($return,200,array('Content-Type'=>'application/json'));
		}
		}
			
}
