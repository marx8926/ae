<?php
namespace AE\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;

class DiscipularServicioController extends Controller
{
	public function getCursosOptionAction($tipo){
		
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "SELECT id,titulo FROM curso where activo=true";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		if(strcmp($tipo,"simple")==0)
			$result = "<select id='curso_select' name='curso' required >";
		else
			$result = "<select id='curso_select' multiple name='prerequisitos[]'>";
	
	    foreach ($todo as $key => $val){
	        $result = $result."<option value='".$val['id']."'>".$val['titulo']."</option>";
	    }
	    $result = $result."</select>";
		return new Response($result);
	}
	
	public function getTablaCursoAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "SELECT * FROM lista_cursos_all";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
                $em->clear();
                
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	
	public function getDocentesOptionAction($tipo){

		$em = $this->getDoctrine()->getEntityManager();

		$sql = "select 
				miembro.id, persona.nombre, persona.apellidos 
				from miembro inner join persona on (persona.id = miembro.id) 
				inner join docente on (miembro.id = docente.id_persona) 
				where miembro.activo=true and docente.activo=true";

		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();

		$todo = $smt->fetchAll();

		if(strcmp($tipo,"simple")==0)
			$result = "<select  name='profesor'>";
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
	
		$sql = "select 
				miembro.id, persona.nombre, persona.apellidos, persona.edad, 
				miembro.id_red as red, miembro.id_celula as celula, 
				miembro.fecha_obtencion as fecha from miembro 
				inner join persona on persona.id = miembro.id 
				where miembro.activo=true and NOT EXISTS (SELECT * FROM docente as d where d.id_persona = miembro.id)";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
                $em->clear();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	

	public function getTablaMiembrosNoEstudianteAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select * from lista_miembros_noestudiantes";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
                
                $em->clear();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getTablaPersonalAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = "select * from lista_personal";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getTablaEstudianteAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$sql = " select * from lista_miembros_estudiantes";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getAsignacionCursoAction(){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select 
				miembro.id, persona.nombre, persona.apellidos,  miembro.id_red as red, docente.activo as estado,docente.fecha_inicio as inicio, docente.fecha_fin as fin 
				from miembro 
				inner join persona on (persona.id = miembro.id) 
				inner join docente on (miembro.id = docente.id_persona) 
				where miembro.activo=true";
		
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
	
		$sql = "select * from lista_curso_impartido";
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
                $em->clear();
	
		return new JsonResponse(array('aaData'=>$todo));
	}
	
	public function getTablaAsignacionMatriculaAction($curso){
            
                $securityContext = $this->get('security.context');

		
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
                                {
                                    if($securityContext->isGranted('ROLE_PROFESOR') )
                                     $Estado = '<td><input class"button_matricula" type="button" id="activo" data="'.$val["id"].'" name="activo" value="Matricular" onclick="EnviarMatricular(this)" /></td>';
                                    else
					$Estado = '<td class="encurso">En Curso</td>';

                                }
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
	
	function getTablaEstudiantePorAsignacionAction($idAsignacion){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sql = "select
				matric.id, persona.nombre, persona.apellidos,miembro.id_red as red,miembro.id_celula as celula,
				estudiante.fecha_inicio as inicio ,estudiante.fecha_fin as fin
				from miembro
				inner join persona on (persona.id = miembro.id) 
				inner join estudiante on (miembro.id = estudiante.id) 
				inner join matric on (estudiante.id = matric.id_persona_estudiante)
				where matric.id_curso_impartido =".$idAsignacion;
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();		
		return new JsonResponse(array('aaData'=>$todo));
		
	}
	
	function getTablaReporteAsignacionAction($idAsignacion,$tipodato){
		$em = $this->getDoctrine()->getEntityManager();
		
		$sqlnum = "SELECT cc.ofrenda
					FROM clase_curso cc
					inner join tema_curso tc on (cc.tema = tc.id)
					where cc.id_curso_impartido =".$idAsignacion."
					order by tc.id";
		
		$smt = $em->getConnection()->prepare($sqlnum);
		$smt->execute();
		
		$num;
		
		$todoofrenda= $smt->fetchAll();
		
		$result='<table id="tabla_asignacion_estado" name="tabla_asignacion_estado" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Red</th>';
		$Ofrenda="<tr><td>Ofrendas</td><td></td>";
			foreach ($todoofrenda as $key => $valofrenda){
				$result=$result."<th>".($key+1)."</th>";
				$Ofrenda = $Ofrenda."<td>".$valofrenda['ofrenda']."</td>";
			}
                    
		$result=$result."</tr></thead><tbody>";
		
		$sql = "select concat(persona.nombre,'', persona.apellidos) as estudiante, persona.id, miembro.id_red
				from miembro
				inner join persona on (persona.id = miembro.id)
				inner join miembro m on (m.id = persona.id)
				inner join estudiante on (miembro.id = estudiante.id) 
				inner join matric on (estudiante.id = matric.id_persona_estudiante)
				where matric.id_curso_impartido =".$idAsignacion;
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		
		$todo = $smt->fetchAll();
		
		foreach ($todo as $key1 => $val){
			
			$result = $result."<tr>
					<td>".$val['estudiante']."</td>
					<td>".$val['id_red']."</td>";
			
			$sqlAsistencia = "SELECT acc.".$tipodato."
							  FROM clase_curso cc 
							  inner join asistencia_clase_curso acc on (acc.id_clase_curso = cc.id)
							  inner join tema_curso tc on (cc.tema = tc.id)
							  where acc.id_persona_estudiante =".$val["id"]." and cc.id_curso_impartido =".$idAsignacion." order by tc.id";
			
			$smt = $em->getConnection()->prepare($sqlAsistencia);
			$smt->execute();
			
			$todoAsistencia = $smt->fetchAll();
			foreach ($todoAsistencia as $key2 => $valor){
				if($tipodato=="nota")
					$result = $result."<td>".$valor[$tipodato]."</td>";
				else
					if($valor[$tipodato]==1)
						$result = $result."<td>&#10004</td>";
					else
						$result = $result."<td></td>";
			}
			
			$result = $result."</tr>";
		}
		$result=$result.$Ofrenda;
		
		$result = $result."</tbody></table>";
		return new Response($result);
	}
	
	function getTablaReporteAsistenciaRedesAction($idCurso,$idRed,$dia){
		$em = $this->getDoctrine()->getEntityManager();
	
		$sqlnum = 	"select count (id) as num
					from tema_curso
					where tema_curso.id_curso = ".$idCurso;
	
		$smt = $em->getConnection()->prepare($sqlnum);
		$smt->execute();
	
		$num;
	
		$todonum = $smt->fetchAll();
	
		foreach ($todonum as $key => $val){
			$num= $val["num"];
		}
	
		$result='<table id="tabla_asistencia_redes" name="tabla_asignacion_estado" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Nro</th>
							<th>Profesor</th>
							<th>Alumno</th>
							<th>Celular<br>Alumno</th>
							<th>Lider</th>
							<th></th>';
		for($i=0;$i < $num;$i++){
			$result=$result."<th>".($i+1)."</th>";
		}
		$result=$result.'</tr></thead><tbody>';
	
		$sql = "select concat(pd.nombre,' ',pd.apellidos) as docente,
				pe.id as id_estudiante,concat(pe.nombre,' ',pe.apellidos) as estudiante,
				pe.celular,c.id as celula, ci.id as curso_impartido
				from estudiante e
				inner join persona pe on(pe.id=e.id)
				inner join miembro mbr on(mbr.id=e.id)
				inner join celula c on (mbr.id_celula = c.id)
				inner join matric m on (m.id_persona_estudiante = e.id)
				inner join curso_impartido ci on(ci.id = m.id_curso_impartido)
				inner join horario h on(h.id = ci.id_horario)
				inner join docente d on (d.id_persona=ci.id_persona_docente)
				inner join persona pd on(pd.id = d.id_persona)";
				
		if($dia=="desentralizado")
			$sql=$sql."where c.id_red = '".$idRed."' and h.dia!='Jueves' and h.dia!='Domingo' and ci.id_curso = ".$idCurso;
		else
			$sql=$sql."where c.id_red = '".$idRed."' and h.dia='".$dia."' and ci.id_curso = ".$idCurso;
	
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
	
		$todo = $smt->fetchAll();
	
		foreach ($todo as $key1 => $val){
	
			$result = $result."<tr>
					<td rowspan='2'>".($key1+1)."</td>
					<td rowspan='2'>".$val['docente']."</td>
					<td rowspan='2'>".$val['estudiante']."</td>
					<td rowspan='2'>".$val['celular']."</td>";
					
			$sqllider = "SELECT concat (nombre,' ',apellidos) as lider from info_celula(:idcelula)";
			$smt = $em->getConnection()->prepare($sqllider);
            $smt->execute(array(':idcelula'=>$val['celula']));
            $liderarray = $smt->fetchAll();
            $lider=$liderarray[0]['lider'];
            $result = $result."<td rowspan='2'>".$lider."</td>";
	
			$sqlAsistencia = "SELECT acc.asistencia,to_char (cc.fecha_dicto, 'dd/mm') as fecha_dicto
							FROM clase_curso cc
							inner join asistencia_clase_curso acc on (acc.id_clase_curso = cc.id)
							inner join tema_curso tc on (cc.tema = tc.id)
							where acc.id_persona_estudiante =".$val["id_estudiante"]."
							and cc.id_curso_impartido =".$val['curso_impartido']." order by tc.id";
	
			$smt = $em->getConnection()->prepare($sqlAsistencia);
			$smt->execute();
				
			$todoAsistencia = $smt->fetchAll();
				
			$celdas_asistencia = "<tr><td>Asist</td>";
			$celdas_fechas ="<td>Fecha</td>";
			foreach ($todoAsistencia as $key2 => $valor){
	
				if($valor["asistencia"]==1)
					$celdas_asistencia = $celdas_asistencia."<td>&#10004</td>";
				else
					$celdas_asistencia = $celdas_asistencia."<td>X</td>";
				$celdas_fechas =$celdas_fechas."<td>".$valor["fecha_dicto"]."</td>";
	
			}
			$celdas_fechas = $celdas_fechas."</tr>";
			$celdas_asistencia = $celdas_asistencia."</tr>";
			$result = $result.$celdas_fechas;
			$result = $result.$celdas_asistencia;
		}
	
	
		$result = $result."</tbody></table>";
		return new Response($result);
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
                
                $em->clear();
		
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
                
                $em->clear();
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
			on (acc.id_clase_curso = cc.id)
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
	
	function getTablaVisionAction($idred){
		$em = $this->getDoctrine()->getEntityManager();
		//Procedimiento almacenado clm_sel_clasesyasistenciacurso_by_idcursoimpartido
		$sql = "select concat(p.nombre,' ', p.apellidos) as nombre , p.id from matric m
				inner join curso_impartido ci on (m.id_curso_impartido = ci.id)
				inner join curso c on(c.id=ci.id_curso)
				inner join persona p on (p.id = m.id_persona_estudiante)
				inner join miembro mbr on (m.id_persona_estudiante = mbr.id)
				where mbr.id_red = '".$idred."'";
		
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();		
		$Estudiantes = $smt->fetchAll();
		
		$sql = "SELECT id, titulo FROM curso where activo=true order by 1";
		$smt = $em->getConnection()->prepare($sql);
		$smt->execute();
		$Cursos = $smt->fetchAll();
                $em->clear();
		
		$result = 	"<table id='tabla_vision' name='tabla_vision' class='table table-striped table-bordered'>
					<thead>
					<tr>
					<th>Miembro</th>";
		
		foreach ($Cursos as $key => $val1){
			$result = $result."
						<th>".$val1["titulo"]."</th>";
		}
		$result = $result."</tr>
					</thead>
					<tbody>";
		$flag = true;
		foreach ($Estudiantes as $key2 => $val2){
			$result = $result."<tr><td>".$val2["nombre"]."</td>";
			$flag = false;
			foreach ($Cursos as $key3 => $val3){
				$sql = "select m.id from matric m
						inner join estudiante e on (m.id_persona_estudiante = e.id)
						inner join curso_impartido ci on (m.id_curso_impartido = ci.id)
						inner join curso c on (ci.id_curso = c.id)
						where c.id=".$val3["id"]."  and e.id=".$val2["id"];
				$smt = $em->getConnection()->prepare($sql);
				$smt->execute();
				$Matriculado = $smt->fetchAll();
                                
                                $em->clear();
                                
				if(count($Matriculado)>0)
					$result = $result."<td>X </td>";
				else
					$result = $result."<td> </td>";
			}
			$result = $result."</tr>";
		}
		
		if($flag)
			$result = $result."<tr><td colspan='6'>Datos No Disponibles</td></tr>";
		
		$result = $result."</tbody></body>";
		return new Response($result);
	}
	
	function getTablaInformeDiscipularAction($idcelula=null){
		
		$em = $this->getDoctrine()->getEntityManager();
		//Procedimiento almacenado clm_sel_clasesyasistenciacurso_by_idcursoimpartido
		$sqlcurso = "select id,titulo from curso order by 1";
	
		$smt = $em->getConnection()->prepare($sqlcurso);
		$smt->execute();
		$cursos = $smt->fetchAll();
	
		$result = 	"<table id='tabla_vision' name='tabla_vision' class='table table-striped table-bordered'>
					<thead>
					<tr>
					<th rowspan='2'>NOMBRE Y APELLIDO</th>";
		$tdclases = "<tr>";
		
		foreach ($cursos as $key => $valcurso){
			
			
			$sqltemas = "SELECT count(id) as num
  						FROM tema_curso where id_curso=".$valcurso["id"];
			$smt= $em->getConnection()->prepare($sqltemas);
			$smt->execute();
			$temas = $smt->fetchAll();
			
			$num = $temas[0]['num'];
			$result = $result."
						<th colspan='".$num."'>".$valcurso["titulo"]."</th>";
			for($i=0;$i<$num;$i++){
				$tdclases = $tdclases."<td>".($i+1)."</td>";
			}			
		}
		
		$result=$result."</tr>".$tdclases."</tr></thead><tbody>";
		
		$sqlestudiantes = "select concat(nombre,' ',apellidos) as estudiante, e.id
						from celula c
						inner join miembro me on (me.id_celula = c.id)
						inner join persona pe on (pe.id = me.id)
						inner join estudiante e on (e.id = pe.id)
						where c.id =".$idcelula;
		
		$smt = $em->getConnection()->prepare($sqlestudiantes);
		$smt->execute();
		$Estudiantes = $smt->fetchAll();
		
		$flag = true;
		foreach ($Estudiantes as $key2 => $valestudiante){
			$result = $result."<tr><td>".$valestudiante["estudiante"]."</td>";
			$flag = false;
			foreach ($cursos as $key3 => $valcurso){
				$sqlasistencia = "SELECT acc.asistencia
						FROM clase_curso cc 
						inner join asistencia_clase_curso acc on (acc.id_clase_curso = cc.id)
						inner join tema_curso tc on (cc.tema = tc.id)
						inner join curso_impartido ci on(cc.id_curso_impartido= ci.id)
						where acc.id_persona_estudiante =".$valestudiante["id"]." and ci.id_curso=".$valcurso['id']." order by cc.id";
				$smt = $em->getConnection()->prepare($sqlasistencia);
				$smt->execute();
				$asistencia = $smt->fetchAll();
	
				$em->clear();
	
			foreach ($asistencia as $key2 => $valasistencia){
	
				if($valasistencia["asistencia"]==1)
					$result = $result."<td>X</td>";
				else
					$result = $result."<td>F</td>";
	
			}
			}
			$result = $result."</tr>";
		}
	
		if($flag)
			$result = $result."<tr><td colspan='6'>Datos No Disponibles</td></tr>";
	
		$result = $result."</tbody></body>";
		return new Response($result);
	}
	
	public function getCelulaSelectAction($idred){
		
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            //misioneros
            $sql = "select cid, concat(nombre,' ',apellidos) as lider from info_celula_red(:idred)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idred'=>$idred));
            $celulas = $smt->fetchAll();
            $em->clear();
            
            $result="<select id='celula'>";
            
            foreach($celulas as $key => $varcel) {
               	$result = $result."<option id='lider_select_option' value='".$varcel['cid']."'>".$varcel['lider']."</option>";
            }
           
            $result=$result."</select>";
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new Response($result);
    }
    
    function getTablaReporteSemanalIndeliAction($desde,$hasta,$tipo){
    	$em = $this->getDoctrine()->getEntityManager();
    	//Procedimiento almacenado clm_sel_clasesyasistenciacurso_by_idcursoimpartido
    	$sqltabla = "SELECT c.titulo, concat(pd.nombre,' ',pd.apellidos) as docente,
					l.nombe, to_char( h.hora_inicio,'HH24:MI') as hora_inicio,  to_char( h.hora_fin,'HH24:MI')as hora_fin, cc.ofrenda, tc.descripcion,
					(select count (matric.id) from matric
					where matric.id_curso_impartido = ci.id ) as matriculados, 
					SUM(CASE WHEN acc.asistencia=true then 1
					ELSE 0
					END) as cantidad
					FROM curso c
					inner join curso_impartido ci on (ci.id_curso = c.id)
					inner join persona pd on (pd.id = ci.id_persona_docente)
					inner join horario h on(h.id = ci.id_horario)
					inner join local l on (l.id = ci.id_local)
					inner join clase_curso cc on (cc.id_curso_impartido = ci.id)
					inner join tema_curso tc on (cc.tema = tc.id)
					inner join asistencia_clase_curso acc on (acc.id_clase_curso = cc.id)
					";
    	
    	$condicion = "";
    	if($tipo == "desentralizado")
    		$condicion = "where h.dia != 'Jueves' and h.dia!='Domingo' and cc.fecha_dicto BETWEEN '".$desde."' and '".$hasta."'";
    	else
    		$condicion = "where h.dia = '".$tipo."' and cc.fecha_dicto BETWEEN '".$desde."' and '".$hasta."'";
    	
    	$sqltabla = $sqltabla.$condicion."group by c.titulo, pd.nombre, pd.apellidos, l.nombe, h.hora_inicio, h.hora_fin, cc.ofrenda, tc.descripcion, ci.id";
    	
    	$smt = $em->getConnection()->prepare($sqltabla);
    	$smt->execute();
    	$tabla = $smt->fetchAll();
    	
    	$result = "
    			<table id='tabla_vision' name='tabla_vision' class='table table-striped table-bordered'>
				<thead>
				<tr>
    			<th>Nro</th>
    			<th>Curso</th>
    			<th>Profesor</th>
    			<th>Lugar</th>
    			<th>Hora</th>
    			<th>Tema</th>
    			<th>Nro<br>Alum</th>
    			<th>Asis</th>
    			<th>Ofrn</th>
    			</thead>
				<tbody>";
    	
    	foreach ($tabla as $key => $val){
    		$result = $result."
						<tr>
						<td>".($key+1)."</td>
						<td>".$val['titulo']."</td>
						<td>".$val['docente']."</td>
						<td>".$val['nombe']."</td>
						<td>".$val['hora_inicio']."<br>".$val['hora_fin']."</td>
						<td>".$val['descripcion']."</td>
						<td>".$val['matriculados']."</td>
						<td>".$val['cantidad']."</td>
						<td>".$val['ofrenda']."</td>
						</tr>";
    		}
    		$result = $result."</tbody></table>";
    		return new Response($result);
    }
    
    function gerTablaResumenReporteSemanalIndeliAction($desde,$hasta){
    	$em = $this->getDoctrine()->getEntityManager();
    	$sql = "select sum(ofrenda) as ofrenda, count (clase_curso.id) as clases,
				(SELECT count(id_persona)
				  FROM docente
					where activo = true) as docentes,
				(select count (m.id) 
					from matric m
					inner join curso_impartido ci on (m.id_curso_impartido = ci.id)) as matriculados,
				(select (SUM(CASE WHEN asistencia=true then 1
					ELSE 0
					END) ) from asistencia_clase_curso acc 
					inner join clase_curso cc on (acc.id_clase_curso = cc.id)
					where cc.fecha_dicto BETWEEN '".$desde."' and '".$hasta."') as asistencia
				from clase_curso
				where fecha_dicto BETWEEN '".$desde."' and '".$hasta."'";
    	$smt = $em->getConnection()->prepare($sql);
    	$smt->execute();
    	$tabla = $smt->fetchAll();
    	$result= "<table class='table table-striped table-bordered'><tr>
    			<td rowspan='6'>INDELI RESUMEN</td>
    			<td>Maestros</td><td>".$tabla[0]['docentes']."</td>
    			<tr><td>Clases</td><td>".$tabla[0]['clases']."</td></tr>
    			<tr><td>Alumnos</td><td>".$tabla[0]['matriculados']."</td></tr>
    			<tr><td>Asistencia</td><td>".($tabla[0]['matriculados']-$tabla[0]['asistencia'])."</td></tr>
    			<tr><td>No Asistieron</td><td>".$tabla[0]['asistencia']."</td></tr>
    			<tr><td>Ofrenda</td><td>".$tabla[0]['ofrenda']."</td></tr>
    					</table>";
    	return new Response($result);
    }
    
    
   
}
