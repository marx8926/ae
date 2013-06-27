<?php

namespace AE\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class GanarServicioController extends Controller
{
     //lista de redes activas
    public function redAction()
    {
        $redes = array();
        $em = $this->getDoctrine()->getEntityManager();

        
        try {
            $em->beginTransaction();
            $sql = 'select * from lista_redes';

            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();

            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       $resultado= new JsonResponse($redes);
       $resultado->setMaxAge(60);
       $resultado->setPublic();
       
       return $resultado;
    }
    
    
     public function redsinAction()
    {
        $redes = array();
        $em = $this->getDoctrine()->getEntityManager();

        
        try {
            $em->beginTransaction();
            $sql = 'select * from red where activo=true order by id';

            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();

            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       $resultado = new JsonResponse($redes);
       $resultado->setMaxAge(60);
       $resultado->setPublic();
       return $resultado;
       
    }
    
    public function regionAction()
    {
      
    
        $em = $this->getDoctrine()->getEntityManager();
        $redes = array();
       
        try{
            
            $em->beginTransaction();
            
            $sql = 'select * from lista_regiones';
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            $em->clear();
            
            $em->commit();
        }  catch (Exception $e)
        {
            $em->rollback();
            $em->close();
            throw  $e;
        }
    
        $resultado = new JsonResponse($redes); 
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;
        
    }
    
     public function lugarAction()
     {
        $sql = 'select * from lugar';
        
        $redes = array();
 
        $em = $this->getDoctrine()->getEntityManager();
        try {
            
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            $em->clear();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

        $resultado = new JsonResponse($redes); 
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;
      }
      
    //celula id
    public function celulaAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        
        try {
            
            $em->beginTransaction();
            
            $sql = 'select * from celulas_por_red(:red,:tip) ';
       
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$id,':tip'=>0));
 
            $redes = $smt->fetchAll();
            $em->clear();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $resultado = new JsonResponse($redes); 
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;
    }
    
    public function provinciaAction($id)
    {      
  
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
        try {
            $em->beginTransaction();
            $sql = 'SELECT cod as codprovincia, prov as provincia from ver_provincia(:id)';
      
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $redes = $smt->fetchAll();
            $em->clear();
            $em->commit();
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

        $resultado = new JsonResponse($redes); 
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;         
    }
    public function distritoAction($dep, $prov)
    {
        
         $em = $this->getDoctrine()->getEntityManager();
         $redes = array();
         
         try {
             $em->beginTransaction();
             
             $sql = 'select ids as id, 
                    coddep as coddepartamento,
                    codprov as codprovincia,
                    coddis as coddistrito,
                    dep as departamento,
                    prov as provincia,
                    dist as distrito,
                    lati as lati,
                    longi as "long" from ver_distrito(:dep,:prov)';
             $smt = $em->getConnection()->prepare($sql);
             $smt->execute(array(':dep'=>$dep,':prov'=>$prov));
 
             $redes = $smt->fetchAll();
             $em->clear();
             
             $em->commit();
        
         } catch (Exception $exc) {
             $em->rollback();
             $em->close();
             throw $exc;
         }

        $resultado = new JsonResponse($redes); 
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;
    }

    public function listaconvertidosAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $redes = array();
        try {
            $em->beginTransaction();
            
            $sql = "select *from nuevos_convertidos";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $result = JsonResponse(array('aaData'=>$redes));
        $result->setMaxAge(60);
        $result->setPublic();

        return $result;
    }
    
     public function listaconvertidos_ganadorAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $redes = array();
        try {
            $em->beginTransaction();
            
            $sql = "select *from nuevos_convertidos_ganador";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $resultado = new JsonResponse(array('aaData'=>$redes));
        $resultado->setMaxAge(60);
        $resultado->setPublic();

        return $resultado;
    }
    
    public function personaAction($id)
    {        
        $sql_persona = "select * from get_persona(:id)";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql_persona);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
        $em->clear();
   
       $result = new JsonResponse($redes);
       $result->setMaxAge(60);
       $result->setPublic();
       return $result;
    }
    
    public function ubigeoAction($id)
    {
        $sql = 'select * from ubigeo where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
 
        $redes = $smt->fetchAll();
        $em->clear();
   
       $result = new JsonResponse($redes);
       $result->setMaxAge(60);
       $result->setPublic();
       return $result;
       
    }
    
    public function nuevoconvertidoAction($id)
    {
        $sql = "select * from get_convertido(:id)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $redes = $smt->fetch();
            $em->clear();
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       
       $result = new JsonResponse($redes);
       $result->setMaxAge(60);
       $result->setPublic();
       
       return $result;
    }
    
    function getTablaGanarFechaAction($fecha1,$fecha2){
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	
    	$fecha1_formateada = date("Y-m-d", strtotime($fecha1));
    	$fecha2_formateada = date("Y-m-d", strtotime($fecha2));
    	 
    	$sql = "SELECT 
				CONCAT(lred.nombre,' ',lred.apellidos) as lider_red , cel.id as celula, 
				CONCAT(pnc.nombre,' ', pnc.apellidos) as nuevo_convertido, nc.fecha_conversion, 
				CONCAT(pcdor.nombre,' ',pcdor.apellidos) as consolidador, l.nombre as lugar
				FROM nuevo_convertido nc
				inner join lugar l on (l.id = nc.id_lugar)
				inner join celula cel on(cel.id = nc.id_celula)
				inner join consolida c on (nc.id= c.id_nuevo_convertido)
				inner join consolidador cdor on (c.id_consolidador = cdor.id)
				inner join red r on (r.id = cel.id_red)
				inner join persona pcdor on (pcdor.id = cdor.id)
				inner join persona pnc on (nc.id = pnc.id)
				inner join persona lred on (lred.id = r.id_lider_red)
				where nc.fecha_conversion BETWEEN '".$fecha1."' AND '".$fecha2."'";
    
    	$result = "<table id='tabla_informe_ganar' name='tabla_asignacion_estado' class='table table-striped table-bordered'>
					<thead>
					<tr>
					<th style='width: 22%;'>DOCE DEL PASTOR</th>
					<th style='width: 6;'>CODIGO</th>
					<th style='width: 22%;'>NOMBRE DEL NUEVO<br>CONVERTIDO</th>
					<th style='width: 15;'>FECHA</th>
					<th style='width: 22%;'>NOMBRE DEL<br>CONSOLIDADOR</th>
    				<th style='width: 13;'>DONDE LO GANO</th>
					</tr>
					</thead>
					<tbody>";
    	$smt = $em->getConnection()->prepare($sql);
    	$smt->execute();
    
    	$todo = $smt->fetchAll();
        $em->clear();
    	$flag = true;
    	foreach ($todo as $key => $val){
    		$flag = false;
    		$result = $result."
						<tr>
						<td>".$val['lider_red']."</td>
						<td>".$val['celula']."</td>
						<td>".$val['nuevo_convertido']."</td>
						<td>".$val['fecha_conversion']."</td>
						<td>".$val['consolidador']."</td>
						<td>".$val['lugar']."</td>
						</tr>";
    	}
    	if($flag)
    		$result = $result."
						<tr>
						<td colspan='6'>Datos No Disponibles</td>
						</tr>";
    		
    	$result = $result."</tbody></table>";
    	$resultado = new Response($result);
        $resultado->setMaxAge(60);
        $resultado->setPublic();
        return $resultado;
    }
    
    //tipo: 0 mujeres , 1 hombres , 2 otros
    //    
    public function getTablaGanarSemanalAction($tipo, $nombre, $fecha1, $fecha2)
    {
        $em = $this->getDoctrine()->getEntityManager();
    	
    	
    	$fecha1_formateada = date("Y-m-d", strtotime($fecha1));
    	$fecha2_formateada = date("Y-m-d", strtotime($fecha2));
    	 
    	
    
    	$result = "<table id='".$nombre."' name='".$nombre."' class='table table-striped table-bordered'>
					<thead>
					<tr>
                    <th width='15%'>CODIGO</th>
					<th>DOCE DEL PASTOR</th>
					<th>N° ALMAS</th>
					</tr>
					</thead>
					<tbody>";
        
        try{
           
            $em->beginTransaction();
            
            $sql = "select * from get_reporte_ganar(:tipo,:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>$tipo,':inicio'=>$fecha1_formateada,':fin'=>$fecha2_formateada));
    
            $todo = $smt->fetchAll();
            $em->clear();
            $flag = true;
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $todo = array();
            $em->rollback();
            $em->close();
            
            throw $e;
        }
    	foreach ($todo as $key => $val){
    		$flag = false;
    		$result = $result."
						<tr>
						<td>".$val['id']."</td>
						<td>".$val['nombre']." ".$val['apellidos']."</td>
						<td>".$val['almas']."</td>
						</tr>";
    	}
    	if($flag)
    		$result = $result."
						<tr>
						<td colspan='6'>Datos No Disponibles</td>
						</tr>";
    		
    	$result = $result."</tbody></table>";
    	$resultado = new Response($result);
        $resultado->setPublic();
        $resultado->setMaxAge(60);
        
        return $resultado;
        
    }
    
    public function  getTablaLugarGanadosAction($nombre, $fecha1, $fecha2)
    {
           $em = $this->getDoctrine()->getEntityManager();
    	
    	
    	$fecha1_formateada = date("Y-m-d", strtotime($fecha1));
    	$fecha2_formateada = date("Y-m-d", strtotime($fecha2));
    	 
    	
    
    	$result = "<table id='".$nombre."' name='".$nombre."' class='table table-striped table-bordered'>
					<thead>
					<tr>
                    <th>NOMBRE</th>
					<th>VARONES</th>
					<th>MUJERES</th>
					</tr>
					</thead>
					<tbody>";
        
        try{
            
            $em->beginTransaction();
            
            //mujeres
            $sql = "select * from get_reporte_lugar_ganar(:tipo,:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>0,':inicio'=>$fecha1_formateada,':fin'=>$fecha2_formateada));
            $todo_m = $smt->fetchAll();
            $em->clear();
            
            //hombres
            $sql = "select * from get_reporte_lugar_ganar(:tipo,:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>1,':inicio'=>$fecha1_formateada,':fin'=>$fecha2_formateada));
            $todo_h = $smt->fetchAll();
            $em->clear();
            
            $flag = true;
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $todo_m = array();
            $todo_h = array();
            
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        $n = count($todo_h);
        $flag = true;
        
    	for($i=0; $i<$n; $i++)            
        {
                $val_h = $todo_h[$i];
                $val_m = $todo_m[$i];
              
    		$flag = false;
    		$result = $result."
			<tr>
			<td>".$val_h['nombre']."</td>
			<td>".$val_h['almas']."</td>
                        <td>".$val_m['almas']."</td>
			</tr>";
    
    	}
    	if($flag)
    		$result = $result."
						<tr>
						<td colspan='6'>Datos No Disponibles</td>
						</tr>";
    		
    	$result = $result."</tbody></table>";
    	$resultado = new Response($result);
        $resultado->setMaxAge(60);
        $resultado->setPublic();
        return $result;
    }
    
    public function convertido_Red_LugarAction($fecha1, $fecha2)
    {
        $sql = "select * from get_reporte_nuevos_convertidos_lugar(:ini, :fin)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':ini'=>$fecha1,':fin'=>$fecha2));
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $result = new JsonResponse(array('aaData'=>$redes));
       $result->setMaxAge(60);
       $result->setPublic();
       return $result;
    }
    
    public function lideres_red_tipoAction($red, $tipo)
    {
            $sql = "select * from get_lider_tipo(:tipo, :red)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
        
        $result = "<option value='-1'>Sin Lider</option>";
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>$tipo,':red'=>$red));
 
            $redes = $smt->fetchAll();
            
            foreach ($redes as $value) {
                $result = $result."<option value='".$value['id']."' data-padre='".$value['padre']."' >".
                        $value['nombres']." </option>";
            }
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $resultado = new Response("<select>".$result."</select>");
       $resultado->setMaxAge(60);
       $resultado->setPublic();
       return $resultado;
    }
    
