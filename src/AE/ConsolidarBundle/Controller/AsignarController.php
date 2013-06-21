<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Consolida;


class AsignarController extends Controller
{
     public function asignar_consolidarAction()
     {
         
         $securityContext = $this->get('security.context');
        
         if($securityContext->isGranted('ROLE_LIDER_RED'))
         {
             
             $ganador = $securityContext->getToken()->getUser()->getIdPersona();
                $red = NULL;
                $em = $this->getDoctrine()->getEntityManager();
        
                if($ganador != NULL)
                {
                    $sql = "select * from get_red_persona(:id)";
                    $smt = $em->getConnection()->prepare($sql);
                    $smt->execute(array(':id'=>$ganador->getId()));
                    $req = $smt->fetch();
                    if(count($req)>0)
                    $red = $req['red'];
                }
                
               
              if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                    $red = NULL;
              
              return $this->render('AEConsolidarBundle:Default:asignar.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

     }
     
     public function registrar_asignacionAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');
        
        
        
        if(strlen($id)==0)
        {
            $return=array("responseCode"=>400, "greeting"=>"Bad1");
             
    
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
        }
        
        $num = $request->request->get('num');
        
        $num_clases = intval($num);
        
        $fechas_ini = array();
        $fechas_fin = array();
        $time_ini = array();
        $time_fin = array();
        $id_temas = array();
        
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
                $id_temas[] = $datos['id'.  strval($i)];
                
             }
                         
              $em->beginTransaction();
            try
            {
                //if($consolidador != $id) //preguntar si es q se puede
                {    
                     //consolidador
                    $consolidador_q = $em->getRepository('AEDataBundle:Consolidador');
                    $consolidador_f = $consolidador_q->findOneBy(array('id'=>$consolidador));
                  
                    //nuevo_convertido
                    
                    $per = $em->getRepository('AEDataBundle:Persona');
                    $persona = $per->findOneBy(array('id'=>$id));      
                    
                   

                    
                    //nuevo_convertido
                                 
                    $new_convert = $em->getRepository('AEDataBundle:NuevoConvertido');
                    $new_convert_f = $new_convert->findOneBy(array('id'=>$id));
                    $new_convert_f->setConsolidado(true);
                    $em->persist($new_convert_f);
                    $em->flush();
                    
                     
                    //miembro
                    $miembro = $em->getRepository('AEDataBundle:Miembro');
                    $miembro_f = $miembro->findOneBy(array('id'=>$id));
                                        
                    if($miembro_f==NULL)
                    {
                        $miembro_f = new Miembro();
                        $miembro_f->setId($persona);
                        $miembro_f->setIdCelula($new_convert_f->getIdCelula());
                        $miembro_f->setIdRed($new_convert_f->getIdRed());
                        $miembro_f->setActivo(TRUE);
                        $miembro_f->setFechaObtencion(new \DateTime());
                        $miembro_f->setAptoConsolidar(FALSE);
                        
                        $em->persist($miembro_f);
                        $em->flush();
                    }
                    
                 
                    
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
                    
                    

                    //recuperar herramientas 
                    $tools = $em->getRepository('AEDataBundle:Herramienta')->findAll();
                    
                    foreach ($tools as $key => $value) {
                        $tiempo = $value->getTiempoOptimo();
                        
                        $date = new \DateTime();
                        $date->modify('+'.$tiempo);
                        
                        $sql = "select insert_consolida_herramienta(:tool,:consol,:tiempo)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':tool'=>$value->getId(),':consol'=>$consolida->getId(),
                            ':tiempo'=>$date->format('Y-m-d H:i:s')));
                    }
                    
                    
                     $n = $num_clases;
                     
                     for($i =0; $i< $n; $i++)
                     {
                         $sql ="select insert_consolida_leche(:idc, :idtl,:fIn,:fLim)";
                         
                         //, :fIn,:fLim
                         $smt = $em->getConnection()->prepare($sql);
                         
                        // $td = $todo[$i];
                         
                       if(!$smt->execute(array(':idc'=>$consolida->getId(),':idtl'=>$id_temas[$i],
                           ':fIn'=>$fechas_ini[$i].' '.$time_ini[$i],':fLim'=>$fechas_fin[$i].' '.$time_fin[$i])))
                       {
                           
                            $em->rollback();
                            $em->close();
                
                            $return=array("responseCode"=>400, "greeting"=>"Bad");

                            $return=json_encode($return);//jscon encode the array
     
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                       }
                     }
                       //agregar consolida (consolidador, nuevo_convertido)
                    
                    
                    $em->commit();
                    $em->clear();
                    $return=array("responseCode"=>200,  "greeting"=>'ook');
                }
               /* else
                {
                    $return=array("responseCode"=>400,  "greeting"=>'Bad3');

                }
                * 
                */
 
            }catch(Exception $e)
            {
               $em->rollback();
               $em->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad2");

               throw $e;
               
            }
            
           //$return=array("responseCode"=>200, "greeting"=>$time_ini);
             
         }
         else  $return=array("responseCode"=>400, "greeting"=>"Bad1");             
    
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
    }
}

