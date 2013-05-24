<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\LecheEspiritual;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class PermisoController extends Controller
{
      //controladores para registro de permisos
    
    public function permisoAction()
    {
        return $this->render('AEAdministrarBundle:otro:permiso.html.twig');
    }
    
    //guardar permisos para cada persona
    
    public function regpermisoAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
         $cel = NULL; //celula

         $user = NULL;
         $lider = NULL;

         $consolidador = NULL;
         $estudiante = NULL;        
         $misionero = NULL;
         
         $e_ganar = NULL;
         $e_consolidar = NULL;
         $e_discipular = NULL;
         $e_enviar = NULL;

         $pastor_aso = NULL;
         $pastor_eje = NULL;
         $administrador = NULL;
         $id = NULL;
         $em = $this->getDoctrine()->getEntityManager();         


       if($name!=NULL){
                
           if(strpos($name, 'celula')!==false)
           {  $cel = $datos['celula'];
           
           }
           
           if(strpos($name, 'lider_red')!==false)
              $lider = $datos['lider_red'];
           
           if(strpos($name, 'pastor_asoc')!==false)
            $pastor_aso = $datos['pastor_asoc'];
           
           if(strpos($name, 'misionero')!==false)
            $misionero = $datos['misionero'];
           
           if(strpos($name, 'pastor_eje')!==false)
            $pastor_eje = $datos['pastor_eje'];
          
        
           
           if(strpos($name, 'consolidador')!==false)
            $consolidador = $datos['consolidador'];
           
           if(strpos($name, 'code')!==false)
           { $id = $datos['code'];
            if(strlen($id)==0)
            {
               $return=array("responseCode"=>600, "greeting"=>"Bad");
               $return=json_encode($return);//jscon encode the array
        
               return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
            }
           }
   
            $em->beginTransaction();
            try
            {
                //persona
                $prev = $em->getRepository('AEDataBundle:Persona');
                $persona = $prev->findOneBy(array('id'=>$id));
                $em->clear();
            
                /*
                //lider de celula
                if(strlen($cel)>0)
                {
                    $como = $em->getRepository('AEDataBundle:Lider');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $em->clear();
                
                    if($result ==NULL)
                    {
                        $var = new \AE\DataBundle\Entity\Lider();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                
                }
                
                //lider de red
                if(strlen($lider)>0)
                {
                 
                    $como = $em->getRepository('AEDataBundle:LiderRed');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $em->clear();
                
                    if($result==NULL)
                    {
                        $var = new \AE\DataBundle\Entity\LiderRed();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                       
                    }
                }
                
                //pastor asociado
                if(strlen($pastor_aso)>0)
                {
                    $como = $em->getRepository('AEDataBundle:PastorAsociado');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $em->clear();
                
                    if($result==NULL)
                    {
                        $var = new \AE\DataBundle\Entity\PastorAsociado();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                }
                
                //misionero
                if(strlen($misionero)>0)
                {

                    $como = $em->getRepository('AEDataBundle:Misionero');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $em->clear();
                    
                    if($result==NULL)
                    {
                         $var = new \AE\DataBundle\Entity\Misionero();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
  
                        $em->persist($var);
                        $em->flush();
                    }
                }
                
                //pastor ejecutivo
                if(strlen($pastor_eje)>0)
                {
                    $var = new \AE\DataBundle\Entity\PastorEjecutivo();
                    $var->setId($persona);
                    $var->setFechaObtencion(new \DateTime());
                    $var->setActivo(TRUE);

                    $como = $em->getRepository('AEDataBundle:PastorEjecutivo');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $em->clear();
                    
                    if($result==NULL)
                    {
                        $em->persist($var);
                        $em->flush();
                    }
                }
                
          
              //consolidador
                if(strlen($consolidador)>0)
                {

                    $sql = 'select * from consolidador where id=:iddep';
       
                    $smt = $em->getConnection()->prepare($sql);
                    $smt->execute(array(':iddep'=>$persona->getId()));
 
                    $redes = $smt->fetchAll();
                    $em->clear();
                   
                    if($redes == NULL)
                    {
                        
                        $var = new Consolidador();
                        $var->setActivo(TRUE);
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        
                        $em->persist($var);
                        $em->flush();
                       
                    }
  
                }
             
               */
                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }

    public function modpermisoAction()
    {
        return $this->render('AEAdministrarBundle:otro:modificarpermiso.html.twig');
    }


    public function modpermisoupdateAction()
    {
          $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
         $cel = NULL;
         $lider = NULL;
         $pastor_aso = NULL;
         $misionero = NULL;
         $pastor_eje = NULL;
         $estudiante = NULL;
         $consolidador = NULL;
         $id = NULL;
         
          $em = $this->getDoctrine()->getEntityManager(); 

       if($name!=NULL){
                
           if(strpos($name, 'celula')!==false)
           {  $cel = $datos['celula'];
           
           }
           
           if(strpos($name, 'lider_red')!==false)
              $lider = $datos['lider_red'];
           
           if(strpos($name, 'pastor_asoc')!==false)
            $pastor_aso = $datos['pastor_asoc'];
           
           if(strpos($name, 'misionero')!==false)
            $misionero = $datos['misionero'];
           
           if(strpos($name, 'pastor_eje')!==false)
            $pastor_eje = $datos['pastor_eje'];
           
           if(strpos($name, 'estudiante')!==false)
            $estudiante = $datos['estudiante'];
           
           if(strpos($name, 'consolidador')!==false)
            $consolidador = $datos['consolidador'];
           
           if(strpos($name, 'code')!==false)
           { $id = $datos['code'];
            if(strlen($id)==0)
            {
               $return=array("responseCode"=>600, "greeting"=>"Bad");
               $return=json_encode($return);//jscon encode the array
        
               return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
            }
           }
           

                   

            $em->beginTransaction();
            try
            {
                //persona
                $prev = $em->getRepository('AEDataBundle:Persona');
                $persona = $prev->findOneBy(array('id'=>$id));
                $em->clear();
            
                
                //lider de celula
                
                 $como = $em->getRepository('AEDataBundle:Lider');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 $em->clear();
 
                 if($result ==NULL)
                 {
                    if(strlen($cel)>0) //activar celula
                    {
                        $var = new \AE\DataBundle\Entity\Lider();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                 }
                 else
                 {
                     if((strlen($cel)>0)) //activar permiso
                     {
                         //activar permiso
                         $result->setActivo(TRUE);
                     }
                     else
                     {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                     }
                     $em->persist($result);
                     $em->flush(); 
                 }

               
                //lider de red
                 $como = $em->getRepository('AEDataBundle:LiderRed');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 $em->clear();
                 
                 if($result ==NULL)
                 {
                    if(strlen($lider)>0) //activar lider de red
                    {
                        $var = new \AE\DataBundle\Entity\LiderRed();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                 }
                 else
                 {
                     if((strlen($lider)>0)) //activar permiso
                     {
                         //activar permiso
                         $result->setActivo(TRUE);
                     }
                     else
                     {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                     }
                     $em->persist($result);
                     $em->flush(); 
                 }
         
                //pastor asociado
                
                 $como = $em->getRepository('AEDataBundle:PastorAsociado');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 $em->clear();
                
                 if($result==NULL)
                 {
                    if(strlen($pastor_aso)>0) //pastor asociado
                    {
                        $var = new \AE\DataBundle\Entity\PastorAsociado();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                       
                 }
                else {
                    
                    if((strlen($pastor_aso)>0)) //activar permiso
                     {
                         //activar permiso
                         $result->setActivo(TRUE);
                     }
                     else
                     {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                     }
                     $em->persist($result);
                     $em->flush(); 
                }
                
                
                //misionero
                 $como = $em->getRepository('AEDataBundle:Misionero');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 $em->clear();
                    
                if($result==NULL)
                {

                    if(strlen($misionero)>0)
                    {
                         $var = new \AE\DataBundle\Entity\Misionero();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
  
                        $em->persist($var);
                        $em->flush();
                    }
                    
                }
                else {
                    if(strlen($misionero)>0)
                    {
                        $result->setActivo(TRUE);
                    }
                    else
                    {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                    }
                    
                    $em->persist($result);
                    $em->flush();
                }
                
                //pastor ejecutivo
                $como = $em->getRepository('AEDataBundle:PastorEjecutivo');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                $em->clear();
                
                if($result==NULL)
                {
    
                    if(strlen($pastor_eje)>0)
                    {
                        $var = new \AE\DataBundle\Entity\PastorEjecutivo();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);

                        $em->persist($var);
                        $em->flush();
                    }
                }
                else
                {
                    if(strlen($pastor_eje)>0)
                    {
                        $result->setActivo(TRUE);
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                    }
                    $em->persist($result);
                    $em->flush();
                    
                }
                
                $como = $em->getRepository('AEDataBundle:Estudiante');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                $em->clear();
                
                if($result==NULL)
                {
                    
                    if(strlen($estudiante)>0)
                    {
                         $var = new \AE\DataBundle\Entity\Estudiante();
                        $var->setIdPersona($persona);
                        $var->setFechaInicio(new \DateTime());
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();
                    }
                }
                else
                {
                    if(strlen($estudiante)>0)
                    {
                        $result->setActivo(TRUE);
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                    }
                    $em->persist($result);
                    $em->flush();
                }
                
                //consolidador
                $como = $em->getRepository('AEDataBundle:Consolidador');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                $em->clear();
                
                if($result==NULL)
                {
                    if(strlen($consolidador)>0)
                    {
                         $var = new Consolidador();
                        $var->setActivo(TRUE);
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        
                        $em->persist($var);
                        $em->flush();
                    }
                }
                else
                {
                    if(strlen($consolidador)>0)
                    {
                        $result->setActivo(TRUE);
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                    }
                    $em->persist($result);
                    $em->flush();
                }

                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
   

}
