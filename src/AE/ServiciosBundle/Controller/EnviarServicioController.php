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

class EnviarServicioController extends Controller
{
    public function getListaRedAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $sql = "select * from lista_redes";
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        $em->clear();
        
        $n = count($todo);
        
        $cadena = "";
       
        for($i=0; $i<$n; $i++)
        {
           $temp = "<option  value='". $todo[$i]['id']."' >";
           
           $temp = $temp." ".$todo[$i]['id']."-";
           $temp = $temp." ".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";

           $cadena = $cadena.$temp;
        }
        
        return new Response("<select id='redes_select_option'>".$cadena."</select>");
        
    }
    //por eliminar este codigo
    public function getListaCelulaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $red = $request->request->get('red');
        $tipo = $request->request->get('celula');
        
        $datos = array();

        parse_str($name,$datos);

       if($name!=NULL){
           
            $em = $this->getDoctrine()->getEntityManager();
       }
        return new Response();
       
    }
    public function   getLiderRedCellAction()
    {
       $request = $this->get('request');
       
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       try
       {
          $sql = "select *from  ver_lideres_red_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $em->clear();
          $total = "";
          
          $n = count($todo);
          
          foreach ($todo as $value) 
          {
              $linea = "<option value='".$value['id']."'>";
                $linea = $linea.$value['id']."-".$value['nombre']." ".$value['apellidos']."</option>";
                $total = $total.$linea;
          }
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($total);
    }
  
    public function getLiderMisioneroCellAction()
    {
       $request = $this->get('request');
       
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       try
       {
          $sql = "select *from  ver_misionero_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $em->clear();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option value='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           
           throw  $e;
       }
       
       return new Response($total);
    }
    
    public function getLiderCellAction()
    {
         $request = $this->get('request');
       
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       try
       {
          $sql = "select *from  ver_lideres_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $em->clear();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option value='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           
           throw  $e;
       }
       
       return new Response($total);
    }
    public function getLiderPastorEjeCellAction()
    {
       $request = $this->get('request');
       
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       try
       {
          $sql = "select *from  ver_pastor_eje_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          
          $todo = $smt->fetchAll();
          $em->clear();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option value='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($total);
    }
    
    public function getListaCelulaTablaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            
            $sql = "select * from lista_celula_lider_red_act";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $lider_red = $smt->fetchAll();
            $em->clear();
            
            $sql = "select * from lista_celula_misionero_act";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute();
            $misionero = $smt1->fetchAll();
            $em->clear();
            
            $sql = "select * from lista_celula_pastor_eje_act";
            $smt2 = $em->getConnection()->prepare($sql);
            $smt2->execute();
            $pastor = $smt2->fetchAll();
            $em->clear();
            
            $todo = $lider_red;
            foreach ($misionero as $key => $value) {
                $todo[]=$value;                
            }
            
            foreach ($pastor as $key => $value) {
                $todo[]=$value;
            }
            
            $sql = "select * from lista_celula_lider_act";
            $smt3 = $em->getConnection()->prepare($sql);
            $smt3->execute();
            $lideres = $smt3->fetchAll();
            $em->clear();
            
            foreach ($lideres as $key => $value) {
                $todo[]=$value;
            }
           
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
     public function getListaCelulaTabla_redAction($red, $tipo)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            
            $sql = "select * from get_celula_lider_red(:red,:tip)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':tip'=>$tipo));
            $lider_red = $smt->fetchAll();
            $em->clear();
            
            $sql = "select * from get_celula_misionero(:red,:tip)";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute(array(':red'=>$red,':tip'=>$tipo));
            $misionero = $smt1->fetchAll();
            $em->clear();
            
            $sql = "select * from get_celula_pastor_eje(:red,:tip)";
            $smt2 = $em->getConnection()->prepare($sql);
            $smt2->execute(array(':red'=>$red,':tip'=>$tipo));
            $pastor = $smt2->fetchAll();
            $em->clear();
            
            $todo = $lider_red;
            foreach ($misionero as $key => $value) {
                $todo[]=$value;                
            }
            
            foreach ($pastor as $key => $value) {
                $todo[]=$value;
            }
            
            $sql = "select * from get_celula_lider(:red,:tip)";
            $smt3 = $em->getConnection()->prepare($sql);
            $smt3->execute(array(':red'=>$red,':tip'=>$tipo));
            
            $lideres = $smt3->fetchAll();
            $em->clear();
            
            foreach ($lideres as $key => $value) {
                $todo[]=$value;
            }
           
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
    
    public function getListaCelulaLider_redAction($red)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            
            $sql = "select * from  info_celula_doce_red(:red)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red));
            $todo = $smt->fetchAll();
           
            $em->commit();
            $em->clear();

        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
    
    public function getListaCelulaLider12_redAction($red,$lider)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            
            $sql = "select * from  info_celula_doce_red_lider(:red,:lider)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':lider'=>$lider));
            $todo = $smt->fetchAll();
           
            $em->commit();
            $em->clear();

        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
    public function getListaCelulaTablaDesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            
            $sql = "select * from lista_celula_lider_red_des";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $lider_red = $smt->fetchAll();
            $em->clear();
            
            $sql = "select * from lista_celula_misionero_des";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute();
            $misionero = $smt1->fetchAll();
            $em->clear();
            
            $sql = "select * from lista_celula_pastor_eje_des";
            $smt2 = $em->getConnection()->prepare($sql);
            $smt2->execute();
            $pastor = $smt2->fetchAll();
            $em->clear();
            
            $todo = $lider_red;
            foreach ($misionero as $key => $value) {
                $todo[]=$value;                
            }
            
            foreach ($pastor as $key => $value) {
                $todo[]=$value;
            }
            
            $sql = "select * from lista_celula_lider_des";
            $smt3 = $em->getConnection()->prepare($sql);
            $smt3->execute();
            $lideres = $smt3->fetchAll();
            $em->clear();
            
            foreach ($lideres as $key => $value) {
                $todo[]=$value;
            }
           
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
    public function getListaCelulaRedTipoAction($red, $tipo)
    {
       $securityContext = $this->get('security.context');

       
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       try
       {
           
         if($securityContext->isGranted('ROLE_LIDER_RED'))
         {
          $sql = "select *from  celulas_por_red(:red,:tip)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':red'=>$red,':tip'=>$tipo));
          
         }
         else
         {
             if($securityContext->isGranted('ROLE_LIDERSIN'))
             {
                    $persona = $securityContext->getToken()->getUser()->getIdPersona();
                    
                    if($persona==NULL)
                    {
                        $sql = "select *from  celulas_por_red(:red,:tip)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$red,':tip'=>$tipo));
                    }
                    else
                    {
                        $sql = "select *from  celulas_por_red_lider(:red,:tip,:lider)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$red,':tip'=>$tipo,':lider'=>$persona->getId()));
                    }

             }
         }
          $todo = $smt->fetchAll();
          $em->clear();
          
          $result = "<table cellpadding='0' cellspacing='0' border='0' class='display table table-striped dataTable table-bordered'>
			<tbody>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Apellido</th>
					
					<th>Fecha Inicio</th>
					<th>Tipo</th>
                                        <th>Dia </th>
                                        <th>Hora </th>
                                        <th>Ver</th>
				</tr>";
		
		foreach ($todo as $key => $val){
			$result =$result."
					<tr>
					<td>".$val['id']."</td>
					<td>".$val['nombre']."</td>
					<td>".$val['apellidos']."</td>
					<td>".$val['fecha_creacion']."</td>
					<td>".(($val['tipo']==0)?'Evangelistica':'Discipulado')."</td>
                                        <td>".$val['dia']."</td>
					<td>".$val['hora']."</td>
					<td><input type='button' id='ver' class='button_ver' data='".$val["id"]."' onclick='IrATema(this)' value='Ver'></td>
					</tr>
					";
		}
		$result = $result."</table>";
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($result);
    }
    
    public  function getListaCelulaTemasAction($id)
    {
           
       $em = $this->getDoctrine()->getEntityManager();
 
       try
       {
          $em->beginTransaction();
          
          $sql = "select *from  temas_celula(:id)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':id'=>$id));
          
          $todo = $smt->fetchAll();
          $em->clear();
          
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new JsonResponse(array('aaData'=>$todo));
    }
    
    public function getAsistenciaCelulaClassAction($id,$tipo)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->beginTransaction();

       try
       {
          
          $sql = "select *from  asistencia_tema(:id,:tipo)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':id'=>$id,':tipo'=>$tipo));
          
          $todo = $smt->fetchAll();
          $em->clear();
          
           $result = "<table cellpadding='0' cellspacing='0' border='0' class='display table table-striped dataTable table-bordered' id='persona' name='persona'>
			<tbody>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Asistencia</th>
					
				</tr>";
		
		foreach ($todo as $key => $val){
			$result =$result."
					<tr>
					<td>".$val['id_miembro']." <input type='hidden' name='miembro".$key."' id='miembro".$key."' value='".$val['id_miembro']."' ></td>
					<td>".$val['nombre']."</td>
					<td>".$val['apellidos']."</td>
                                        <td class='check_ast' data='check".$key."'><input type='checkbox' name='check".$key."' id='check".$key."' value='".$key."'".(($val['estado']==TRUE)?"checked='true'":""). "></td>
					</tr>
					";
		}
		$result = $result."</table>";
          
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($result);
    }
  
   public function temas_celulaAction()
   {
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
     
       try
       {
           $sql = "select *from lista_tema_celula";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           $em->clear();
           
           $em->commit();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
     public function contar_clase_celulaAction()
   {
      $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();

       
       try
       {
           $sql = "select *from lista_tema_celula";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $temp = $smt->fetchAll();
           $em->clear();
           
           $n = count($temp);
           
           for($i=0; $i<$n; $i++)
           {
               $sql1 = "select count(*) as num from clase_cell c where c.id_tema_celula=:id";
               $smt1 = $em->getConnection()->prepare($sql1);
               $smt1->execute(array(':id'=>$temp[$i]['id']));
               $total = $smt1->fetch();
               
               $fila = array();
               
               $fila['id'] = $temp[$i]['id'];
               $fila['titulo'] = $temp[$i]['titulo'];
               $fila['fecha'] = $temp[$i]['fecha'];
               $fila['autor'] = $temp[$i]['autor'];
               $fila['tipo'] = $temp[$i]['tipo'];
               $fila['enviado'] = $total['num'];


               $todo[] = $fila;
           }
           $this->getDoctrine()->getEntityManager()->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));  
   }
   
   public function serv_enviar_lista_celulasAction()
   {
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();

       
       try
       {
           $sql = "select *from lista_celulas";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           $em->clear();
 
           $em->commit();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse($todo);  
   }
       
    public function getLideresCelulaTablaAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            //misioneros
            $sql = "select * from ver_lideres_celulas_final(:id,:caso)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id,':caso'=>0));
            $lider_red = $smt->fetchAll();
            $em->clear();
            
            //pastores ejecutivos
            $sql = "select * from ver_lideres_celulas_final(:id,:caso)";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute(array(':id'=>$id,':caso'=>1));
            $misionero = $smt1->fetchAll();
            $em->clear();
            
            //lider de red
            $sql = "select * from ver_lideres_celulas_final(:id,:caso)";
            $smt2 = $em->getConnection()->prepare($sql);
            $smt2->execute(array(':id'=>$id,':caso'=>2));
            $pastor = $smt2->fetchAll();
            $em->clear();
            
            $todo = $lider_red;
            foreach ($misionero as $key => $value) {
                $todo[]=$value;                
            }
            
            foreach ($pastor as $key => $value) {
                $todo[]=$value;
            }
            //lider
            $sql1 = "select * from ver_lideres_celulas_final(:id,:caso)";
            $smt3 = $em->getConnection()->prepare($sql1);
            $smt3->execute(array(':id'=>$id,':caso'=>'3'));
            $lideres = $smt3->fetchAll();
            $em->clear();
            
            foreach ($lideres as $key => $value) {
                $todo[]=$value;
            }
           
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo));
    }
    
    public function getCelulasDiscipuladoAction($red)
    {
             $em = $this->getDoctrine()->getEntityManager();
        
        try {
            
            $em->beginTransaction();
            
            $sql = 'select * from celulas_por_red(:red,:tip) ';
       
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':tip'=>1));
 
            $redes = $smt->fetchAll();
            $em->clear();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
    }
    
    public function getInfoCelulaAction($id, $ini)
    {
        
        $finD = new \DateTime($ini);
        $finD->modify('+14 weeks');
        $fin = $finD->format('Y-m-d');
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        
        $em->beginTransaction();
        
        try {            
            
            $sql = 'select * from info_celula(:id) ';
       
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $todo = $smt->fetch();
            
            $em->clear();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        $result = "               
      <div class='box-head tabs'>
            <h3>Datos de Líder de Célula</h3>	
      </div>
      <table class='table table-striped table-bordered'>
      	<tr>
        		<td>Nombres: ".$todo['nombre']." ".$todo['apellidos']."</td>
        		<td>Código Líder : ".$todo['lider_id']."</td>
        		<td>Red : ".$todo['red']. "</td>
        </tr>
        <tr>
        		<td>Sexo: ".((strval($todo['sexo'])=='1')?'F':'M')."</td>
        		<td>Edad: ".$todo['edad']."</td>
        		<td>Teléfono: ".(strlen($todo['lider_cell'])>0?$todo['lider_cell']:$todo['lider_tel'])."</td>
        </tr>
       </table>
       <div class='box-head tabs'>
            <h3>Datos de Célula</h3>	
      </div>
      <table class='table table-striped table-bordered'>
        <tr>
                        <td>Codigo Célula: ".$todo['cid']."</td>
        		<td colspan='2'>Dirección: ".$todo['direccion']."</td>
        		<td>Tel. Anfitrión: ".$todo['ctel']."</td>
        		<td>Familia Anfitriona: ".$todo['familia']."</td>
        </tr>
        <tr>
        		<td>Apertura: ". $todo['fecha_creacion']."</td>
        		<td>Tipo: ". ((strval($todo['ctipo'])=='0')?'Evangelistica':'Discipulado')."</td>
        		<td>Fecha Inicio: ".$ini." </td>
        		<td>Dia: ".$todo['dia']."</td>
                        <td>Hora: ".$todo['hora']."</td>

        </tr>
	</table>";

       return new Response($result);
        
    }
    
    public function convertHash($todo)
    {
        $final = array();
        foreach ($todo as $key => $value) {
            $final[$value['idp']] = $value;
        }
        return $final;
    }

    public function searchEvento($todo,  $id)
    {
        $salida = array();
        $salida['E'] = FALSE;
        $salida['B'] = FALSE;
        
        foreach ($todo as $key => $value) {
            if($value['idp'] !=NULL)
            {
               if( $value['tipo']==0)
               {
                   $salida['E'] =TRUE;
               }
               else $salida['B']=TRUE;
            }
        }
        
        return $salida;
    }

    public function getAsistenciaCelulaAction($id,$inicio)
    {
        $finD = new \DateTime($inicio);
        $finD = $finD->modify('+14 weeks');
        
        $fin = $finD->format('Y-m-d');  
        
        $ini = new \DateTime($inicio);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->beginTransaction();
        
        $num = 15;
        
        try {            
            
            $sql = 'select * from info_asistencia(:id,:ini,:fin)';
       
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id,':ini'=>$inicio,':fin'=>$fin));
 
            $redes = $smt->fetchAll();
                           
            $em->clear();
            
            //lista de cursos
            
            $sql0 = 'select * from lista_cursos';
            $smt0 = $em->getConnection()->prepare($sql0);
            $smt0->execute();
            $cursos = $smt0->fetchAll();
            $em->clear();
            
            //encuentro bautismo
            $sql1 = 'select * from info_enc_bau(:id)';
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':id'=>$id));
            $encbau = $smt1->fetchAll();
                        
            $em->clear();
            
            
            //leche espiritual
            
            $sql2 = 'select * from info_leche_espiritual(:id)';
            $smt2 = $em->getConnection()->prepare($sql2);
            $smt2->execute(array(':id'=>$id));
            $lcesp= $smt2->fetchAll();
            $lcespF = $this->convertHash($lcesp);
            
            
            //fecha dicto celula
            
            $sql3 = " select * from info_fecha_celula(:cel)";
            $smt3 = $em->getConnection()->prepare($sql3);
            $smt3->execute(array(':cel'=>$id));
            $celulas = $smt3->fetchAll();
            $em->clear();
            
            
            
            $todo = array();
                      
            if(count($redes)>0)
                $id_ini = $redes[0]['id'];
            else $id_ini = NULL;
            
            $id_old = NULL;
            
            $fila = NULL;
                    
            $ind = 0;
            
            foreach ($redes as $key => $value) {
                
                $id_ini = $value['id'];
                
                if($id_ini!=$id_old)
                {                    
                    if(count($fila)>0)
                        $todo[]=$fila;
                    
                    $fila = array();
                    $fila['id'] = $id_ini;
                    $fila['nombres'] = $value['nombre'].' '.$value['apellidos'];
                     
                    $fila['telefono'] = (strlen($value['celular'])==0)?$value['telefono']:$value['celular'];
                    $fila['edad'] = $value['edad'];
                    
                    //$fila['dicto'] = $value['fecha_dicto'];
                    
                    for ($index = 0; $index < $num; $index++) {
                        $fila[$index]=NULL;
                    }
                    
                    $ind = 0;
                    $fila[$ind] = ($value['estado']==TRUE)?TRUE:FALSE;                    
                            
                    //leche espiritual
                    
                    $fila['L']=NULL;
                    
                    if(count($lcespF[$id_ini])>0)
                    {
                        $fila['L'] = TRUE;
                    }
                            
                    //eventos
                    $fila['E']=NULL;
                    $fila['B']=NULL;
                    
                    $eventos = $this->searchEvento($encbau,$id_ini);
                    $fila['E'] = $eventos['E'];
                    $fila['B'] = $eventos['B'];
                    
                    
                    foreach ($cursos as $key => $value) {
                        $fila[$value['titulo']]=NULL;
                    }
                    
                    $ind++;
                }
                else
                {
                    $fila[$ind] = ($value['estado']==TRUE)?TRUE:-1;
                    
                    $ind++;
                }
                $id_old = $id_ini;
            }
            if(count($fila)>0)
            {
                $todo[] = $fila;
            }
            
            $em->clear();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }        
        
      //convert to html 
        
        $result = "";
        
        $num_cursos = count($cursos) + 3;      
       
        $result = $result."<div class='box-head tabs'>
            <h3>Asistencia</h3>	
                </div>
        <table class='table table-striped table-bordered'>
	<tr>
		<th rowspan='2'>Nro.</th>
		<th rowspan='2'>Nombre y Apellidos</th>
		<th rowspan='2'>Teléfono</th>
		<th rowspan='2'>Edad</th>
		<th colspan='15'>Asistencia Semanal</th>
		<th colspan='".strval($num_cursos)."'>Crecimiento Espiritual</th>
	</tr>
	<tr>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
        <th>Leche <br> Espiritual</th>
		<th>Enc.</th>
		<th>Bautismo</th>";
               
        $tempC = "";
        foreach ($cursos as $key => $value) {
            $tempC = $tempC."<th>". substr($value['titulo'], 0, 3)."<br>". substr($value['titulo'], 4,-1). "</th>";
        }

        $result = $result.$tempC."
		
	</tr>";
        
        if(count($todo)>0)
        {
        
         foreach ($todo as $row) {
            
            $element = "<tr>";
            foreach ($row as $value) {                
                
                $element = $element."<td>";
                
                if($value!=NULL)
                {
                    if($value==TRUE && strlen($value)==1)
                      $element= $element."&#10004; ";
                    else if($value== -1)
                         $element=$element."x";
                        else
                        $element = $element.$value;
                }
                
                $element = $element."</td>";         
            }
            
            $element = $element."</tr>";
            
            $result = $result.$element;
        }
        
        $cant = count($celulas);
        
         $last = "
            <tr> <td colspan='4'></td>";
                
        for($i=0; $i<$cant; $i++)
        {
            $fecha = new \DateTime($celulas[$i]['fecha_dicto']);
            $last = $last."<td>".$fecha->format('d/m')."</td>";
        }
       
        for($i=$cant; $i<20; $i++)
        {
            $last = $last."<td></td>";
        }
        
        $last = $last."</tr>";
        
        $result = $result.$last
                ."</table>";
    
       }
       
       return new Response($result);
    }
    
    public function getOfrendaCelulaAction($id, $inicio)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $finD = new \DateTime($inicio);
        $finD->modify('+14 weeks');
        $fin = $finD->format('Y-m-d');
        $todo = array();
        
        $em->beginTransaction();
        try {
            $sql = "select * from info_ofrenda(:id,:ini,:fin)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id,':ini'=>$inicio,':fin'=>$fin));
            $todo = $smt->fetchAll();
            
            $em->commit();
        } catch (Exception $exc) {
            
            $em->rollback();
            $em->close();
            
            throw $exc;
        }
        
        $num = count($todo);
        //primer mes
        
        $primer = "<div class='box-head tabs'>
            <h3>Ofrendas</h3>	
                </div>
        <table class='table table-striped table-bordered'> <tr> <th width=50%> Fecha </th> <th width=50%>Ofrenda  </th> </tr>";
        $temp = "";
        for ($index = 0; $index < 5; $index++) {
            if($index<$num )
            { 
           
            $temp = $temp. "<tr> <td>".$todo[$index]['fecha_asignado']."</td><td>";
            $temp = $temp. $todo[$index]['ofrenda']."</td> </tr>";
            }
            else $temp = $temp."<tr><td>  </td><td>  </td></tr>";
        }
        
        $primer = $primer.$temp."</table>";
        //segundo mes
        $segundo = "<table class='table table-striped table-bordered'> 
        		<tr> 
        			<th width=50%> Fecha </th> 
        			<th width=50%>Ofrenda  </th> 
        		</tr>";
        $temp = "";
        for ($index = 5; $index < 10; $index++) {
            if($index<$num )
            {
            $temp = $temp. "<tr> <td>".$todo[$index]['fecha_asignado']."</td><td>";
            $temp = $temp. $todo[$index]['ofrenda']."</td> </tr>";
            }
            else $temp = $temp."<tr><td>  </td><td>  </td></tr>";

        }
        $segundo = $segundo.$temp."</table>";
        
        //tercer
        $tercer = "<table class='table table-striped table-bordered'> 
        		<tr> 
        			<th width=50%> Fecha </th> 
        			<th width=50%>Ofrenda  </th> 
        		</tr>";
        $temp = "";
        for ($index = 5; $index < 10; $index++) {
            
            if($index<$num)
            {//    break;
            $temp = $temp. "<tr> <td>".$todo[$index]['fecha_asignado']."</td><td>";
            $temp = $temp. $todo[$index]['ofrenda']."</td> </tr>";
            }
            else $temp = $temp."<tr><td>  </td><td>  </td></tr>";

        }
        $tercer = $tercer.$temp."</table>";
        
        $result = $primer.$segundo.$tercer;
        
        return new Response($result);
        
    }
    
      public function invierte_resultados($in)
    {
        $out = array();
        
        foreach ($in as $key => $value) {
            
            $out[$value['red']]= $value;
        }
        
        return $out;
    }
    
    //uniones de los resultados de las consultadas separadas
    
    public function union_resultados_enviar(            
            $red,//$lideres,
            $doce,  $ciento, $mil, $celulas,
            $asistencia, $tumpis, $nuevos, $nueva_cell,$ofrenda            )
    {
        $out = array();
       
        
        $n = count($red);
        
        $cont = 0;
        
        $n = count($red);
         $totales = array();
        $totales['red']='';
        $totales['nombres']='TOTALES';
        //$totales['lideres']=0;
        $totales['doce']=0;
        $totales['ciento']=0;
        $totales['mil']=0;
        $totales['celulas']=0;
        $totales['asistencia_celula']=0;
        $totales['informaron']=0;
        $totales['tumpis'] =0;
        $totales['nuevos']=0;
        $totales['nueva_cell']=0;
        $totales['ofrenda']=0;
       
        
        foreach ($red as $key => $value) {
           
            $temp = array();
            
            $temp['red'] = $value['red'];
            $temp['nombres']=$value['nombres'];
            
            //$temp['lideres']=$lideres[$value['red']]['lideres'];
            //$totales['lideres']+=$temp['lideres'];
            
            $temp['doce'] = $doce[$value['red']]['lideres'];            
            $totales['doce']+=$temp['doce'];
            
            $temp['ciento'] = $ciento[$value['red']]['lideres'];            
            $totales['ciento']+=$temp['ciento'];
            
            $temp['mil'] = $mil[$value['red']]['lideres'];
            $totales['mil']+=$temp['mil'];
            
            $temp['celulas'] = $celulas[$value['red']]['celulas'];    
            $totales['celulas']+=$temp['celulas'];
            
            $temp['asistencia_celula'] = $asistencia[$value['red']]['asistencia'];            
            $totales['asistencia_celula']+=$temp['asistencia_celula'];
            
            $temp['informaron'] = $asistencia[$value['red']]['informaron'];            
            $totales['informaron']+=$temp['informaron'];
            
            $tump =$tumpis[$value['red']]['asistencia'];
            $temp['tumpis'] = ($tump==NULL)?0:$tump;            
            $totales['tumpis']+=$temp['tumpis'];
            
            $temp['nuevos'] = $nuevos[$value['red']]['nuevos'];
            $totales['nuevos']+= $temp['nuevos'];
            
            $temp['nueva_cell'] =$nueva_cell[$value['red']]['nuevas'];
            $totales['nueva_cell']+=$temp['nueva_cell'];
            
            $ofren = $ofrenda[$value['red']]['ofrenda'];
            
            $temp['ofrenda'] = ($ofren==NULL)?0.0:$ofren;
            $totales['ofrenda'] = $totales['ofrenda'] + $temp['ofrenda'];
            
            $out[] = $temp;
        }
        
        $resumen = $totales;
       
       $resumen['nombres']='RESUMEN';
       
        $out[]=$totales;
        //$out[]=$resumen;
        
        
        
        return $out;
    }
    
       public function informe_semanal_enviar_pastorAction($pastor, $inicio, $fin)
    {
     
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
   
        $begin = new \DateTime($inicio);
        $end = new \DateTime($fin);
        
        $init  = new \DateTime();
        $init->setDate($begin->format('Y'),'01','01');
        
        $em->beginTransaction();
       
        try{
            
               
            $sql1="select * from get_encargados_red_xpastor(:pastor)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':pastor'=>$pastor));
            $red_encargado_b = $smt1->fetchAll();
            $red_encargado = $this->invierte_resultados($red_encargado_b);
        /*
            $sql2="select * from get_lideres_red_xpastor(:pastor)";
            $smt2 = $em->getConnection()->prepare($sql2);
            $smt2->execute(array(':pastor'=>$pastor));
            $lideres_red_b = $smt2->fetchAll();
            $lideres_red = $this->invierte_resultados($lideres_red_b);
*/
            //sus 12
            $sql3="select * from get_lideres_xpastor(:pastor,1)";
            $smt3 = $em->getConnection()->prepare($sql3);
            $smt3->execute(array(':pastor'=>$pastor));
            $doce_b = $smt3->fetchAll();
            
            $doce = $this->invierte_resultados($doce_b);
        
            //sus 144
            $sql4=" select * from  get_lideres_xpastor(:pastor,12)";
            $smt4 = $em->getConnection()->prepare($sql4);
            $smt4->execute(array(':pastor'=>$pastor));
            $ciento_b = $smt4->fetchAll();
            
            $ciento = $this->invierte_resultados($ciento_b);
            
            //sus 1278
            $sql7=" select * from  get_lideres_xpastor(:pastor,144)";
            $smt7 = $em->getConnection()->prepare($sql7);
            $smt7->execute(array(':pastor'=>$pastor));
            $mil_b = $smt7->fetchAll();
            
            $mil = $this->invierte_resultados($mil_b);
       
            //celulas
            
            $sql5 = " select * from get_celulas_xpastor(:pastor,:fin)";
            $smt5 = $em->getConnection()->prepare($sql5);
            $smt5->execute(array(':pastor'=>$pastor,':fin'=>$end->format('Y-m-d')));
            $celulas_b= $smt5->fetchAll();
            $celulas = $this->invierte_resultados($celulas_b);
            
            
            //asistencia celula
            
           $sql8 = "select * from get_asistencia_celula_total_xpastor(:pastor,:inicio,:fin)";
           $smt8 = $em->getConnection()->prepare($sql8);
           $smt8->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $asistencia_b = $smt8->fetchAll();
           $asistencia = $this->invierte_resultados($asistencia_b);
           
           
           //asistencia al tumpis
           $sql9 = "select * from get_asistencia_culto_xpastor(:pastor, :inicio,:fin)";
           $smt9 = $em->getConnection()->prepare($sql9);
           $smt9->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $tumpis_b = $smt9->fetchAll();
           $tumpis = $this->invierte_resultados($tumpis_b);
           
           
           //nuevos convertidos
           
           $sql10 = "select * from get_nuevo_conv_xpastor(:pastor,:inicio, :fin)";
           $smt10 = $em->getConnection()->prepare($sql10);
           $smt10->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $nuevos_b = $smt10->fetchAll();
           $nuevos = $this->invierte_resultados($nuevos_b);
           
           //nuevas celulas
           
           $sql11 = "select * from get_nuevas_celula_xpastor(:pastor,:inicio,:fin)";
           $smt11 = $em->getConnection()->prepare($sql11);
           $smt11->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $nueva_cell_b = $smt11->fetchAll();
           $nueva_cell = $this->invierte_resultados($nueva_cell_b);
           
           
            //ofrenda
            
            $sql6 = " select * from get_ofrenda_celula_xpastor(:pastor, :inicio, :fin)";
            $smt6 = $em->getConnection()->prepare($sql6);
            $smt6->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $ofrenda_b = $smt6->fetchAll();
            $ofrenda = $this->invierte_resultados($ofrenda_b);

            $todo = $this->union_resultados_enviar($red_encargado, 
//$lideres_red,
                    $doce, $ciento,$mil,$celulas, $asistencia,$tumpis,$nuevos,$nueva_cell, $ofrenda);

            
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
        
         $result = "<table cellpadding='0' cellspacing='0' border='0' class='display table table-striped dataTable table-bordered'>
			<thead>
				<tr>
					<th>RED</th>
					<th>12 DEL PASTOR</th>
					<th>LIDERES <br> DE 12</th>
					<th>LIDERES <br> DE 144</th>
					<th>LIDERES <br> DE 1278</th>
                                        <th>CÉLULAS</th>
                                        <th>INFORMARON</th>
                                        <th>ASISTENCIA <br> CÉLULAS</th>
                                        <th>ASISTENCIA <br> TUMPIS</th>
                                        
					<th>NUEVOS<br>CONVERTIDOS</th>
                                        <th>NUEVAS <br> CÉLULAS </th>
                                        <th>OFRENDA </th>
				</tr>
                        </thead> <tbody>    
                            ";
        
        foreach ($todo as $key => $row) {
            
            $linea = "<tr>";
            foreach ($row as $value) {
                $linea= $linea."<td>".$value."</td>";
            }
            $linea = $linea."</tr>";
            
            $result = $result.$linea;
        }
        
        $final = "<tr>
						<th colspan='2'>RESUMEN</th>
						<th colspan='3'>TOTAL DE LIDERES</th>
						 <th colspan='1'>CÉLULAS</td>
                                                 <th>INFORMARON </th>
                                                <th>ASISTENCIA <br> CÉLULAS</th>
                                                <th>ASISTENCIA <br> TUMPIS</th>
                                        
                                                <th>NUEVOS<br>CONVERTIDOS</th>
                                                <th>NUEVAS <br> CÉLULAS </th>
                                                <th>OFRENDA </th>
					</tr>
                <tr>
                <td colspan='2'> </td>
                <td colspan='3'>
                ";
        
        //</tfoot>";
        $ultimo = end($todo);

        $final = $final.($ultimo['doce']+$ultimo['ciento']+$ultimo['mil'])."</td><td colspan='1'> ".$ultimo['celulas'].
                 "</td><td>".$ultimo['asistencia_celula']."</td><td>".$ultimo['informaron']."</td><td>".$ultimo['tumpis']."</td><td>".$ultimo['nuevos']."</td><td>".$ultimo['nueva_cell'].
               "</td><td>".$ultimo['ofrenda']."</td> </tr> ";
        
            
        
        $result = $result."</tbody>".$final;
        
       return new Response($result); 
    }


    public function informe_semanal_enviar($pastor, $inicio, $fin)
    {
     
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
   
        $begin = new \DateTime($inicio);
        $end = new \DateTime($fin);
        
        $init  = new \DateTime();
        $init->setDate($begin->format('Y'),'01','01');
        
        $em->beginTransaction();
       
        try{
            
               
            $sql1="select * from get_encargados_red_xpastor(:pastor)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':pastor'=>$pastor));
            $red_encargado_b = $smt1->fetchAll();
            $red_encargado = $this->invierte_resultados($red_encargado_b);
        /*
            $sql2="select * from get_lideres_red_xpastor(:pastor)";
            $smt2 = $em->getConnection()->prepare($sql2);
            $smt2->execute(array(':pastor'=>$pastor));
            $lideres_red_b = $smt2->fetchAll();
            $lideres_red = $this->invierte_resultados($lideres_red_b);
*/
            //sus 12
            $sql3="select * from get_lideres_xpastor(:pastor,1)";
            $smt3 = $em->getConnection()->prepare($sql3);
            $smt3->execute(array(':pastor'=>$pastor));
            $doce_b = $smt3->fetchAll();
            
            $doce = $this->invierte_resultados($doce_b);
        
            //sus 144
            $sql4=" select * from  get_lideres_xpastor(:pastor,12)";
            $smt4 = $em->getConnection()->prepare($sql4);
            $smt4->execute(array(':pastor'=>$pastor));
            $ciento_b = $smt4->fetchAll();
            
            $ciento = $this->invierte_resultados($ciento_b);
            
            //sus 1278
            $sql7=" select * from  get_lideres_xpastor(:pastor,144)";
            $smt7 = $em->getConnection()->prepare($sql7);
            $smt7->execute(array(':pastor'=>$pastor));
            $mil_b = $smt7->fetchAll();
            
            $mil = $this->invierte_resultados($mil_b);
       
            //celulas
            
            $sql5 = " select * from get_celulas_xpastor(:pastor,:fin)";
            $smt5 = $em->getConnection()->prepare($sql5);
            $smt5->execute(array(':pastor'=>$pastor,':fin'=>$end->format('Y-m-d')));
            $celulas_b= $smt5->fetchAll();
            $celulas = $this->invierte_resultados($celulas_b);
            
            
            //asistencia celula
            
           $sql8 = "select * from get_asistencia_celula_total_xpastor(:pastor,:inicio,:fin)";
           $smt8 = $em->getConnection()->prepare($sql8);
           $smt8->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $asistencia_b = $smt8->fetchAll();
           $asistencia = $this->invierte_resultados($asistencia_b);
           
           
           //asistencia al tumpis
           $sql9 = "select * from get_asistencia_culto_xpastor(:pastor, :inicio,:fin)";
           $smt9 = $em->getConnection()->prepare($sql9);
           $smt9->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $tumpis_b = $smt9->fetchAll();
           $tumpis = $this->invierte_resultados($tumpis_b);
           
           
           //nuevos convertidos
           
           $sql10 = "select * from get_nuevo_conv_xpastor(:pastor,:inicio, :fin)";
           $smt10 = $em->getConnection()->prepare($sql10);
           $smt10->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $nuevos_b = $smt10->fetchAll();
           $nuevos = $this->invierte_resultados($nuevos_b);
           
           //nuevas celulas
           
           $sql11 = "select * from get_nuevas_celula_xpastor(:pastor,:inicio,:fin)";
           $smt11 = $em->getConnection()->prepare($sql11);
           $smt11->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
           $nueva_cell_b = $smt11->fetchAll();
           $nueva_cell = $this->invierte_resultados($nueva_cell_b);
           
           
            //ofrenda
            
            $sql6 = " select * from get_ofrenda_celula_xpastor(:pastor, :inicio, :fin)";
            $smt6 = $em->getConnection()->prepare($sql6);
            $smt6->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $ofrenda_b = $smt6->fetchAll();
            $ofrenda = $this->invierte_resultados($ofrenda_b);

            $todo = $this->union_resultados_enviar($red_encargado, 
//$lideres_red,
                    $doce, $ciento,$mil,$celulas, $asistencia,$tumpis,$nuevos,$nueva_cell, $ofrenda);

            
            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
        
       return new JsonResponse($todo); 
       
    }
    public function informe_enviar_semanal_resumidoAction($pastor, $pastora, $inicio, $fin)
    {
        $pastor_out = $this->informe_semanal_enviar($pastor, $inicio, $fin);
        
        $pastora_out = $this->informe_semanal_enviar($pastora, $inicio, $fin);
        
        $es_pastor= $pastor_out->getContent();
        
        $resultado_pastor = json_decode($es_pastor,true);
        
        
        $resumen_hombres = end($resultado_pastor);
        
       $resumen_hombres['red']='Hombres';
        
        $es_pastora = $pastora_out->getContent();
        
        $resultado_pastora = json_decode($es_pastora,true);
        
        $resumen_mujeres = end($resultado_pastora);
        $resumen_mujeres['red']='Mujeres';
        
        $final = array();
        
        $final[]=$resumen_hombres;
        $final[]=$resumen_mujeres;
        
        $result ="<table class='table table-striped table-bordered'>
					<tr>
						<th rowspan='3'>Hombres</th>
						<th>Líderes <br> de 12</th>
						<th>Líderes <br> de 144</th>
						<th>Líderes <br> de 1728</th>
						<th>Células</th>
						<th>Asistencia <br> a Células</th>
						<th>Asistencia <br> al Tumpis</th>
						<th>Nuevos <br> Convertidos</th>
						<th>Nuevas <br> Células</th>
						<th>Ofrendas</th>
					</tr>
					<tr>
						<td>".$resumen_hombres['doce']." </td>
						<td>".$resumen_hombres['ciento']." </td>
						<td>".$resumen_hombres['mil']." </td>
						<td rowspan='2'>".$resumen_hombres['celulas']." </td>
						<td rowspan='2'>".$resumen_hombres['asistencia_celula']." </td>
						<td rowspan='2'>".$resumen_hombres['tumpis']." </td>
						<td rowspan='2'>".$resumen_hombres['nuevos']." </td>
						<td rowspan='2'>".$resumen_hombres['nueva_cell']." </td>
						<td rowspan='2'>".$resumen_hombres['ofrenda']." </td>
					</tr>
					<tr>
						<td colspan='2'>".($resumen_hombres['doce']+$resumen_hombres['ciento']
                +$resumen_hombres['mil'])."</td>
					</tr>
                                        
                                        <tr>
						<th rowspan='3'>Mujeres</th>
						<th>Líderes <br> de 12</th>
						<th>Líderes <br> de 144</th>
						<th>Líderes <br> de 1728</th>
						<th>Células</th>
						<th>Asistencia <br> a Células</th>
						<th>Asistencia <br> al Tumpis</th>
						<th>Nuevos <br> Convertidos</th>
						<th>Nuevas <br> Células</th>
						<th>Ofrendas</th>
					</tr>
					<tr>
						<td>".$resumen_mujeres['doce']." </td>
						<td>".$resumen_mujeres['ciento']." </td>
						<td>".$resumen_mujeres['mil']." </td>
						<td rowspan='2'>".$resumen_mujeres['celulas']." </td>
						<td rowspan='2'>".$resumen_mujeres['asistencia_celula']." </td>
						<td rowspan='2'>".$resumen_mujeres['tumpis']." </td>
						<td rowspan='2'>".$resumen_mujeres['nuevos']." </td>
						<td rowspan='2'>".$resumen_mujeres['nueva_cell']." </td>
						<td rowspan='2'>".$resumen_mujeres['ofrenda']." </td>
					</tr>
					<tr>
						<td colspan='2'>".($resumen_mujeres['doce']+$resumen_mujeres['ciento']
                +$resumen_mujeres['mil'])."</td>
					</tr>

                                        <tr>
						<th rowspan='3'>Resumen</th>
						<th colspan='3'>Total de<br> Líderes</th>
						<th>Células</th>
						<th>Asistencia <br>a Células</th>
						<th>Asistencia <br>al Tumpis</th>
						<th>Nuevos <br>Convertidos</th>
						<th>Nuevas <br> Células</th>
						<th>Ofrendas</th>
					</tr>
					<tr>
						<td colspan='3'>".($resumen_hombres['doce']+$resumen_mujeres['doce']+
                $resumen_hombres['ciento']+$resumen_mujeres['ciento']+
                $resumen_hombres['mil']+$resumen_mujeres['mil']
                )."</td>
						<td colspan='1'>".($resumen_hombres['celulas']+$resumen_mujeres['celulas'])."</td>
						<td>".($resumen_hombres['asistencia_celula']+$resumen_mujeres['asistencia_celula'])."</td>
						<td>".($resumen_hombres['tumpis']+$resumen_mujeres['tumpis'])."</td>
						<td>".($resumen_hombres['nuevos']+$resumen_mujeres['nuevos'])."</td>
						<td>".($resumen_hombres['nueva_cell']+$resumen_mujeres['nueva_cell'])."</td>
						<td>".($resumen_hombres['ofrenda']+$resumen_mujeres['ofrenda'])."</td>
					</tr>
				</table>";
        
        return new Response($result);
    }
    
    public function miembrosindiscipuladoAction($red)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        
        $em->beginTransaction();
       
        try{
            
               
            $sql1="select * from get_miembros_sin_disc_xred(:net)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':net'=>$red));
            $todo = $smt1->fetchAll();

            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       return new JsonResponse(array('aaData'=>$todo)); 
    }
    
   
    public function miembrocondiscipuladoAction($red)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        
        $em->beginTransaction();
       
        try{
            
               
            $sql1="select * from get_miembros_con_disc_xred(:net)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':net'=>$red));
            $todo = $smt1->fetchAll();

            $em->commit();
            $em->clear();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       return new JsonResponse(array('aaData'=>$todo)); 
    }
    
   
    
    
    public function celulas_discipuladoAction($red)
    {
        $todo = array();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $em->beginTransaction();
        
        $result = "<option value='-1'>Sin Célula </option>";
       
        try{
            
               
            $sql1="select * from get_lideres_celula_dis(:net)";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':net'=>$red));
            $todo = $smt1->fetchAll();
            
            foreach ($todo as $key => $value) {
                $result = $result."<option value='".$value['id']."'>".$value['id']." ".
                        $value['nombres']."</option>";
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
        
        return new Response($result);
    }
    
    
    public function info_celula_redAction($red, $tipo)
    {
    
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            $sql = "select * from  info_celula_por_red(:red,:tipo)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':tipo'=>$tipo));
            $todo = $smt->fetchAll();
           
            $em->commit();
            $em->clear();

        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse($todo);
    
    }

     
    public function info_celula_red_liderAction($red, $lider, $tipo)
    {    
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        $em->beginTransaction();
        
        try{
        
            $sql = "select * from  info_celula_por_red_lider(:red,:lider,:tip)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':lider'=>$lider,':tip'=>$tipo
                    ));
            $todo = $smt->fetchAll();
           
            $em->commit();
            $em->clear();

        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse($todo);
    
    }
    
}