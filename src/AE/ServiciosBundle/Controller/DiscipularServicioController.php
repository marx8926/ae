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
		
		$sql = "SELECT * FROM curso";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		if(strcmp($tipo,"simple")==0)
			$result = "<select name='curso' required >";
		else
			$result = "<select multiple name='prerequisitos[]'>";
	
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
	
	public function getTablaMiembrosNoPersonalAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha from miembro inner join persona on persona.id = miembro.id where miembro.activo=true and NOT EXISTS (SELECT * FROM docente as d where d.id_persona = miembro.id)";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	

	public function getTablaMiembrosNoEstudianteAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha from miembro inner join persona on persona.id = miembro.id where miembro.activo=true and NOT EXISTS (SELECT * FROM estudiante as d where d.id = miembro.id)";
	
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
	
	public function getTablaEstudianteAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos,  miembro.id_red as red, estudiante.activo as estado,estudiante.fecha_inicio as inicio, estudiante.fecha_fin as fin from miembro inner join persona on (persona.id = miembro.id) inner join estudiante on (miembro.id = estudiante.id) where miembro.activo=true";
	
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
	
		$sql = "select miembro.id, persona.nombre, persona.apellidos, curso.titulo, horario.dia, horario.hora_inicio, horario.hora_fin,curso_impartido.id as idasignacion,curso_impartido.id_horario  from miembro inner join persona on (persona.id = miembro.id) inner join docente on (miembro.id = docente.id_persona) inner join curso_impartido on (docente.id_persona = curso_impartido.id_persona_docente) inner join horario on (curso_impartido.id_horario= horario.id) inner join curso on (curso.id = curso_impartido.id_curso)";
	
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
			$result =$result."
					<tr>
					<td>".$val['id']."</td>
					<td>".$val['nombre']."</td>
					<td>".$val['apellidos']."</td>
					<td>".$val['titulo']."</td>
					<td>".$val['dia']."</td>
					<td>".$val['hora_inicio']."</td>
					<td>".$val['hora_fin']."</td>
					<td><input type='checkbox' name='id[]' value='".$key."'><input type='hidden' name='asignacion".$key."' value='".$val['idasignacion']."'><input type='hidden' name='horario".$key."' value='".$val['id_horario']."'></td>
					</tr>
					";
		}
		$result = $result."</table>";
		return new Response($result);
	}
	
	public function getTablaAsignacionMatriculaAction($curso){
		
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select curso_impartido.id as id,persona.nombre, persona.apellidos,
				curso_impartido.fecha_inicio,curso_impartido.fecha_fin, horario.hora_inicio, horario.hora_fin,
				curso_impartido.activo, curso_impartido.estado_matricula
				from persona
				inner join docente on (persona.id = docente.id_persona) 
				inner join curso_impartido on (docente.id_persona = curso_impartido.id_persona_docente)
				inner join horario on (curso_impartido.id_horario= horario.id) 
				inner join curso on (curso.id = curso_impartido.id_curso) where curso.id=".$curso;
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		$result='<table id="tabla_asignacion_estado" name="tabla_asignacion_estado" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre del Profesor</th>
							<th>Fecha de Inicio</th>
							<th>Fecha de Fin</th>
							<th>Hora de Inicio</th>
							<th>Hora de Fin</th>
					        <th>Estado</th>
					        <th>N° Matriculados</th>
					    </tr>
					</thead>
					<tbody>';
		foreach ($todo as $key => $val){
			if($val['activo']==1)
				if($val['estado_matricula']==1)
					$Estado = '<td><input class"button_matricula" type="button" id="activo" data="'.$val["id"].'" name="activo" value="Matricular" onclick="EnviarMatricular(this)" /></td>';
				else
					$Estado = '<td class="encurso">En Curso</td>';
			else
				$Estado = '<td class="cerrado">Cerrado</td>';
			$result =$result."<tr>
							<td>".$val["id"]."</td>
							<td>".$val["nombre"]." ".$val["apellidos"]."</td>
							<td>".$val["fecha_inicio"]."</td>
							<td>".$val["fecha_fin"]."</td>
							<td>".$val["hora_inicio"]."</td>
							<td>".$val["hora_fin"]."</td>
					        ".$Estado."
					        <td><a id='ver' class='button_ver' data='".$val["id"]."'>Ver</a></td>
					    </tr>";
		}
			$result =$result."</tbody></table>";
			return new Response($result);
	}
	
	function getTablaEstudiantePorCursoAction($curso){
		
	}
	
	function getTablaEstudianteActivoAction($activo){
	
	}
}