    public function nuevos_red_liderAction($red, $inicio,$fini)
    {
        $sql = "select * from  get_ganador_lider_red(:red,:ini,:fin)";

        $ini = new \DateTime($inicio);
        $fin = new \DateTime($fini);
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        $em->beginTransaction();

        try{
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':ini'=>$ini->format('Y-m-d'),':fin'=>$fin->format('Y-m-d')));
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
        $resultado = new JsonResponse(array('aaData'=>$redes));
        $resultado->setMaxAge(60);
        $resultado->setPublic();
        return $resultado;
    }
    
    public function nuevos_red_lider_doceAction($red, $inicio, $fin, $doce)
    {
        $sql = "select * from  get_ganador_lider_red_doce(:red,:ini,:fin,:doce)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':ini'=>$inicio,':fin'=>$fin,':doce'=>$doce));
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $resultado = new JsonResponse(array('aaData'=>$redes)); 
       $resultado->setPublic();
       $resultado->setMaxAge(60);
       return $resultado;
       
    }

    
     public function nuevos_red_lider_cientoAction($red, $inicio, $fin, $doce, $padre)
    {
        $sql = "select * from  get_ganador_lider_red_ciento(:red,:ini,:fin,:padre,:doce)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':ini'=>$inicio,':fin'=>$fin,':padre'=>$padre, ':doce'=>$doce));
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $resultado = new JsonResponse(array('aaData'=>$redes)); 
       $resultado->setMaxAge(30);
       $resultado->setPublic();
       return $resultado;
       
    }

     public function nuevos_red_lider_milAction($red, $inicio, $fin, $doce, $ciento, $padre)
    {
        $sql = "select * from  get_ganador_lider_red_mil(:red,:ini,:fin,:padre,:doce,:ciento)";

        $em = $this->getDoctrine()->getEntityManager();
    
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':ini'=>$inicio,':fin'=>$fin,':padre'=>$padre, ':doce'=>$doce,':ciento'=>$ciento));
 
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $resultado = new JsonResponse(array('aaData'=>$redes)); 
       $resultado->setMaxAge(60);
       $resultado->setPublic();
       return $resultado;
    }
    
    
    private function invierte_resultados($in)
    {
        $out = array();
        
        foreach ($in as $key => $value) {
            
            $out[$value['red']]= $value;
        }
        
        return $out;
    }
    
    private function union_resultados_ganar($red, $lideres, $ganados, $descartados, $lugar)
    {
        $out = array();
       
        
        $n = count($red);
        $m = count($lugar);
        
        if($m <$n)
        {
            for ($index = $m; $index < $n; $index++) {
                $t = array();
                $t['lugar']=NULL;
                $t['ganados']=NULL;
                
                $lugar[] = $t;
            }
            
        }
        
        $cont = 0;
        foreach ($red as $key => $value) {
           
            $temp = array();
            
            $temp['red'] = $value['red'];
            $temp['nombres']=$value['nombres'];
            
            $temp['lideres']=$lideres[$value['red']]['lideres'];
            $temp['meta'] = $temp['lideres']*7;
            
            $temp['ganados']=$ganados[$value['red']]['ganados'];
            $temp['descartados']= $descartados[$value['red']]['descartados'];
            
            $temp['lugar']=$lugar[$cont]['lugar'];
            $temp['lg']= $lugar[$cont]['ganados'];
            
            $out[] = $temp;
            $cont++;
        }
        
       
        if($m > $n)
        {
            for ($i = $n; $i < $m; $i++) {
                $temp = array();
            
            $temp['red'] = NULL;
            $temp['nombres']= NULL;
            
            $temp['lideres']=NULL;
            $temp['meta'] = NULL;
            
            $temp['ganados']= NULL;
            $temp['descartados']= NULL;
            
            $temp['lugar']=$lugar[$i]['lugar'];
            $temp['lg']= $lugar[$i]['ganados'];
            
            $out[] = $temp;
            }
        }
        return $out;
    }
    public function informe_semanal_dataAction($pastor, $inicio, $fin)
    {
     
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
        
        $em->beginTransaction();
       
        try{
            
               
            $sql1="select * from get_encargados_red_xpastor(:pastor)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':pastor'=>$pastor));
            $red_encargado_b = $smt1->fetchAll();
            $red_encargado = $this->invierte_resultados($red_encargado_b);
        
            $sql2="select * from get_lideres_red_xpastor(:pastor)";
            $smt2 = $em->getConnection()->prepare($sql2);
            $smt2->execute(array(':pastor'=>$pastor));
            $lideres_red_b = $smt2->fetchAll();
            $lideres_red = $this->invierte_resultados($lideres_red_b);

            $sql3="select * from get_ganados_red_xpastor(:pastor,:inicio,:fin)";
            $smt3 = $em->getConnection()->prepare($sql3);
            $smt3->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $ganados_red_b = $smt3->fetchAll();
            
            $ganados_red = $this->invierte_resultados($ganados_red_b);
        
            $sql4="select * from get_descartados_red_xpastor(:pastor,:inicio,:fin)";
            $smt4 = $em->getConnection()->prepare($sql4);
            $smt4->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $descartados_red_b = $smt4->fetchAll();
            
            $descartados_red = $this->invierte_resultados($descartados_red_b);
            
            $sql5 = " select * from get_ganados_xlugar(:pastor,:inicio,:fin)";
            $smt5 = $em->getConnection()->prepare($sql5);
            $smt5->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $lugar_red = $smt5->fetchAll();
           
           
            $todo = $this->union_resultados_ganar($red_encargado, $lideres_red, $ganados_red, $descartados_red,
                    $lugar_red);

            
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       $result = new JsonResponse(array('aaData'=>$todo)); 
       $result->setMaxAge(60);
       $result->setPublic();
       return $result;
    }
    
    
    public function listaconvertidos_ganador_redAction($red, $inicio, $fin)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();

        $em->beginTransaction();

        try {
            
            $sql = "select *from get_nuevos_convertidos_ganador_red_tiempo(:red, :inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':inicio'=>$inicio,':fin'=>$fin));
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $result = new JsonResponse(array('aaData'=>$redes));
        $result->setMaxAge(60);
        $result->setPublic();
        return $result;
    }

     public function listaconvertidos_ganador_tiempoAction($inicio, $fin)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();

        $em->beginTransaction();

        try {
            
            $sql = "select *from get_nuevos_convertidos_ganador_tiempo(:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':inicio'=>$inicio,':fin'=>$fin));
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $resultado = new JsonResponse(array('aaData'=>$redes));
        $resultado->setMaxAge(60);
        $resultado->setPublic();
        return $resultado;
    }

     public function listaconvertidos_ganador_red_sinAction($red, $inicio, $fin)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();

        $em->beginTransaction();

        try {
            
            $sql = "select *from get_nuevos_convertidos_ganador_red_tiempo_sin_consolidar(:red, :inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':inicio'=>$inicio,':fin'=>$fin));
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $result = new JsonResponse(array('aaData'=>$redes));
        $result->setMaxAge(60);
        $result->setPublic();

        return $result;
    }

     public function listaconvertidos_ganador_tiempo_sinAction($inicio, $fin)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();

        $em->beginTransaction();

        try {
            
            $sql = "select *from get_nuevos_convertidos_ganador_tiempo_sin_consolidar(:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':inicio'=>$inicio,':fin'=>$fin));
            $redes = $smt->fetchAll();
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $result = new JsonResponse(array('aaData'=>$redes));
        $result->setMaxAge(60);
        $result->setPublic();
        return $result;
    }

 }

