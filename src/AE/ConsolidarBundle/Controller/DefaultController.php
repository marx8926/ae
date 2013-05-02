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

}
