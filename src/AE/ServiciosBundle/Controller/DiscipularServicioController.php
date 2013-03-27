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
			$result = "<select name='curso'>";
		else
			$result = "<select multiple name='prerequisitos[]' required>";
	
	    foreach ($todo as $key => $val){
	        $result = $result."<option value='".$val['id']."'>".$val['titulo']."</option>";
	    }
	    $result = $result."</select>";
		return new Response($result);
	}
		public function getDocentesOptionAction($tipo){
	
			$em = $this->getDoctrine()->getEntityManager();
	
			$sql = "select miembro.id, persona.nombre, persona.apellidos from miembro inner join persona on (persona.id = miembro.id) inner join docente on (miembro.id = docente.id_persona) where miembro.activo=true and docente.activo=true";
	
			$smt = $em->getConnection()->prepare($sql);
			$smt->execute();
	
			$todo = $smt->fetchAll();
	
			if(strcmp($tipo,"simple")==0)
				$result = "<select name='profesor'>";
			else
				$result = "<select multiple name='porfesores[]' required>";
	
			foreach ($todo as $key => $val){
				$result = $result."<option value='".$val['id']."'>".$val['nombre']." ".$val['apellidos']."</option>";
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
	
	public function getAsignacionCursoAction(){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select miembro.id, persona.nombre, persona.apellidos,  miembro.id_red as red, docente.activo as estado,docente.fecha_inicio as inicio, docente.fecha_fin as fin from miembro inner join persona on (persona.id = miembro.id) inner join docente on (miembro.id = docente.id_persona) where miembro.activo=true";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getLocalOptionAction($tipo){
		
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "SELECT * FROM local";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();

		$todo = $smt->fetchAll();

		if(strcmp($tipo,"simple")==0)
			$result = "<select name='local'>";
		else
			$result = "<select multiple name='locales[]' >";

		foreach ($todo as $key => $val){
			$result = $result."<option value='".$val['id']."'>".$val['nombe']."</option>";
		}
		$result = $result."</select>";
		return new Response($result);
	}
	
	public function getTablaAsignacionAction(){
	
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos, curso.titulo, horario.dia, horario.hora_inicio, horario.hora_fin  from miembro inner join persona on (persona.id = miembro.id) inner join docente on (miembro.id = docente.id_persona) inner join curso_impartido on (docente.id_persona = curso_impartido.id_persona_docente) inner join horario on (curso_impartido.id_horario= horario.id) inner join curso on (curso.id = curso_impartido.id_curso)";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
		$result = "<table>
			<tbody>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Curso</th>
					<th>Dia</th>
					<th>Hora Inicio</th>
					<th>Hora Fin</th>
					<th>Seleccionar</th>
				</tr>";
		
		foreach ($todo as $key => $val){
			$result ="
					<tr>
					<td>".$val['id']."</td>
					<td>".$val['nombre']."</td>
					<td>".$val['apellidos']."</td>
					<td>".$val['titulo']."</td>
					<td>".$val['dia']."/td>
					<td>".$val['hora_inicio']."</td>
					<td>".$val['hora_fin']."</td>
					<td><input type='checkbox' value='".$val['id']."'></td>
					</tr>
					";
		}
		$result = $result."</table>";
		return new Response($result);
	}
}
