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
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
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
            
            $em->commit();
        }  catch (Exception $e)
        {
            $em->rollback();
            $em->close();
            throw  $e;
        }
    
        return new JsonResponse($redes); 
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
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
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
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
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
            $em->commit();
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);         
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
                    lati as provincia,
                    longi as "long" from ver_distrito(:dep,:prov)';
             $smt = $em->getConnection()->prepare($sql);
             $smt->execute(array(':dep'=>$dep,':prov'=>$prov));
 
             $redes = $smt->fetchAll();
             
             $em->commit();
        
         } catch (Exception $exc) {
             $em->rollback();
             $em->close();
             throw $exc;
         }

       return new JsonResponse($redes);
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
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        return new JsonResponse(array('aaData'=>$redes));
    }
    
    public function personaAction($id)
    {        
        $sql_persona = "select * from get_persona(:id)";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql_persona);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }
    
    public function ubigeoAction($id)
    {
        $sql = 'select * from ubigeo where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
       
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
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       return new JsonResponse($redes);
    }
    
    function getTablaGanarFechaAction($fecha1,$fecha2){
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	
    	$fecha1_formateada = date("Y-m-d", strtotime($fecha1));
    	$fecha2_formateada = date("Y-m-d", strtotime($fecha2));
    	 
    	$sql = "SELECT 
				CONCAT(lcel.nombre,' ',lcel.apellidos) as lider_red , cel.id as celula, 
				CONCAT(pnc.nombre,' ', pnc.apellidos) as nuevo_convertido, nc.fecha_conversion, 
				CONCAT(pcdor.nombre,' ',pcdor.apellidos) as consolidador, l.nombre as lugar
				FROM nuevo_convertido nc
				inner join lugar l on (l.id = nc.id_lugar)
				inner join celula cel on(nc.id = nc.id_celula)
				inner join consolida c on (nc.id= c.id_nuevo_convertido)
				inner join consolidador cdor on (c.id_consolidador = cdor.id)
				inner join persona pcdor on (pcdor.id = cdor.id)
				inner join persona pnc on (nc.id = pnc.id)
				inner join persona lcel on (lcel.id = cel.id_lider_red)
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
    	return new Response($result);
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
                                        <th style='width: 8%;'>CODIGO</th>
					<th style='width: 22%;'>DOCE DEL PASTOR</th>
					<th style='width: 10%;'>N° ALMAS</th>
					</tr>
					</thead>
					<tbody>";
        
        try{
            
            $em->beginTransaction();
            
            $sql = "select * from get_reporte_ganar(:tipo,:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>$tipo,':inicio'=>$fecha1_formateada,':fin'=>$fecha2_formateada));
    
            $todo = $smt->fetchAll();
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
    	return new Response($result);
        
    }
    
    public function  getTablaLugarGanadosAction($nombre, $fecha1, $fecha2)
    {
           $em = $this->getDoctrine()->getEntityManager();
    	
    	
    	$fecha1_formateada = date("Y-m-d", strtotime($fecha1));
    	$fecha2_formateada = date("Y-m-d", strtotime($fecha2));
    	 
    	
    
    	$result = "<table id='".$nombre."' name='".$nombre."' class='table table-striped table-bordered'>
					<thead>
					<tr>
                                        <th style='width: 28%;'>NOMBRE</th>
					<th style='width: 20%;'>VARONES</th>
					<th style='width: 20%;'>MUJERES</th>
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
            
            //hombres
            $sql = "select * from get_reporte_lugar_ganar(:tipo,:inicio,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':tipo'=>1,':inicio'=>$fecha1_formateada,':fin'=>$fecha2_formateada));
            $todo_h = $smt->fetchAll();
            
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
    	return new Response($result);
    }
}

