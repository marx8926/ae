<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class SeguirController extends Controller
{
     public function lista_seguirAction()
     {
         
         $securityContext = $this->get('security.context');
         
         if($securityContext->isGranted('ROLE_CONSOLIDADOR'))
         {     
            $securityContext = $this->get('security.context');
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $consolidador =NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                {
                    $red = $req['red'];
                    $consolidador= $ganador->getId();
                }
                if($securityContext->isGranted('ROLE_LIDER_RED'))
                {
                    $consolidador = NULL;
                }
                
                if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                {
                    $red =NULL;
                    $consolidador=NULL;
                }
                
            }
            
            return $this->render('AEConsolidarBundle:Default:lista_seguir.html.twig',array('red'=>$red
                    ,'consolidador'=>$consolidador));
         }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
                  
     }
     
     public function seguirAction($id)
     {
         
         $em = $this->getDoctrine()->getEntityManager();
         
         try{
             $sql = "select * from get_consolidado(:id)";
             $smt = $em->getConnection()->prepare($sql);
             $smt->execute(array(':id'=>$id));
             
             $td = $smt->fetch();
             $em->clear();
  
        }
        catch (Exception $e)
        {
            throw $e;
        }
         return $this->render('AEConsolidarBundle:Default:seguir.html.twig',array('id'=>$id,'nombre'=>$td['nombre'],
             'apellidos'=>$td['apellidos'],'inicio'=>$td['inicio'],'fin'=>$td['fin'],'consolidador'=>$td['consolidador'],
             'code'=>$td['code']));
     }
     
     
     public function seguir_updateAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $num = $request->request->get('num');
        $consol = $request->request->get('consol');
  
        $numero = intval($num);
  
        $datos = array();

        parse_str($name,$datos);
        
        $check = array();
        $fechas = array();
        $horas = array();
        
        $chequeo = 0;

        $em = $this->getDoctrine()->getEntityManager();
        
        $em->beginTransaction();

        if($name!=NULL)
        {
            try
            {
            for($i=0;$i < $numero; $i++)
            {
                $var = "check".strval($i);
                $ids = $datos["list".strval($i)];
                
                if(strpos($name, $var)!==false)
                {
                    $check[$var]=$datos[$var];
                    
                    $temp = "dia".strval($i);
                    
                    $fechas[$temp] = $datos[$temp];
                    
                    $temp_h = "hora".strval($i);
                    $horas[$temp_h] = $datos[$temp_h];
                    
                    if(strlen($fechas[$temp])>=0 && strlen($horas[$temp_h])>=0)
                    {
                        //consultar si ya actualizo las fechas
 
                            $sql = "select update_consolida_leche(:leche,:fin,:id)";
                            
                            $smt = $em->getConnection()->prepare($sql);
                            
                            $smt->execute(array(':fin'=>$fechas[$temp].' '.$horas[$temp_h], ':leche'=>$ids,
                                ':id'=>$consol));

                           // $return=array("responseCode"=>200,  "greeting"=>$ids); 
                        
                            $chequeo = $chequeo+1;
                    }
                    else $return=array("responseCode"=>500, "greeting"=>'bad');
                    
                }
                else
                {
                    if(strpos($name, 'dat'.strval($i))!==false)
                    {
                         $chequeo = $chequeo+1;
                    }
                }
                
            }
            //settear termino 
            if(intval($chequeo)==intval($numero))
            {
                $sql = "select termino_consolida(:id)";
                $smt1 = $em->getConnection()->prepare($sql);
                $smt1->execute(array(':id'=>$consol));
            }

            $return=array("responseCode"=>200, "greeting"=>'ok');

            $em->commit();
            $em->clear();
            }catch(Exception $e)
             {
                            $em->rollback();
                            $em->close();
                
                            $return=array("responseCode"=>400, "greeting"=>"Bad");

                            $return=json_encode($return);//jscon encode the array
     
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
                            
                            throw $e;
               
              }
        

            
        }
        else  $return=array("responseCode"=>400, "greeting"=>$name);
            
        
      
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
        //falta cambiar a proce
     }
     
   
}

