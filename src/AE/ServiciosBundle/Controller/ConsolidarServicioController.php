<?php

namespace AE\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
   
   
     public function lconsolidadores_redAction($red)
   {
        $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        try
        {
            $em->beginTransaction();
            
            $sql = " select * from  get_consolidadores_red(:red)" ;
                
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
   
   public function l_act_consolidadoresAction()
   {
        $result = "";
        
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select * from lista_consolidadores_act";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        $em->clear();
        
        $n = count($todo);
        $temp = "";
        for($i=0; $i<$n; $i++)
        {
               $temp = $temp. "<option value='". $todo[$i]['id']."' >". $todo[$i]['nombre']."  ". $todo[$i]['apellidos']." </option>";
              
        }
  
        return new Response($temp);
   }
   
   
   public function l_act_consolidadores_redAction($id)
   {
        $result = "";
        
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select * from get_consolidadores(:id)";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $todo = $smt->fetchAll();
        $em->clear();
        
        $n = count($todo);
        $temp = "<option value='-1'>Sin Consolidador </option>";
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
                    <label class='control-label'>Fecha Inicio:</label>
            		<div class='controls'>
                         <input type='date' id='begin' name='begin' value='".$d->format('Y-m-d')."'>
                         <input type='hidden' id='numero' name='numero' value='".count($todo)."'>
                    </div>
                         ";
            
             $d->modify('+'.  count($todo).' weeks');
                         
            $result = $result."         
            	</div>
                <div class='control-group'>
                	<label class='control-label'>Fecha Fin:</label>
                    <div class='controls'>
            			<input type='date' id='end' name='end' value='".$d->format('Y-m-d')."'>
                     </div>
            	</div>
                   </div>
                </div>
                </fieldset>  ";
            
            
            
            $result=$result.'
            <div class="box">
            <div class="box-content box-nomargin">
            	<table id="tabla_asignacion" name="tabla_asignacion" class="table table-striped table-bordered">
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
                                            <td><input type='date'class='input-medium' id='inicio".$key."' name='inicio".$key."' value='".$e->format('Y-m-d') ."' > </td>";

                        $e->modify('+6 days');
                        $result = $result."
                                            <td><input type='time' class='input-small' id='time_inicio".$key."' name='time_inicio".$key."' value='00:00:00' ></td>
                                            <td><input type='date' class='input-medium' id='limite".$key."' name='limite".$key."' value='".$e->format('Y-m-d')."' > </td>
                                            <td><input type='time' class='input-small' id='time_limite".$key."' name='time_limite".$key."' value='23:59:00' ></td>
                                            </tr>";
                        
                        $e->modify('-5 days');
                        $e->modify('+1 weeks');

		}
                
                $result = $result."</tbody>  </table> 
                		</div>
                	</div>
                		<div class='control-group' id='subir' name='subir'>
                  <label></label>
              <div class='controls'>
              	<input type='submit' class='btn btn-primary' id='asignar' name='asignar' value='Asignar'>
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
           $em->clear();
        }
        catch (Exception $e){
            $em->rollback();
            $em->close();
            
            throw $e;
        }
   
    
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
    public function nuevos_consolidadores_redAction($red)
   {
       
       $todo = array();
       
       $em = $this->getDoctrine()->getEntityManager();

       try{
         
           $em->beginTransaction();
           $sql = "select * from get_por_consolidadores_red(:red)";
                
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red));
        
           $todo = $smt->fetchAll();
           
           $em->commit();
           $em->clear();
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
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
   public function consolidado_termino_red_yearAction($red, $inicio, $fin)
   {
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       try
       {
           
           $sql = "select *from get_consolidado_termino_xred(:ini, :fin, :net)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':ini'=>$inicio,':fin'=>$fin,':net'=>$red));
           $todo = $smt->fetchAll();
           
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
   public function consolidado_termino_red_year_consAction($red, $inicio, $fin, $cons)
   {
       
       $todo = array();
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       try
       {
           
           $sql = "select *from get_consolidado_termino_xred_consolidador(:ini, :fin, :net,:cons)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':ini'=>$inicio,':fin'=>$fin,':net'=>$red,':cons'=>$cons));
           $todo = $smt->fetchAll();
           
           $em->commit();
           $em->clear();
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
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   
   
    public  function consolidado_seguir_redAction($red, $consol)
   {
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
          
           $sql = "select *from get_seguimiento_red_con(:red,:con)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red,':con'=>$consol));
           $todo = $smt->fetchAll();
           
           $em->commit();
           $em->clear();
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
            $em->clear();
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
							<th>N°</th>
							<th>Tema</th>
							<th>Fecha de Inicio</th>							
                                                        <th>Fecha de Fin</th>
							<th>Hora de Fin</th>
                                                        <th>Completado</th>
					    </tr>
					</thead>
					<tbody>";
            
                $cont =1;
            	foreach ($todo as $key => $val){
			
                    
			$result =$result."<tr>
                                            <td>".$cont." <input type='hidden' id='list".$key."' name='list".$key."' value='".$val['leche']."' > </td>
                                            <td>".$val["titulo"]."</td>
                                            <td>".$val['inicio']."</td>";
                                             
                        $cont++;
                        
                        if($val['fin']!=NULL)
                        {
                            $d = new \DateTime($val['fin']);
                            $d->format('Y-m-d');
                        
                            $result = $result."<td> <input type='date' class='input-medium' id='dia".$key."' name='dia".$key."' class='datepick' value='".$d->format('Y-m-d')."' disabled> </td>
                                            <td> <input type='time' class='input-small' id='hora".$key."' name='hora".$key."' class='timepicker' value='".$d->format('H:i:s')."' disabled> </td>
                                            <td><input type='checkbox' id='check".$key."' name='check".$key."' ".(($val['fin']!=NULL)?("checked disabled"):"").">
                                                <input type='hidden' id='dat".$key."' name='dat".$key."' value='".$key."' >
                                                </td>.
                                           </tr>";
                         }
                         else {
                             $d = new \DateTime();
                             
                                $result = $result."<td> <input type='date'class='input-medium' id='dia".$key."' name='dia".$key."' class='datepicker' value='".$d->format('Y-m-d')."'> </td>
                                            <td> <input type='time' class='input-small' id='hora".$key."' name='hora".$key."' class='timepicker' value='00:00:00' > </td>
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
            $em->clear();
            
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
   
   
    public function pordescartar_redAction($red)
   {
       $em = $this->getDoctrine()->getEntityManager();
       $todo = array();

       try{
           $em->beginTransaction();
           
            $sql = "select * from descartar where red=:net";
          
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':net'=>$red));
        
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
            $em->clear();
            
            $em->commit();
            
        }catch(Exception $e)
        {
            $em->rollback();
            $em->clear();
            $em->close();
            
            throw $e;
        }
        
        return new JsonResponse(array('aaData'=>$todo)); 
   }
   
   
    public function lista_descartados_redAction($red, $inicio,$fin)
   {
        $em = $this->getDoctrine()->getEntityManager();

        $todo = array();
       
        $em->beginTransaction();
        try
        {
            
            $sql = "select * from get_lista_descartados_red(:net,:ini,:fin)";
          
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':net'=>$red, ':ini'=>$inicio,':fin'=>$fin));
        
            $todo = $smt->fetchAll();
           
            $em->commit();
            $em->clear();

        }catch(Exception $e)
        {
            $em->rollback();
            $em->clear();
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
           $em->clear();
           
           $this->getDoctrine()->getEntityManager()->commit();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse(array('aaData'=>$todo));
   }

   
      public function consolidador_consolidado_redAction($red)
   {
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       $todo = array();
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from get_consolidador_consolidado_red(:red)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red));
           $todo = $smt->fetchAll();
           $em->clear();
           
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
       
       $em->beginTransaction();

       try
       {
           $sql = "select * from herramienta";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           $todo = $smt->fetchAll();
           
           $em->clear();
           
           foreach ($todo as $key => $value) {
               $result = $result."<option value='".$value['id'].
                       "'>".$value['nombre']." </option>";
           }
           
           $em->commit();
           $em->clear();
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

       $em->beginTransaction();

       try
       {
           $sql = "select * from get_reporte_no_consolidados(:inicio,:fin)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':inicio'=>$inicio,':fin'=>$fin));          
           $todo = $smt->fetchAll(); 
           $em->commit();
           $em->clear();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }  
       
       return new JsonResponse(array('aaData'=>$todo));
   }
   //funcion de reemplazo de herramientas por falso
   public function reemplazo($source, $tools)
   {
       $retorno =$source;
       foreach ($tools as $key => $value) {
           $retorno= str_replace($tools[$key],'F',$retorno); //titulo

       }
       return $retorno;
   }


   public function getHerramientaNuevosAction($inicio, $fin)
   {
      $em = $this->getDoctrine()->getEntityManager();
       $todo = array();
       $tools = array();

       $em->beginTransaction();

       try
       {
           $init = new \DateTime($inicio);
           $end  = new \DateTime($fin);
           
           $sql = "select * from get_reporte_herramientas(:inicio,:fin)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':inicio'=>$init->format('Y-m-d H:i:s'),':fin'=>$end->format('Y-m-d H:i:s')));          
           $todo = $smt->fetchAll(); 
           $em->clear();
           
           
           $sql1 = "select * from herramienta";
           $smt  = $em->getConnection()->prepare($sql1);
           $smt->execute();
           
           $tools = $smt->fetchAll();
           $em->clear();
           
           
           $herramienta = array();
           
           //creamos la cabezera
           $temp = "<thead> <tr> <th>N°</th><th>ID</th> <th>Nombres</th><th>Apellidos</th><th>Red</th>";
           $cuerpo = "<tr> <td>N°</td><td>ID</td> <td>Nombres</td><td>Apellidos</td><td>Red</td>";
           
            $result="<table id='persona' name='persona' class='table table-striped table-bordered'>";

           foreach ($tools as $key => $value) {
               $herramienta[$value['nombre']]=$value['nombre'];
               
               $temp = $temp."<th>".$value['nombre']."</th>";
               $cuerpo = $cuerpo."<td>".$value['nombre']."</td>";
               
           }
           $temp = $temp." </thead> </tr>";
           $cuerpo = $cuerpo." </tr>";
           
           $result = $result.$temp;
           
           $body="<tbody id='tablas1' name='tablas1'>";
           
           //creamos el cuerpo
           
           if(count($todo)>0)
           {
                $old = $todo[0]['idx'];
                $newe = $todo[0]['idx'];
           }
           $cont =0;
           $cadena = $cuerpo; 
           $numero = 0;
           foreach ($todo as $key => $value) {               
                
               $newe = $value['idx'];
               if($newe == $old && $cont!=0)
               {
                    $body = str_replace($value['titulo'], ($value['hecho']==TRUE)?'T':'F', $body); //titulo

               }
               else
               {
                  $body = $this->reemplazo($body, $herramienta);
                  
                  $cadena = $cuerpo; 
                  $cadena = str_replace('N°', strval($numero), $cadena); //id

                  $cadena = str_replace('ID', $value['idx'], $cadena); //id
                  $cadena = str_replace('Nombres', $value['nombre'], $cadena); //nombre
                  $cadena = str_replace('Apellidos', $value['apellidos'], $cadena); //apellidos
                  $cadena = str_replace('Red', $value['id_red'], $cadena); //red
                  $cadena = str_replace($value['titulo'], ($value['hecho']==TRUE)?'T':'F', $cadena); //titulo
                  
                  $body = $body.$cadena;
                  $cont =$cont+1;
                  $numero = $numero+1;
               }
               $old = $newe;
           }
           
           //limpiar los que aún no han sido seleccionados
           
           foreach ($tools as $key => $value) {
               $body = str_replace($value['nombre'],'F', $body); //titulo
           }
           
           $result = $result.$body."</tbody> </table>";

           $em->commit();
           $em->clear();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }  
       return new Response($result);
       //return new JsonResponse($todo); 
   }
   
   
   public function getLecheEspiritualConsolidaAction($inicio, $fin, $leche, $red, $consol)
   {
       $result = "";
       
      $em = $this->getDoctrine()->getEntityManager();
       $todo = array();
       $tools = array();
       $tool_per = array();

       $em->beginTransaction();

       try
       {
           
           $begin = new \DateTime($inicio);
           
           $init = new \DateTime();
           $init->setDate($begin->format('Y'), '01', '01');
           
           $end  = new \DateTime($fin);
           
           $sql = "select * from get_reporte_leche_espiritual_consolidador_red(:inicio,:fin,:leche,:net,:consol)";

           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':inicio'=>$init->format('Y-m-d H:i:s'),':fin'=>$end->format('Y-m-d H:i:s'),
               ':leche'=>$leche, ':net'=>$red, ':consol'=>$consol));          
           $todo = $smt->fetchAll(); 
           $em->clear();
           
           
           $sql1 = " select * from tema_leche where id_leche_espiritual=:leche";
           $smt  = $em->getConnection()->prepare($sql1);
           $smt->execute(array(':leche'=>$leche));
           
           $tools = $smt->fetchAll();
           $em->clear();
           
           
           $herramienta = array();
           
           //creamos la cabezera
           $temp = "<thead> <tr> <th>N°</th> <th>Inicio</th> <th>Nombres</th><th>Apellidos</th><th>Edad</th><th>Dirección</th> <th>Telefono</th>".
                   "<th>Co</th> <th> Vi </th> <th>Cé</th><th>Ig</th>";
           $cuerpo = "<tr> <td>N°</td> <td>Inicio</td>  <td>Nombres</td><td>Apellidos</td><td>Edad</td><td>Dirección</td> <td>Telefono</td>".
                   "<td>Contacto</td><td> Visita </td> <td>Célula</td><td>Iglesia</td>";
           
            $result="<table id='persona' name='persona' class='table table-striped table-bordered'>";

           foreach ($tools as $key => $value) {
               $herramienta[$value['titulo']]=$value['titulo'];
               
               $temp = $temp."<th>".($key+1)."</th>";
               $cuerpo = $cuerpo."<td>".$value['titulo']."</td>";
               
           }
           $temp = $temp." <th>De</th> <th>En</th> </thead> ";
           $cuerpo = $cuerpo."<td>Descartado</td> <td>Encuentro<td></tr>";
           
           $result = $result.$temp;
           
           $body="<tbody id='tablas1' name='tablas1'>";
           
         
           $old = NULL;
           $newe = NULL;
           $cont =0;
           $cadena = $cuerpo; 
           $numero = 0;
           
           foreach ($todo as $key => $value) {               
                
               
               
               $newe = $value['id'];
               if($newe == $old )
               {
              //      $body = str_replace($value['titulo'], ($value['hecho']==TRUE)?'T':'F', $body); //titulo
                   
                   if(count($value['fin'])>0)
                   {
                       $fin = new \DateTime($value['fin']);
                       
                    $body = str_replace($value['titulo'], '  &#10003; <br> '.$fin->format('d/m'), $body); //titulo
                   }
                   else
                    $body = str_replace($value['titulo'], ' ', $body); //titulo
        

               }
               else
               {
                  $body = $this->reemplazo($body, $herramienta);
                  
                  $cadena = $cuerpo; 
                  
                  //descartado
                  
                  $sql4 = "select * from descartado n inner join consolida c on c.id_nuevo_convertido=n.id and ".
                          "c.pausa=true and n.fecha_descarte between :ini and :fin and c.id_nuevo_convertido=:id";
                  
                  $smt4 = $em->getConnection()->prepare($sql4);
                  $smt4->execute(array(':ini'=>$init->format('Y-m-d'),
                      ':fin'=>$end->format('Y-m-d'),':id'=>$value['id']));
                  
                  $resultado = $smt4->fetchAll();
                  
                  if(count($resultado)>0)
                  {
                       $cadena = str_replace('Descartado', ' ', $cadena); //titulo

                  }
                  
                  //herramientas 
                  
                  $sql0 = "select * from get_reporte_herramientas_persona(:id,:ini,:fin)";
                  $smt0 = $em->getConnection()->prepare($sql0);
                  $smt0->execute(array(':id'=>$value['id'],':ini'=>$init->format('Y-m-d H:i:s'),
                            ':fin'=>$end->format('Y-m-d H:i:s')));
           
                  $tool_per = $smt0->fetchAll();
                  $em->clear();
                  
                  foreach ($tool_per as $key => $vale) {
                      if(count($vale['fecha'])>0)
                      {
                        $cadena = str_replace($vale['tool'],' &#10003;' , $cadena); //inicio
                      }
                      else 
                      {
                        $cadena = str_replace($vale['tool'],'' , $cadena); //inicio

                      }
                  }
                  
                  $cadena = str_replace('N°', strval($numero), $cadena); //id
                  
                  
                  $ini = new \DateTime($value['fecha_inicio']);
                  
                  $cadena = str_replace('Inicio',$ini->format('d/m/y') , $cadena); //inicio
                  
                  
                  $cadena = str_replace('Nombres', $value['nombre'], $cadena); //nombre
                  $cadena = str_replace('Apellidos', $value['apellidos'], $cadena); //apellidos
                  $cadena = str_replace('Edad', $value['edad'], $cadena); //edad
                  $cadena = str_replace('Dirección', $value['direccion'], $cadena); //direccion
                  $cadena = str_replace('Telefono', $value['telefono'], $cadena); //telefono



                  //$cadena = str_replace($value['titulo'], (count($value['fin'])>0)?$value['fin']:'', $cadena); //titulo
                   if(count($value['fin'])>0)
                   {
                       $fin = new \DateTime($value['fin']);
                       
                    $cadena = str_replace($value['titulo'], '  &#10003; <br> '.$fin->format('d/m'), $cadena); //titulo
                   }
                   else
                    $cadena = str_replace($value['titulo'], ' ', $cadena); //titulo
                  
                  $body = $body.$cadena;
                  $cont =$cont+1;
                  $numero = $numero+1;
               }
               $old = $newe;
           }
           
           $result = $result.$body."</tbody> </table>";
           
           $sql2 = "select * from herramienta";
           $smt2 = $em->getConnection()->prepare($sql2);
           $smt2->execute();
           $herr = $smt2->fetchAll();
           
           foreach ($herr as $key => $value) {
              $result = str_replace($value['nombre'], ' ', $result); //titulo

           }
           $result = str_replace('Descartado', ' ', $result); //titulo


           $em->commit();
           $em->clear();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->clear();
           $em->close();
           throw  $e;
       }
       
       return new Response($result);
   }
   
   
   public function getDescartadosAction($inicio, $fin)
   {
       $em = $this->getDoctrine()->getEntityManager();
       $todo = array();

       $em->beginTransaction();

       try
       {
           $sql = "select * from get_reporte_descartados2(:inicio,:fin)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':inicio'=>$inicio,':fin'=>$fin));          
           $todo = $smt->fetchAll(); 
           $em->commit();
           $em->clear();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }  
       return new JsonResponse(array('aaData'=>$todo));
   }
   
   
   public function getReporteAlmasConsolidadorAction($id)
   {
       $em = $this->getDoctrine()->getEntityManager();
       $todo = array();

       $em->beginTransaction();

       try
       {
           $sql = "select * from get_reporte_consolidados_consolidador(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));          
           $todo = $smt->fetchAll(); 
           
           
           $em->commit();
           $em->clear();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }  
       return new JsonResponse(array('aaData'=>$todo));
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
   
   
       public function informe_semanal_consolidar_pastorAction($pastor, $inicio, $fin)
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

            //por consolidar
            $sql3="select * from get_porconsolidar_red_xpastor(:pastor,:inicio,:fin)";
            $smt3 = $em->getConnection()->prepare($sql3);
            $smt3->execute(array(':pastor'=>$pastor,':inicio'=>$init->format('Y-m-d'),':fin'=>$fin));
            $porconsolidar_b = $smt3->fetchAll();
            
            $porconsolidar = $this->invierte_resultados($porconsolidar_b);
        
            //consolidados
            $sql4=" select * from get_consolidados_red_xpastor(:pastor, :inicio, :fin)";
            $smt4 = $em->getConnection()->prepare($sql4);
            $smt4->execute(array(':pastor'=>$pastor,':inicio'=>$begin->format('Y-m-d H:i:s'),
                ':fin'=>$end->format('Y-m-d H:i:s')));
            $consolidados_b = $smt4->fetchAll();
            
            $consolidados_red = $this->invierte_resultados($consolidados_b);
            
            //no consolidados
            
            $sql5 = " select * from get_no_consolidados_red_xpastor(:pastor,:inicio,:fin)";
            $smt5 = $em->getConnection()->prepare($sql5);
            $smt5->execute(array(':pastor'=>$pastor,':inicio'=>$begin->format('Y-m-d H:i:s'),
                ':fin'=>$end->format('Y-m-d H:i:s')));
            $no_consolidados_b= $smt5->fetchAll();
            $no_consolidados_red = $this->invierte_resultados($no_consolidados_b);
           
           
            //por encuentro
            
            $sql6 = " select * from get_pencuentro_xpastor(:pastor, :inicio, :fin)";
            $smt6 = $em->getConnection()->prepare($sql6);
            $smt6->execute(array(':pastor'=>$pastor,':inicio'=>$inicio,':fin'=>$fin));
            $encuentro_b = $smt6->fetchAll();
            $encuentro = $this->invierte_resultados($encuentro_b);

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

