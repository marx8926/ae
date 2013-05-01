<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\Consolida;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEConsolidarBundle:Default:index.html.twig', array('name' => $name));
    }
    
    //registrar asignacion red
    
    public function registroAction()
    {
        return $this->render('AEConsolidarBundle:Default:registro.html.twig');
    }
    public function registroupdateAction($id)
    {
           $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try {
                //miembro
                    $sql = "UPDATE miembro SET  apto_consolidar= :apto WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':apto'=>TRUE, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                    
                    //chequear si es consolidador
                    $sql = "select *from consolidador where id=:codigo";
                    $all = array();
                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                    else $all = $smt->fetchAll();
                        
                    if(count($all)>0)
                    {
                         $sql = "UPDATE consolidador SET  activo= true WHERE id= :codigo";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }
                         
                    }
                    else
                    {
                         $pers = $em->getRepository('AEDataBundle:Persona');
                         $persona = $pers->findOneBy(array('id'=>$id));
                         
                         $consolidador = new Consolidador();
                         $consolidador->setActivo(TRUE);
                         $consolidador->setFechaObtencion(new \DateTime());
                         $consolidador->setId($persona);
                         
                         $em->persist($consolidador);
                         $em->flush();
                         
                    }
                        
                   
                    $this->getDoctrine()->getEntityManager()->commit();
            } catch (Exception $exc) {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                
                throw $exc;

            }
     
         return $this->render('AEConsolidarBundle:Default:registro.html.twig');

     }
     
     public function modificarAction()
     {
         return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
     }
     
     public function modificar_activarAction($id)
     {
         
         $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try {
                 //miembro
                    $sql = "UPDATE miembro SET  apto_consolidar= true WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                  
                    //chequear si es consolidador
                    $sql = "select *from consolidador where id=:codigo";
                    $all = array();
                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                    else $all = $smt->fetchAll();
                        
                    if(count($all)>0)
                    {
                         $sql = "UPDATE consolidador SET  activo=true WHERE id= :codigo";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }
                         
                    }
         
                    $this->getDoctrine()->getEntityManager()->commit();
            } catch (Exception $exc) {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                
                throw $exc;

            }
        return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
     }
     
     public function modificar_desactivarAction($id)
     {
         $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try {
                
                //miembro
                    $sql = "UPDATE miembro SET  apto_consolidar= false WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                  
                    //chequear si es consolidador
                    $sql = "select *from consolidador where id=:codigo";
                    $all = array();
                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                    else $all = $smt->fetchAll();
                        
                    if(count($all)>0)
                    {
                         $sql = "UPDATE consolidador SET  activo=false WHERE id= :codigo";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }
                         
                    }
                                 
                    $this->getDoctrine()->getEntityManager()->commit();
            } catch (Exception $exc) {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                
                throw $exc;

            }
        return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
     }

     public function lista_consolidarAction()
     {
         return $this->render('AEConsolidarBundle:Default:lista_consolidador.html.twig');
     }
     
     public function registrar_asignacionAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');
        $num = $request->request->get('numero');
        
        $num_clases = intval($num);
        
        $fechas_ini = array();
        $fechas_fin = array();
        $time_ini = array();
        $time_fin = array();
        
         $datos = array();

        parse_str($name,$datos);

        
        $em = $this->getDoctrine()->getEntityManager(); 
        
         if($name!=NULL){
             
              $consolidador = $datos['select_consolidador']; 
             $leche = $datos['select_leche'];
             $ini = $datos['begin'];
             $lim = $datos['end'];
             
              $i=0;
             
             for($i=0;$i< $num_clases;$i++)
             {
                $fechas_ini[] = $datos['inicio'.strval($i)] ;           
                $fechas_fin[] = $datos['limite'.strval($i)] ; 
                $time_ini[]= $datos['time_inicio'.strval($i)];
                $time_fin[]= $datos['time_limite'.strval($i)];
                
             }
           
              $em->beginTransaction();
            try
            {
                if($consolidador != $id)
                {    
                   
                    //consolidador
                  
                    
                    $query = $em->createQuery('SELECT u FROM AE\DataBundle\Entity\Consolidador u WHERE u.id =:id');
                    $query->setParameter('id', $consolidador);
                    $consolidador_q= $query->getResult();
                    $consolidador_f = $consolidador_q[0];
                    
                    
                    //nuevo_convertido
                    
                    $query = $em->createQuery('SELECT u FROM AE\DataBundle\Entity\Persona u where u.id=:id');
                    $query->setParameter('id', $id);
                    $per   = $query->getResult();
                    $persona = $per[0];
                    
                    
                    $new_convert = $em->getRepository('AEDataBundle:NuevoConvertido');
                    $new_convert_f = $new_convert->findOneBy(array('id'=>$id));
                    $new_convert_f->setConsolidado(true);
                    $em->persist($new_convert_f);
                    $em->flush();
                    
                     /*
                    //miembro
                    $miembro = $em->getRepository('AEDataBundle:Miembro');
                    $miembro_f = $miembro->findOneBy(array('id'=>$id));
                    
                    //agregar consolida (consolidador, nuevo_convertido)
                    
                    
                    $consolida = new Consolida();
                    $consolida->setFechaInicio(new \DateTime($ini));
                    $consolida->setFechaFin(new \DateTime($lim));
                    $consolida->setIdConsolidador($consolidador_f);
                    $consolida->setIdMiembro($miembro_f);
                    $consolida->setIdNuevoConvertido($new_convert_f);
                    $consolida->setPausa(FALSE);
                    $consolida->setTermino(FALSE);
                    
                    
                    $em->persist($consolida);
                    $em->flush();
                    
                                    
                    $sql = " select * from tema_leche where tema_leche.id_leche_espiritual = :id ";    
        
                    $smt = $em->getConnection()->prepare($sql);
                    
                    $smt->execute(array(':id'=>$leche));
                    
                    $i =0;
                    
                     //while((
                     $todo = $smt->fetchAll();
                     
                     $n = count($todo);
                     
                     for($i =0; $i< $n; $i++)
                     {
                        
                         $sql ="INSERT INTO many_consolidacion_has_many_tema_leche(".
                                "id_consolida, id_tema_leche, fecha_hora_inicio, ".
                                "fecha_hora_limite) VALUES (:idc, :idtl,:fIn,:fLim)";
                         
                         //, :fIn,:fLim
                         $smt = $em->getConnection()->prepare($sql);
                         
                        // $td = $todo[$i];
                         
                       if(!$smt->execute(array(':idc'=>$consolida->getId(),':idtl'=>$todo[$i]['id'],
                           ':fIn'=>$fechas_ini[$i].' '.$time_ini[$i],':fLim'=>$fechas_fin[$i].' '.$time_fin[$i])))
                       {
                            $return=array("responseCode"=>400, "greeting"=>"Bad");
             
    
                            $return=json_encode($return);//jscon encode the array
     
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                       }
                     
                     }
                    */
                    $em->commit();
                    
                    $return=array("responseCode"=>200,  "greeting"=>$persona->getId());
                }
                else
                {
                    $return=array("responseCode"=>400,  "greeting"=>'Bad');

                }
 
            }catch(Exception $e)
            {
               $em->rollback();
               $em->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
               
            }

           // $return=array("responseCode"=>200, "greeting"=>$num);
         }
         else  $return=array("responseCode"=>400, "greeting"=>"Bad");
             
    
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     
         
        
     }
     
     public function seguirAction($id)
     {
         return $this->render('AEConsolidarBundle:Default:seguir.html.twig',array('id'=>$id));
     }
     
     public function seguir_updateAction()
     {
           $request = $this->get('request');
        $name=$request->request->get('formName');
        $num = $request->request->get('num');
        $ids = $request->request->get('list');
        
        $numero = intval($num);
  
         $datos = array();

        parse_str($name,$datos);
        
        $check = array();
        $fechas = array();
        
        
                        
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();

        if($name!=NULL)
        {
            for($i=0;$i < $numero; $i++)
            {
                $var = "check".strval($i);
                if(strpos($name, $var)!==false)
                {
                    $check[$var]=$datos[$var];
                    
                    $temp = "fecha_fin".strval($i);
                    
                    $fechas[$temp] = $datos[$temp];
                    
                    if(strlen($fechas[$temp])>=0)
                    {
                        //consultar si ya actualizo las fechas
                        
                        try
                        {
                
                            $sql = "select update_consolida_leche(:leche,:fin)";
                            
                            $smt = $em->getConnection()->prepare($sql);
                            
                            $smt->execute(array(':fin'=>$fechas[$temp], ':leche'=>$ids[$i]));

                           // $return=array("responseCode"=>200,  "greeting"=>$ids); 
                        }catch(Exception $e)
                        {
                            $this->getDoctrine()->getEntityManager()->rollback();
                            $this->getDoctrine()->getEntityManager()->close();
                
                            $return=array("responseCode"=>400, "greeting"=>"Bad");

                            $return=json_encode($return);//jscon encode the array
     
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
                            
                            throw $e;
               
                        }
                        
                    }
                    else $return=array("responseCode"=>500, "greeting"=>'bad');
                }
            }
           $this->getDoctrine()->getEntityManager()->commit();

            
            $return=array("responseCode"=>200, "greeting"=>'Ok');
        }
        else  $return=array("responseCode"=>400, "greeting"=>$name);
            
        
      
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
        //falta cambiar a proce
     }
      public function descartarAction()
     {
         return $this->render('AEConsolidarBundle:Default:descartar.html.twig');
     }
     
     public function descartar_updateAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
              
        $em = $this->getDoctrine()->getEntityManager();
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();
        
        if($name!=NULL)
        {
        try
        {
            $sql = "select update_consolida(:id)";
            
            $smt = $em->getConnection()->prepare($sql);
                         
                        // $td = $todo[$i];
                         
            if(!$smt->execute(array(':id'=>$name)))
            {
                $return=array("responseCode"=>400, "greeting"=>"Bad");

                $return=json_encode($return);//jscon encode the array
     
                return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
            }
            
            $this->getDoctrine()->getEntityManager()->commit();
        }catch(Exception $e)
        {
             $this->getDoctrine()->getEntityManager()->rollback();
             $this->getDoctrine()->getEntityManager()->close();
                
             $return=array("responseCode"=>400, "greeting"=>"Bad");

             throw $e;
        }
        $return=array("responseCode"=>200, "greeting"=>"Good");
        }
        else  $return=array("responseCode"=>400, "greeting"=>"Bad");
            
  
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     }
     
     public function lista_descartarAction()
     {
         return $this->render('AEConsolidarBundle:Default:lista_descartados.html.twig');
     }
     
     public function vistaAction($id)
     {
         return $this->render('AEConsolidarBundle:Default:vista.html.twig', array('id'=>$id));
     }
     
     public function activar_nuevoAction()
     {
            $request = $this->get('request');
        $name=$request->request->get('formName');
              
        $em = $this->getDoctrine()->getEntityManager();
        
        // $this->getDoctrine()->getEntityManager()->beginTransaction();
        $return = null;
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();
        try{
            
     
        $sql = "select activa_consolida(:id)";
        
         $smt = $em->getConnection()->prepare($sql);
         
         $smt->execute(array(':id'=>$name));
        
         
         
         $return=array("responseCode"=>200, "greeting"=>'Good');

         $this->getDoctrine()->getEntityManager()->commit();
        }
        catch(Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            $return=array("responseCode"=>400, "greeting"=>'Bad');

        }
        
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
     }
     
     public function cambiar_consolidadorAction()
     {
         return $this->render('AEConsolidarBundle:Default:cambiar_consolidador.html.twig');
     }
     
     public function cambiar_consolidador_updateAction()
     {
            $request = $this->get('request');
        $name=$request->request->get('formName');
        $lista = $request->request->get('lista');
        
         $datos = array();

        parse_str($name,$datos);
        
     
        $em = $this->getDoctrine()->getEntityManager();
        
        // $this->getDoctrine()->getEntityManager()->beginTransaction();
        $return = null;
        
        $this->getDoctrine()->getEntityManager()->beginTransaction();
        try{
            
     
        $sql = "select update_consolida_consolidador(:id1,:id2,:idp)";
        
         $smt = $em->getConnection()->prepare($sql);
         
         $smt->execute(array(':id1'=>$lista[0],':id2'=>$datos['select_consolidador'],':idp'=>$lista[6]));
 
         $return=array("responseCode"=>200, "greeting"=>'Good');

         $this->getDoctrine()->getEntityManager()->commit();
        }
        catch(Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            $return=array("responseCode"=>400, "greeting"=>'Bad');

        }
        
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
     }
     
     public function lista_consolidadosAction()
     {
         return $this->render('AEConsolidarBundle:Default:lista_consolidados.html.twig');
     }
     
     public function lista_seguirAction()
     {
         return $this->render('AEConsolidarBundle:Default:lista_seguir.html.twig');
     }
}
