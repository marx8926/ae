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
		$result = "<table cellpadding='0' cellspacing='0' border='0' class='display table table-striped dataTable table-bordered'>
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
					        <td><input type='button' id='ver' class='button_ver' data='".$val["id"]."' onclick='IrAAsignacion(this)' value='Ver'></td>
					    </tr>";
		}
			$result =$result."</tbody></table>";
			return new Response($result);
	}
	
	function getTablaEstudiantePorCursoAction($curso){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select 
				matric.id, persona.nombre, persona.apellidos,miembro.id_red as red,miembro.id_celula as celula,
				estudiante.fecha_inicio as inicio ,estudiante.fecha_fin as fin
				from miembro
				inner join persona on (persona.id = miembro.id) 
				inner join estudiante on (miembro.id = estudiante.id) 
				inner join matric on (estudiante.id = matric.id_persona_estudiante)
				where matric.id_curso_impartido =".$curso;
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	function getTablaEstudianteActivoAction($activo){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select 
				miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red,
				miembro.id_celula as celula,miembro.fecha_obtencion as fecha
				from miembro 
				inner join persona on (persona.id = miembro.id) 
				inner join estudiante on (miembro.id = estudiante.id) 
				where estudiante.activo=".$activo;
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	function getTablaClaseAsistenciaAction($idclase){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "SELECT 
				estudiante.id as idestudiante, persona.nombre, persona.apellidos, asistencia_clase_curso.nota,
				asistencia_clase_curso.asistencia 
				FROM clase_curso 
				inner join tema_curso on (tema_curso.id = clase_curso.tema) 
				inner join asistencia_clase_curso on (clase_curso.id = asistencia_clase_curso.id_clase_curso)
				inner join estudiante on (asistencia_clase_curso.id_persona_estudiante = estudiante.id)
				inner join persona on (estudiante.id =  persona.id) 
				where clase_curso.id=".$idclase;
		
		$result = "<table id='tabla_asignacion_estado' name='tabla_asignacion_estado' class='table table-striped table-bordered'>
					<col width='10%' />
					<col width='70%' />
					<col width='10%' />
					<col width='10%' />
					<thead>
					<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Nota</th>
					<th>Asistencia</th>
					</tr>
					</thead>
					<tbody>";
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		foreach ($todo as $key => $val){
			if($val['asistencia']==1){
				$asisvalue = "true";
				$asistencia = "<input id='check".$key."' class='modificable check' disabled type='checkbox' value='true' checked onClick=\"CheckChange(this,'hidden".$key."')\">";
			}
			else {
				$asistencia = "<input id='check".$key."' class='modificable check' disabled type='checkbox' value='true' onClick=\"CheckChange(this,'hidden".$key."')\">";
				$asisvalue = "false";
			}
			$result = $result."
						<tr>
						<td>".$val['idestudiante']."</td>
						<td>".$val['nombre']." ".$val['apellidos']."</td>
						<td><input type='number' disabled class='modificable' max='20' min='0' name='nota[]' value='".$val['nota']."'></td>
						<td>".$asistencia."<input id='hidden".$key."' type='hidden' name='asistencia[]' value='".$asisvalue."'>
								<input type='hidden' name='idestudiante[]' value='".$val['idestudiante']."'></td>
						</tr>";
		}
		$result = $result."</tbody></table>";
		return new Response($result);
	}
	

	function getTablaClaseAsignacionAction($idasignacion){
		$em = $this->getDoctrine()->getEntityManager();
	    //Procedimiento almacenado clm_sel_clasesyasistenciacurso_by_idcursoimpartido
	    
		$sql = "SELECT SUM(CASE WHEN acc.asistencia=true then 1
			ELSE 0
			END) as cantidad,
			acc.id_clase_curso,tc.descripcion,cc.fecha_dicto 
			FROM asistencia_clase_curso as acc
			inner join clase_curso cc 
			on (acc.id_clase_curso = id)
			inner join tema_curso tc
			on (tc.id = cc.tema)
			where (cc.id_curso_impartido=".$idasignacion.")
			group by acc.id_clase_curso,tc.descripcion,cc.fecha_dicto
			order by 2 asc";
	
		$result = "<table id='tabla_asignacion_estado' name='tabla_asignacion_estado' class='table table-striped table-bordered'>
					<col width='10%' />
					<col width='60%' />
					<col width='10%' />
					<col width='10%' />
					<col width='10%' />
					<thead>
					<tr>
					<th>Nª</th>
					<th>Descripcion</th>
					<th>Fecha</th>
					<th>Asistencia</th>
					<th>Ver</th>
					</tr>
					</thead>
					<tbody>";
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		foreach ($todo as $key => $val){
			
			$result = $result."
						<tr>
						<td>".($key+1)."</td>
						<td>".$val['descripcion']."</td>
						<td>".$val['fecha_dicto']."</td>
						<td>".$val['cantidad']."</td>
						<td><input type='button' data='".$val['id_clase_curso']."' value='Ver' onclick='IrAClase(this)'></td>
						</tr>";
		}
		$result = $result."</tbody></table>";
		return new Response($result);
	}
}
