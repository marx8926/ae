<?php
namespace AE\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;

class DiscipularServicioController extends Controller
{
	public function getCursosOptionAction($tipo){
		
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select * from curso";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		if(strcmp($tipo,"simple")==0)
			$result = "<select name='curso'><option value=''>Ninguno</option>";
		else
			$result = "<select multiple name='prerequisitos[]' required><option value=''>Ninguno</option>";
	
	    foreach ($todo as $key => $val){
	        $result = $result."<option value='".$val['id']."'>".$val['titulo']."</option>";
	    }
	    $result = $result."</select>";
		return new Response($result);
	}
		public function getProfesoresOptionAction(){
	
			$em = $this->getDoctrine()->getEntityManager();
	
			$sql = "select * from curso";
	
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
	
			$todo = $smt->fetchAll();
	
			$result = "<select multiple name='prerequisitos[]'><option value=''>Ninguno</option>";
	
			foreach ($todo as $key => $val){
				$result = $result."<option value='".$val['id']."'>".$val['titulo']."</option>";
			}
			$result = $result."</select>";
			return new Response($result);
		}
	
	public function getTablaMiembrosAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha from miembro inner join persona on persona.id = miembro.id where miembro.activo=true and NOT EXISTS (SELECT * FROM docente as d where d.id_persona = miembro.id)";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getTablaPersonalAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos,  miembro.id_red as red, docente.activo as estado,docente.fecha_inicio as inicio, docente.fecha_fin as fin from miembro inner join persona on (persona.id = miembro.id) inner join docente on (miembro.id = docente.id_persona) where miembro.activo=true";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
}
