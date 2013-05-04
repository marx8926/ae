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


class ConsolidarServicioController extends Controller
{
     public function lconsolidadoresAction()
   {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        try
        {
            $em->beginTransaction();
            
            $sql = " select * from lista_consolidadores" ;
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            
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
   
   public  function lista_espiritualAction()
   {
       $todo = array();

       $em = $this->getDoctrine()->getEntityManager();

       try{ 
           $em->beginTransaction();
           
           $sql = " select * from leche_espiritual";    
        
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
        
           $todo = $smt->fetchAll();
           
          
        }
        catch(Exception $e)
        {
       
           $em->rollback();
           $em->close();
           
           throw $e;
        }

        return new JsonResponse($todo);
   }
   
   public function l_act_consolidadoresAction()
   {
        $result = "";
        
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select * from lista_consolidadores_act";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        $n = count($todo);
        $temp = "";
        for($i=0; $i<$n; $i++)
        {
               $temp = $temp. "<option value='". $todo[$i]['id']."' >". $todo[$i]['red']." ".$todo[$i]['nombre']."  ". $todo[$i]['apellidos']." </option>";
              
        }
  
        return new Response($temp);
   }
   
   public function leche_esp_temasAction($id)
   {

        $em = $this->getDoctrine()->getEntityManager();
        $todo = array();
        
        $d = new \DateTime();

        try
        {
            $em->beginTransaction();
            
            $sql = "select * from ver_tema_leche(:id)";    
        
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
        
            $todo = $smt->fetchAll();
            
            
           
            $result = "<fieldset>  <legend>Limites </legend>
                <div class='control-group'>
                   <div class='control-row'>
                  
                     <li>
                         <p class='help-block' >Fecha Inicio</p>
                         <input type='date' id='begin' name='begin' value='".$d->format('Y-m-d')."'>
                         <input type='hidden' id='numero' name='numero' value='".count($todo)."'>
                         ";
            
             $d->modify('+'.  count($todo).' weeks');
                         
            $result = $result."         </li>
                     <li>
                         <p class='help-block' >Fecha Fin</p>
                         <input type='date' id='end' name='end' value='".$d->format('Y-m-d')."'>
                     </li>
                   </div>
                </div>
                </fieldset>  ";
            
            
            
            $result=$result.'<table id="tabla_asignacion" name="tabla_asignacion" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tema</th>
							<th>Fecha de Inicio</th>
							<th>Hora de Inicio</th>
                                                        <th>Fecha de Fin</th>
							<th>Hora de Fin</th>					        
					    </tr>
					</thead>
					<tbody>';
            
             $e = new \DateTime();
             $f = new \DateTime();
             
            	foreach ($todo as $key => $val){
			
			$result =$result."<tr>
                                            <td>".$val["id"]." <input type='hidden' id='id".$key."' name='id".$key."' value='".$val["id"]."'> </td>
                                            <td>".$val["titulo"]."</td>
                                            <td><input type='date' id='inicio".$key."' name='inicio".$key."' value='".$e->format('Y-m-d') ."' > </td>";

                        $e->modify('+6 days');
                        $result = $result."
                                            <td><input type='time' id='time_inicio".$key."' name='time_inicio".$key."' value='00:00:00' ></td>
                                            <td><input type='date' id='limite".$key."' name='limite".$key."' value='".$e->format('Y-m-d')."' > </td>
                                            <td><input type='time' id='time_limite".$key."' name='time_limite".$key."' value='23:59:00' ></td>
                                            </tr>";
                        
                        $e->modify('-5 days');
                        $e->modify('+1 weeks');

		}
                
                $result = $result."</tbody>  </table> <div class='control-group' id='subir' name='subir'>
                  <label></label>
                  <div class='controls'>
                      <input type='submit' class='btn-success' id='asignar' name='asignar' value='Asignar'>
                  </div>
                  </div> ";
            
            $em->commit();
            
        }  catch (Symfony\Component\Config\Definition\Exception\Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }

        return new Response($result);
   }
   
   public function nuevos_consolidadoresAction()
   {
       
       $todo = array();
       
       $em = $this->getDoctrine()->getEntityManager();

       try{
         
           $em->beginTransaction();
           $sql = "select * from lista_nuevos_consolidadores_reg";
                
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
        
           $todo = $smt->fetchAll();
           
           $em->commit();
        }
        catch (Exception $e){
            $em->rollback();
            $em->close();
            
            throw $e;
        }
   
    
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
      
   public function consolidado_terminoAction()
   {
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       try
       {
           
           $sql = "select *from consolidado_termino";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           
           $em->commit();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
   public  function consolidado_seguirAction()
   {
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
          
           $sql = "select *from consolidando";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           
           $em->commit();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
    public function consolidadoAction($id)
    {
       $em = $this->getDoctrine()->getEntityManager();

        $sql = "select persona.id, persona.nombre, persona.apellidos, consolida.fecha_inicio as inicio, consolida.fecha_fin as fin, consolida.id_consolidador as consolidador, consolida.id as code from consolida left join persona on persona.id = consolida.id_miembro where consolida.id =:id".
                " and consolida.termino=false and consolida.pausa=false";
    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $todo = $smt->fetch();
        
        return new JsonResponse($todo);
   }
   
   
    public function temasAction($cons)
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select * from get_consolidado_temas(:id)";
        
        $todo = array();
        try
        {
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$cons));
        
            $todo = $smt->fetchAll();
            
            $em->commit();
        }
        catch (Exception $e)
        {
            $em->rollback();
            $em->close();
                    
            throw $e;
        }
        
                
         $result="<div> <input type='hidden' id='numero' name='numero' value='".count($todo)."'><table id='tabla_temas' name='tabla_temas' class='table table-striped table-bordered'>
					<thead>
						<tr>
							<th>ID</th>
							<th>Tema</th>
							<th>Fecha de Inicio</th>							
                                                        <th>Fecha de Fin</th>
							<th>Hora de Fin</th>
                                                        <th>Completado</th>
					    </tr>
					</thead>
					<tbody>";
            
       
            	foreach ($todo as $key => $val){
			
                    
			$result =$result."<tr>
                                            <td>".$val["id"]." <input type='hidden' id='list".$key."' name='list".$key."' value='".$val['leche']."' > </td>
                                            <td>".$val["titulo"]."</td>
                                            <td>".$val['inicio']."</td>";
                                             
                        if($val['fin']!=NULL)
                        {
                            $d = new \DateTime($val['fin']);
                            $d->format('Y-m-d');
                        
                            $result = $result."<td> <input type='date' id='dia".$key."' name='dia".$key."' class='datepick' value='".$d->format('Y-m-d')."' disabled> </td>
                                            <td> <input type='datetime' id='hora".$key."' name='hora".$key."' class='timepicker' value='".$d->format('H:i:s')."' disabled> </td>
                                            <td><input type='checkbox' id='check".$key."' name='check".$key."' ".(($val['fin']!=NULL)?("checked disabled"):"").">
                                                <input type='hidden' id='dat".$key."' name='dat".$key."' value='".$key."' >
                                                </td>.
                                           </tr>";
                         }
                         else {
                                $result = $result."<td> <input type='date' id='dia".$key."' name='dia".$key."' class='datepick' > </td>
                                            <td> <input type='datetime' id='hora".$key."' name='hora".$key."' class='timepicker' > </td>
                                            <td><input type='checkbox' id='check".$key."' name='check".$key."' ></td>.
                                           </tr>";
                         }
                                               
		}
                
                $result = $result."</tbody>  </table> </div>";
        
        return new Response($result);
   }

   
      
   public function pordescartarAction()
   {
       $em = $this->getDoctrine()->getEntityManager();
       $todo = array();

       try{
           $em->beginTransaction();
           
            $sql = "select * from descartar";
          
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            
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
   
   public function lista_descartadosAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $todo = array();
        
        $em->beginTransaction();
        try
        {
            
            $sql = "select * from lista_descartar";
          
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            
            $em->commit();
            
        }catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo)); 
   }
   
      public function consolidador_consolidadoAction()
   {
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       $todo = array();
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from consolidador_consolidado";
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

   public function getToolsAction()
   {
       $em = $this->getDoctrine()->getEntityManager();
       $todo = array();
       $result = "<select id='tools' name='tools' required>";
       try
       {
           $em->beginTransaction();
           $sql = "select * from herramienta";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           
           
           foreach ($todo as $key => $value) {
               $result = $result."<option value='".$value['id'].
                       "'>".$value['nombre']." </option>";
           }
           
           $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       $result = $result." </select>";
       
       return new Response($result);
   }
   
   public function getNoConsolidadosAction($inicio, $fin)
   {
        $em = $this->getDoctrine()->getEntityManager();
       $todo = array();

       try
       {
           $em->beginTransaction();
           $sql = "select * from get_reporte_no_consolidados(:inicio,:fin)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':inicio'=>$inicio,':fin'=>$fin));          
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
}
