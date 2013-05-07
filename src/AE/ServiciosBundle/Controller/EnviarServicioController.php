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
        
        try{
            $em->beginTransaction();
            
            $sql = "select * from lista_celula_lider_red";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $lider_red = $smt->fetchAll();
            
            $sql = "select * from lista_celula_misionero";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute();
            $misionero = $smt1->fetchAll();
            
            $sql = "select * from lista_celula_pastor_eje";
            $smt2 = $em->getConnection()->prepare($sql);
            $pastor = $smt2->fetchAll();
            
            $todo = $lider_red;
            foreach ($misionero as $key => $value) {
                $todo[]=$value;                
            }
            
            foreach ($pastor as $key => $value) {
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
					<td>".($val['tipo']=0?'Evangelistica':'Discipulado')."</td>
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
 
       try
       {
          $em->beginTransaction();
          
          $sql = "select *from  asistencia_tema(:id)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':id'=>$id));
          
          $todo = $smt->fetchAll();
          
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
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from lista_tema_celula";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           
           $this->getDoctrine()->getEntityManager()->commit();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
     public function contar_clase_celulaAction()
   {
      $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from lista_tema_celula";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $temp = $smt->fetchAll();
           
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
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));  
   }
   
}