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
use AE\DataBundle\Entity\TemaLeche;
use AE\DataBundle\Entity\Archivo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEAdministrarBundle:Default:index.html.twig', array('name' => $name));
    }
    
       //iglesia
    public function igleAction()
    {
      return $this->render('AEAdministrarBundle:Iglesia:regIglesia.html.twig');

    }
    //registrar iglesia
    public function addigleAction()
    {
                
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
       
        
       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $telefono = $datos['inputTelef'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];

            $em = $this->getDoctrine()->getEntityManager();         
   
            $iglesias = $em->getRepository('AEDataBundle:Iglesia');
            $iglesias = $iglesias->findAll();
            
            if(count($iglesias)==0)
            {
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //ubigeo
               $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
             
                
                  //ubicacion
                $ubicacion = new Ubicacion();
                $ubicacion->setDireccion($direccion);
                $ubicacion->setReferencia($referencia);
                $ubicacion->setLatitud($latitud);
                $ubicacion->setLongitud($longitud);
                $ubicacion->setIdUbigeo($ubigeo);
                
                
                $em->persist($ubicacion);
                $em->flush();

                $iglesia = new Iglesia();
                $iglesia->setIdUbicacion($ubicacion);
                $iglesia->setNombre($nombre);
                $iglesia->setTelefono($telefono);
                
                $em->persist($iglesia);
                $em->flush();
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>500, "greeting"=>"Bad");     
        }
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }

        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    }

    
    public function modiglesiaAction()
    {
         $em = $this->getDoctrine()->getEntityManager();         
         
          $iglesias = $em->getRepository('AEDataBundle:Iglesia');
          $igle = $iglesias->findAll();
          
          
         $em->close();
         
         
        return $this->render('AEAdministrarBundle:Iglesia:modificar.html.twig',array('id'=> $igle[0]->getId()));
    }
    
    
    public function updateiglesiaAction()
    {
                
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('other');

        $datos = array();

        parse_str($name,$datos);
    
       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $telefono = $datos['inputTelef'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];

            $em = $this->getDoctrine()->getEntityManager();         
            
            //   $em->persist($user);
            //$em->flush();
            $iglesias = $em->getRepository('AEDataBundle:Iglesia');
            $iglesia = $iglesias->findOneBy(array('id'=>$id));
            
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                
                //ubicacion
               $sql = 'UPDATE ubicacion SET id_ubigeo=:idgeo, direccion=:dir, referencia=:ref, latitud= :lat, longitud= :lng where id=:iddep';

                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idgeo'=>$distrito,':dir'=> $direccion, ':ref'=>$referencia,
                    ':lat'=>$latitud,':lng'=>$longitud, ':iddep'=>$id));
                //iglesia 
                
                $iglesia->setNombre($nombre);
                $iglesia->setTelefono($telefono);

                $em->persist($iglesia);
                $em->flush();
   
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }

       
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }

        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    }
    
    public function redAction()
    {
        return $this->render('AEAdministrarBundle:Red:regredes.html.twig');
    }
    public function addredAction()
    {
         $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);

       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $tipo_red = $datos['tipo_red'];
            $id_iglesia = $datos['iglesia_lista'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            $tip_red = $datos['tip_red'];
            $id_persona = $datos['ids'];

            $em = $this->getDoctrine()->getEntityManager();         
            
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                //checar si el codigo de la red existe 
                 $sql = 'select * from red where red.id = :red';
            
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':red'=>$nombre));
 
                $redes = $smt->fetchAll(); 
                if(count($redes)>0)
                {
                     $return=array("responseCode"=>400, "greeting"=>"Bad");     

                     $return=json_encode($return);//jscon encode the array
        
                     return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                }
                
                //ubigeo
               $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
             
                
                  //ubicacion
                $ubicacion = new Ubicacion();
                $ubicacion->setDireccion($direccion);
                $ubicacion->setReferencia($referencia);
                $ubicacion->setLatitud($latitud);
                $ubicacion->setLongitud($longitud);
                $ubicacion->setIdUbigeo($ubigeo);
                
                
                $em->persist($ubicacion);
                $em->flush();
                
                //persona 
                $personas = $em->getRepository('AEDataBundle:Persona');
                $persona = $personas->findOneBy(array('id'=>$id_persona));

                //iglesia
                $iglesias = $em->getRepository('AEDataBundle:Iglesia');
                $iglesia = $iglesias->findOneBy(array('id'=>$id_iglesia));

                //Red
                $red = new Red();
                $red->setIdIglesia($iglesia);
                $red->setIdUbicacion($ubicacion);
                $red->setTipo($tipo_red);
                $red->setId($nombre);
                $red->setActivo(TRUE);
                $red->setInicio(new \DateTime());

                //añadir al lider
                if($id_persona!=-1)
                {
                    //0 para lider
                    //1 para pastor asociado
                    if($tip_red==0)
                    {
                         //lider de red
                        //$lider = $query->getResult();
                        $lider_red = $em->getRepository('AEDataBundle:LiderRed');
                        $lider = $lider_red->findOneBy(array('id'=>$persona));
                                         
                        $sql = 'select * from red where red.id_lider_red = :red';
            
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$id_persona));
 
                        $redes = $smt->fetchAll();  
                       
                        if(count($redes)>0)
                        {
                            $return=array("responseCode"=>400, "greeting"=>"Bad");     
                            
               
                            $return=json_encode($return);//jscon encode the array
        
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                        }
                          if($lider!=NULL)
                            $red->setIdLiderRed($lider);
                    }
                    else
                    {
                        //pastor asociado 
                        $pastor_asoc = $em->getRepository('AEDataBundle:PastorAsociado');
                        $past_asoc   = $pastor_asoc->findOneBy(array('id'=>$persona));
                        
                        
                        $sql = 'select * from red where red.id_pastor_asociado = :red';
            
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':red'=>$id_persona));
 
                        $redes = $smt->fetchAll();  
                        
                        if(count($redes)>0)
                        {
                            $return=array("responseCode"=>400, "greeting"=>"Bad");     

                            $return=json_encode($return);//jscon encode the array
        
                            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
                        }
                        
                        if($pastor_asoc!=NULL)
                            $red->setIdPastorAsociado($past_asoc);
                    }
                }
                $em->persist($red);
                $em->flush();
                
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
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
         $cel = NULL;
         $lider = NULL;
         $pastor_aso = NULL;
         $misionero = NULL;
         $pastor_eje = NULL;
         $consolidador = NULL;
         $id = NULL;

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
           

            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //persona
                $prev = $em->getRepository('AEDataBundle:Persona');
                $persona = $prev->findOneBy(array('id'=>$id));
            
                
                //lider de celula
                if(strlen($cel)>0)
                {
                    $como = $em->getRepository('AEDataBundle:Lider');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                
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
             
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
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
           

            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //persona
                $prev = $em->getRepository('AEDataBundle:Persona');
                $persona = $prev->findOneBy(array('id'=>$id));
            
                
                //lider de celula
                
                 $como = $em->getRepository('AEDataBundle:Lider');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
 
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

                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
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
    
    public function asignaredAction()
    {
        return $this->render('AEAdministrarBundle:Red:asignarlider.html.twig');
        
    }
    public function addasignarliderAction()
    {
                return $this->render('AEAdministrarBundle:Red:asignarlider.html.twig');

    }

    public function lista_pastor_asociadoAction()
    {
        return $this->render('AEAdministrarBundle:listas:pastores_asoc.html.twig');
    }
  
    public function lista_pastor_ejecutivoAction()
    {
         return $this->render('AEAdministrarBundle:listas:pastores_eje.html.twig');
    }
  
    public function lista_lider_redAction()
    {
         return $this->render('AEAdministrarBundle:listas:liderred.html.twig');
    }
  
    public function lista_misioneroAction()
    {
         return $this->render('AEAdministrarBundle:listas:misionero.html.twig');
    }
  
    public function lista_miembrosAction()
    {
                return $this->render('AEAdministrarBundle:listas:miembro_red.html.twig');

    }
    
    public function lista_miembros_allAction()
    {
       return $this->render('AEAdministrarBundle:listas:miembros.html.twig');   
    }

  
    public function lista_usuariosAction()
    {
        return $this->render('AEAdministrarBundle:listas:usuario.html.twig');
    }
    public function claseAction()
    {
        return $this->render('AEAdministrarBundle:leche:clase.html.twig');
    }
    
    public function clase_regAction()
    {
        
         
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        $nombre = NULL;
        $descripcion = NULL;
        $fecha = NULL;
        $clases = NULL;

       if($name!=NULL){
                
           if(strpos($name, 'nombre')!==false)
           {
               $nombre = $datos['nombre'];           
           }
           
           if(strpos($name, 'inputDescripcion')!=false)
           {
               $descripcion = $datos['inputDescripcion'];
           }
           
           if(strpos($name,'inputFecha')!=false)
           {
               $fecha = $datos['inputFecha'];
           }
           
           if(strpos($name, 'inputClases')!=false)
           {
               $clases = $datos['inputClases'];
           }
           
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                $leche = new LecheEspiritual();
           
                if($fecha ==NULL)
                    $leche->setFechaCreacion(new \DateTime());
                else
                    $leche->setFechaCreacion(new \DateTime($fecha));
           
                $leche->setNombre($nombre);
                $leche->setResumen($descripcion);
           
                $em->persist($leche);
                $em->flush();
           
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>300, "id"=>$leche->getId() ); 

                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
    public function temas_lecheAction()
    {
           
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');
        $num = $request->request->get('num');
        
        $datos = array();

        parse_str($name,$datos);
       

       if($name!=NULL){
                
            $cont = intval($num);
            
            $lista = array();
     
            $i=0;
            for($i=0;$i< $cont;$i++)
            {
                $lista[] = $datos['tema'.strval($i)] ;           
  
            }
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
           
                for($i=0;$i<$cont; $i++)
                {
                    $tema = new TemaLeche();
                    
                    $tema->setIdLecheEspiritual($leche);
                    $tema->setTitulo($lista[$i]);
                    $em->persist($tema);
                    $em->flush();
                }   

                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>300, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
        
    }
    
    public function file_lecheAction(Request $request)
    {
        
         $name = $request->query->get('name');
         $id = $request->query->get('id');
         
         $uploadName = date("Y-m-d-H-i-s").$name;
         $url = "uploads/".$uploadName;

        $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
           
                 $file = new Archivo();
         
                $file->setFecha(new \DateTime());
                $file->setDireccion($url);
                $file->setIdLecheEspiritual($leche);
                
                $em->persist($file);
                $em->flush();
                
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
            
             $return=json_encode($return);//jscon encode the array
        
       // return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
                return new Response();
    }

    public function lista_redesAction()
    {
         return $this->render('AEAdministrarBundle:Red:lista_redes.html.twig');
    }
    
    public function lista_redes_modificarAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $code = $request->request->get('code');
        $ubicacion = $request->request->get('ubicacion');
        
        $datos = array();

        parse_str($name,$datos);
        
        $retorno = 'ok';
       
        if($name!=NULL){
            
            $tipo_red = $datos['tipo_red'];
            $iglesia  = $datos['iglesia_lista'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            $tip_red = $datos['tip_red'];
            $ids = $datos['ids'];
            
            $em = $this->getDoctrine()->getEntityManager();
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            
            //añadir las excepciones en los prepare
            
            try{
                        
                //cambio en tabla red
                if(intval($tip_red)==0)
                {
               
                    //cambio de lider
                    if(intval($ids)!= -1)
                    {                  
                                   
                        $sql = "select update_red(:idx,:tip,:igle,:lider,:pastor)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>$code,':tip'=>$tipo_red, ':igle'=>$iglesia, ':lider'=>$ids,':pastor'=>NULL));
                     
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
              
                    }

            }
            else
            {
                   //cambio pastor
                 if(intval($ids)!= -1)
                    {                  
                                   
                        $sql = "select update_red(:idx,:tip,:igle,:lider,:pastor)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>$code,':tip'=>$tipo_red, ':igle'=>$iglesia, ':lider'=>NULL,':pastor'=>$ids));
                     
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
              
                    }
            }
            
                //cambio de ubicacion
            
                $sql = "select  update_ubicacion(:idx,:dir, :refe, :lat,:lng, :ubigeo)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$ubicacion, ':dir'=>$direccion, ':refe'=>$referencia,':lat'=>$latitud,
                    ':lng'=>$longitud,':ubigeo'=>$distrito));
                $em->flush();
            
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200, "greeting"=>$retorno);
            }
            catch(Exception $e)
            {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                 $return=array("responseCode"=>400, "greeting"=>'bad');
                
                throw $e;
            }

        }
        else 
        {
          $return = array("responseCode"=>400, "greeting"=>"Bad");
        }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
    public function lista_redes_modificar_vistaAction($id)
    {
       return $this->render('AEAdministrarBundle:Red:modred.html.twig',array('id'=>$id));

    }


    public function lista_redes_eliminarAction()
    {
        $request = $this->get('request');
        $code=$request->request->get('formName');
       

       
        if($code!=NULL){
                
            
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                $sql = "select delete_red(:idx)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$code));
               
                $em->flush();
                
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>200, "greeting"=>$code ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
}
