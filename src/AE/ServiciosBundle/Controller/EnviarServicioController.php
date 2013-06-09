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
        
            
            $sql = "select * from get_celula_evan_lider_red(:red,:tip)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$red,':tip'=>$tipo));
            $lider_red = $smt->fetchAll();
            $em->clear();
            
            $sql = "select * from get_celula_evan_misionero(:red,:tip)";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute(array(':red'=>$red,':tip'=>$tipo));
            $misionero = $smt1->fetchAll();
            $em->clear();
            
            $sql = "select * from get_celula_evan_pastor_eje(:red,:tip)";
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
            
            $sql = "select * from get_celula_evan_lider(:red,:tip)";
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
      
       
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       try
       {
          $sql = "select *from  celulas_por_red(:red,:tip)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':red'=>$red,':tip'=>$tipo));
          
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
    
    public function getAsistenciaCelulaClassAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->beginTransaction();

       try
       {
          
          $sql = "select *from  asistencia_tema(:id)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':id'=>$id));
          
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
        		<td colspan='2'>Dirección: ".$todo['direccion']."</td>
        		<td>Tel. Anfitrión: ".$todo['ctel']."</td>
        		<td>Familia Anfitriona: ".$todo['familia']."</td>
        </tr>
        <tr>
        		<td>Apertura: ". $todo['fecha_creacion']."</td>
        		<td>Tipo: ". ((strval($todo['ctipo'])=='0')?'Evangelistica':'Discipulado')."</td>
        		<td>Fecha Inicio: ".$ini." </td>
        		<td>Fecha Final: ".$fin."</td>
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
                    
                    $eventos = $this->searchEvento($encbau,$id_ini);;
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
                $todo[] = $fila;
            
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
        <th>Leche Espiritual</th>
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
                      $element= $element."&#10004;";
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
        
        $result = $result."</table>";

    
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
    
    public function union_resultados_consolidar(
            $red, $lideres, $porconsolidar, $consolidados, $noconsolidados,$encuentro)
    {
        $out = array();
       
        
        $n = count($red);
        
        $cont = 0;
        foreach ($red as $key => $value) {
           
            $temp = array();
            
            $temp['red'] = $value['red'];
            $temp['nombres']=$value['nombres'];
            
            $temp['lideres']=$lideres[$value['red']]['lideres'];
            $temp['meta'] = $temp['lideres']*7;
            
            $temp['porconsolidar']=$porconsolidar[$value['red']]['pconsolidar'];
            $temp['consolidados']= $consolidados[$value['red']]['pconsolidados'];
            
            $temp['noconsolidados']=$noconsolidados[$value['red']]['pconsolidados'];
            $temp['encuentro'] = $encuentro[$value['red']]['pencuentro'];
            
            $out[] = $temp;
        }
        
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
        
            $sql2="select * from get_lideres_red_xpastor(:pastor)";
            $smt2 = $em->getConnection()->prepare($sql2);
            $smt2->execute(array(':pastor'=>$pastor));
            $lideres_red_b = $smt2->fetchAll();
            $lideres_red = $this->invierte_resultados($lideres_red_b);

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
            
           $sql8 = "select * from get_asistencia_celula_xpastor(:pastor,:inicio,:fin)";
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

            $todo = $this->union_resultados_consolidar($red_encargado, 
$lideres_red, $porconsolidar, $consolidados_red,$no_consolidados_red,$encuentro);

            
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


}