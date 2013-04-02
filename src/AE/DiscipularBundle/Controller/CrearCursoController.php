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
		return $this->render('AEDiscipularBundle:Default:crearcurso.html.twig');
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
			$this->getDoctrine()->getEntityManager()->beginTransaction();
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
					$uploadFileName = date("Y-m-d-H-i-s").$FileName;
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