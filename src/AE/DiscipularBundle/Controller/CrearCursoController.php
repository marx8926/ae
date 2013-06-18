<?php
namespace AE\DiscipularBundle\Controller;

use AE\DataBundle\Entity\Archivo;

use Doctrine\Tests\Models\DirectoryTree\File;

use AE\DataBundle\Entity\TemaCurso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AE\DataBundle\Entity\Prerequisito;
use AE\DataBundle\Entity\Curso;
use Doctrine\ORM\TransactionRequiredException;


class CrearCursoController extends Controller{

	public function indexAction()
	{
            
             $securityContext = $this->get('security.context');
        
            if($securityContext->isGranted('ROLE_DISCIPULAR'))
            {
		return $this->render('AEDiscipularBundle:Default:crearcurso.html.twig');
            }
            else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

        }

	public function RegistrarCursoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		
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
			
			if(strpos($form, 'prerequisitos')!==false)
				$prerequisitos = $datos["prerequisitos"];
			
			if(strcmp($datos['estado'],"true")==0)
				$estado = true;
			else
				$estado = false;
	
			$em = $this->getDoctrine()->getEntityManager();	
			$em->beginTransaction();
			try
			{
				$curso = new Curso();
					
				//if($fecha ==NULL)
					//$curso->setFechaCreacion(new \DateTime());
				//else
				$curso->setFechaCreacion(new \DateTime());
				$curso->setTitulo($titulo);
				$curso->setDescripcion($descripcion);
				$curso->setActivo($estado);
				$curso->setNumeroSesiones($numsesiones);
					
				$em->persist($curso);
				$em->flush();
				$num = count($prerequisitos);
				for($i=0;$i<$num;$i++){
						$pre = new Prerequisito();
						$pre->setIdCurso1($curso);
						$pre->setIdCurso2($prerequisitos[$i]);
						$em->persist($pre);
						$em->flush();
				}
				
				for($j=0;$j<$numsesiones;$j++){
					$Tema = new TemaCurso();
					$Tema->setActivo(true);
					$Tema->setDescripcion($datos['detalle'.$j]);
					$Tema->setFechaCreacion(new \DateTime());
					$Tema->setTipo($datos['tipo'.$j]);
					$Tema->setIdCurso($curso);
					$em->persist($Tema);
					$em->flush();
					
					$File = new Archivo();
					
					$FileName = $datos['filename'.$j];
					$uploadFileName = date("Y-m-d")."-".$FileName;
					$url = "uploads/".$uploadFileName;
					
					$File->setNombre($uploadFileName);
					$File->setIdTemaCurso($Tema);
					$File->setFecha(new \DateTime());
					$File->setDireccion($url);				
					$em->persist($File);
					$em->flush();
			}
			
				
			}catch(Exception $e)
			{
				$em->rollback();
				$em->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$em->commit();
                        $em->clear();
                        
			$return=array("responseCode"=>200, "id"=>$datos );
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);//jscon encode the array
	
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
	}
	
	public function DesactivarCursoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
		
		parse_str($form,$datos);
		$idCurso = NULL;
		
		if($form!=NULL){
			$idCurso = $datos['id'];
			$em = $this->getDoctrine()->getEntityManager();
			
			$sql = "UPDATE curso SET activo = false where id=".$idCurso;
			
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
			
			$todo = $smt->fetchAll();
			
			$return=array("responseCode"=>200, "id"=>$datos );
			$return=json_encode($return);//jscon encode the array
			$em->clear();
			return new Response($return,200,array('Content-Type'=>'application/json'));
		}
	}
	
	public function ActivarCursoAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$datos = array();
	
		parse_str($form,$datos);
		$idCurso = NULL;
	
		if($form!=NULL){
			$idCurso = $datos['id'];
			$em = $this->getDoctrine()->getEntityManager();
				
			$sql = "UPDATE curso SET activo = true where id=".$idCurso;
				
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
				
			$todo = $smt->fetchAll();
                        $em->clear();
			$return=array("responseCode"=>200, "id"=>$datos );
			$return=json_encode($return);//jscon encode the array
				
			return new Response($return,200,array('Content-Type'=>'application/json'));
		}
	}
	
}