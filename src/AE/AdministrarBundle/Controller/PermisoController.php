<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Rol;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\LecheEspiritual;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\Encargado;
use AE\DataBundle\Entity\Lider;
use AE\DataBundle\Entity\Estudiante;
use AE\DataBundle\Entity\Docente;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class PermisoController extends Controller
{
      //controladores para registro de permisos
    
    public function permisoAction()
    {
         $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_ADMIN'))
            return $this->render('AEAdministrarBundle:otro:permiso.html.twig');        
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
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
           if(strpos($name,'usuario')!==false)
                  $user = $datos['usuario'];
                   
           if(strpos($name,'estudiante')!==false)
                   $estudiante= $datos['estudiante'];
           
           if(strpos($name, 'misionero')!==false)
            $misionero = $datos['misionero'];
           
           
           if(strpos($name, 'lider_red')!==false)
              $lider = $datos['lider_red'];
           
           if(strpos($name, 'ganar')!==FALSE)
                   $e_ganar = $datos['ganar'];
           
           if(strpos($name, 'consolidar')!==FALSE)
                   $e_consolidar = $datos['consolidar'];
           
           if(strpos($name,'discipular')!==FALSE)
                   $e_discipular = $datos['discipular'];
           
           if(strpos($name,'enviar')!==FALSE)
                   $e_enviar = $datos['enviar'];
           
           if(strpos($name, 'pastor_asoc')!==false)
            $pastor_aso = $datos['pastor_asoc'];
           
           
           
           if(strpos($name, 'pastor_eje')!==false)
            $pastor_eje = $datos['pastor_eje'];
          
           if(strpos($name, 'consolidador')!==false)
            $consolidador = $datos['consolidador'];
           
           if(strpos($name, 'admin')!==false)
                   $administrador = $datos['admin'];
           
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
                        $var->setTipo(0);
                        $em->persist($var);
                        $em->flush();
                    }
                
                }
                
                /*
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
          $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_ADMIN'))
            return $this->render('AEAdministrarBundle:otro:modificarpermiso.html.twig');
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }

    private function en_arreglo_rol($arreglo , $rol)
    {
        $result=FALSE;
        foreach ($arreglo as $value) {
            if($value->getId() == $rol->getId())
            {
                $result = TRUE;
            }
        }
        
        return $result;
    }

    public function modpermisoupdateAction()
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
         $docente = NULL;
         $misionero = NULL;         
         $e_ganar = NULL;
         $e_consolidar = NULL;
         $e_discipular = NULL;
         $e_enviar = NULL;
         $pastor_aso = NULL;
         $pastor_eje = NULL;
         $administrador = NULL;
         $id = NULL;
         $usuario = NULL;
         
         $i_user = NULL;
         $i_pass = NULL;
         
         $em = $this->getDoctrine()->getEntityManager();
         
         
        if($name!=NULL)
        {
            
           if(strpos($name, 'code')!==false)
           { $id = $datos['code'];
            if(strlen($id)==0)
            {
               $return=array("responseCode"=>600, "greeting"=>"Bad");
               $return=json_encode($return);//jscon encode the array
        
               return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
  
            }
           }
           
           
              if(strpos($name, 'celula')!==false)
           {  $cel = $datos['celula'];
           
           }
           if(strpos($name,'usuario')!==false)
                  $user = $datos['usuario'];
                   
           if(strpos($name,'estudiante')!==false)
                   $estudiante= $datos['estudiante'];
           
           if(strpos($name, 'misionero')!==false)
            $misionero = $datos['misionero'];
           
           
           if(strpos($name, 'lider_red')!==false)
              $lider = $datos['lider_red'];
           
           if(strpos($name, 'ganar')!==FALSE)
                   $e_ganar = $datos['ganar'];
           
           if(strpos($name, 'consolidar')!==FALSE)
                   $e_consolidar = $datos['consolidar'];
           
           if(strpos($name,'discipular')!==FALSE)
                   $e_discipular = $datos['discipular'];
           
           if(strpos($name,'enviar')!==FALSE)
                   $e_enviar = $datos['enviar'];
           
           if(strpos($name, 'pastor_asoc')!==false)
            $pastor_aso = $datos['pastor_asoc'];
           
           if(strpos($name, 'pastor_eje')!==false)
            $pastor_eje = $datos['pastor_eje'];
          
           if(strpos($name, 'consolidador')!==false)
            $consolidador = $datos['consolidador'];
           
           if(strpos($name, 'admin')!==false)
                   $administrador = $datos['admin'];
        
           if(strpos($name,'docente')!==false)
                   $docente = $datos['docente'];
           
           if(strpos($name,'inputUsuario')!==false)
                   $i_user = $datos['inputUsuario'];
           
           if(strpos($name,'inputPassword')!==false)
                   $i_pass = $datos['inputPassword'];
           
           
           
           $em->beginTransaction();
           
           try {
               
          
                $prev = $em->getRepository('AEDataBundle:Persona');
                $persona = $prev->findOneBy(array('id'=>$id));
                
                
                if($persona==NULL)
                {
                    $em->rollback();
                    $em->close();
                     $return=array("responseCode"=>400,  "greeting"=>'bad');

               
                     $return=json_encode($return);//jscon encode the array
        
                     return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
                     
                }
                
                $con = $em->getRepository('AEDataBundle:Rol');          
                
                
                if(strlen($user)>0)
                {
                    
                     //roles
                    $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
                    
                    $usuario  = new Usuario();
                    $usuario->setIdPersona($persona);
                    
                    $pass = $datos['inputPassword'];
                    $usuario->setPassword($pass);
                    $usuario->setNombre($i_user);
                    $usuario->addIdRol($rol);
                } 
                else
                {
                    $l_usuarios = $em->getRepository('AEDataBundle:Usuario');
                    $usuario    = $l_usuarios->findOneBy(array('idPersona'=>$persona));
                    
                    
                   
                }
         
                
                 $como = $em->getRepository('AEDataBundle:Lider');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                     
                 $rol = $con->findOneBy(array('nombre'=>'ROLE_LIDERSIN')); 
                     
                     if($result ==NULL)
                     {
                        if(strlen($cel)>0) //activar lider celula
                        {
                        $var = new Lider();
                        $var->setId($persona);
                        $var->setFechaObtencion(new \DateTime());
                        $var->setActivo(TRUE);
                        $var->setTipo(0);
                        $var->setPadre(NULL);
                        
                        $em->persist($var);
                        $em->flush();                        

                        $usuario->addIdRol($rol);
                        
                        }
                    }
                    else
                    {
                        if((strlen($cel)>0)) //activar permiso
                        {
                            //activar permiso
                             $result->setActivo(TRUE); 
                             $result->setTipo(0);
                             $result->setPadre(NULL);
                             
                             $lista = $usuario->getIdRol();
                             
                            if($this->en_arreglo_rol($lista, $rol)==FALSE)
                             {
                                 $usuario->addIdRol($rol);
                             }
                        }
                        else
                        {
                            
                             $rol_lider = $con->findOneBy(array('nombre'=>'ROLE_LIDER')); 
                             $rol_lider12 = $con->findOneBy(array('nombre'=>'ROLE_LIDER12')); 
                             $rol_lider144 = $con->findOneBy(array('nombre'=>'ROLE_LIDER144')); 
                             $rol_lider1728 = $con->findOneBy(array('nombre'=>'ROLE_LIDER1728')); 

                             $l_roles = array();
                             
                             $l_roles[] = $rol;
                             $l_roles[] = $rol_lider;
                             $l_roles[] = $rol_lider12;
                             $l_roles[] = $rol_lider144;
                             $l_roles[] = $rol_lider1728;

                             //desactivar permiso
                             $result->setActivo(FALSE);     
                             foreach ($l_roles as $key => $roles) {
                                $sql = "select eliminar_rol_user(:rol,:id)";
                                $smt = $em->getConnection()->prepare($sql);
                             
                                $smt->execute(array(':rol'=>$roles->getId(),':id'=>$id));
                             }
                             
                             $result->setActivo(FALSE); 
                             $result->setTipo(0);
                             $result->setPadre(NULL);
                             
                        }
                        $em->persist($result);
                        $em->flush(); 
                    }

                    
                    
                    //lider de red
                   $como = $em->getRepository('AEDataBundle:LiderRed');
                    $result = $como->findOneBy(array('id'=>$persona->getId()));
                    $rol = $con->findOneBy(array('nombre'=>'ROLE_LIDER_RED')); 

                        
                 
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

                        $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        if((strlen($lider)>0)) //activar permiso
                        {
                             //activar permiso
                            $result->setActivo(TRUE);
                             $lista = $usuario->getIdRol();
                  
                             if($this->en_arreglo_rol($lista, $rol)==FALSE)
                             {
                                 $usuario->addIdRol($rol);
                             }

                        }
                        else
                        {
                            //desactivar permiso
                            $result->setActivo(FALSE);
                            $sql = "select eliminar_rol_user(:rol,:id)";
                            $smt = $em->getConnection()->prepare($sql);
                            $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                        }
                        $em->persist($result);
                        $em->flush(); 
                    }
         
                    
                            //consolidador
                $como = $em->getRepository('AEDataBundle:Consolidador');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                //$em->clear();

                $rol = $con->findOneBy(array('nombre'=>'ROLE_CONSOLIDADOR')); 
                
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

                        $usuario->addIdRol($rol);
                    }
                }
                else
                {
                    if(strlen($consolidador)>0)
                    {
                        $result->setActivo(TRUE);
                        $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                    }
                    $em->persist($result);
                    $em->flush();
                }
               
                 
               //estudiante
               $como = $em->getRepository('AEDataBundle:Estudiante');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                //$em->clear();
                
               $rol = $con->findOneBy(array('nombre'=>'ROLE_ESTUDIANTE')); 

                
                if($result==NULL)
                {
                    
                    if(strlen($estudiante)>0)
                    {
                        $var = new Estudiante();
                        $var->setId($persona);
                        $var->setFechaInicio(new \DateTime());
                        
                        $var->setActivo(TRUE);
                        
                        $em->persist($var);
                        $em->flush();

                        $usuario->addIdRol($rol);
                    }
                }
                else
                {
                    if(strlen($estudiante)>0)
                    {
                        $result->setActivo(TRUE);
                        $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                    }
                    $em->persist($result);
                    $em->flush();
                }
                
                
                //docente
                   //estudiante
               $como = $em->getRepository('AEDataBundle:Docente');
                $result = $como->findOneBy(array('idPersona'=>$persona));
                //$em->clear();
                
               $rol = $con->findOneBy(array('nombre'=>'ROLE_PROFESOR')); 

                
                if($result==NULL)
                {
                    
                    if(strlen($docente)>0)
                    {
                        $var = new Docente();
                        
                        $var->setIdPersona($persona);
                        $var->setFechaInicio(new \DateTime());
                        
                        $var->setActivo(TRUE);
                        $var->setDescripcion(" ");
                        $em->persist($var);
                        $em->flush();

                        $usuario->addIdRol($rol);
                    }
                }
                else
                {
                    if(strlen($estudiante)>0)
                    {
                        $result->setActivo(TRUE);
                        $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                    }
                    $em->persist($result);
                    $em->flush();
                }
                
                
                
                 //misionero
                 $como = $em->getRepository('AEDataBundle:Misionero');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 //$em->clear();
                 $rol = $con->findOneBy(array('nombre'=>'ROLE_MISIONERO')); 
 
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

                        $usuario->addIdRol($rol);
                    }
                    
                }
                else {
                    if(strlen($misionero)>0)
                    {
                        $result->setActivo(TRUE);
                        
                        $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                         
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                    }
                    
                    $em->persist($result);
                    $em->flush();
                }
                
                
                //pastor ejecutivo
                $como = $em->getRepository('AEDataBundle:PastorEjecutivo');
                $result = $como->findOneBy(array('id'=>$persona->getId()));
                //$em->clear();

                $rol = $con->findOneBy(array('nombre'=>'ROLE_PASTOR_EJECUTIVO')); 

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

                        $usuario->addIdRol($rol);
                    }
                }
                else
                {
                    if(strlen($pastor_eje)>0)
                    {
                        $result->setActivo(TRUE);
                         $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                    }
                    else
                    {
                        $result->setActivo(FALSE);
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                    }
                    $em->persist($result);
                    $em->flush();
                    
                }
                
                
                //pastor asociado
                
                 $como = $em->getRepository('AEDataBundle:PastorAsociado');
                 $result = $como->findOneBy(array('id'=>$persona->getId()));
                 //$em->clear();
                 
                 $rol = $con->findOneBy(array('nombre'=>'ROLE_PASTOR_ASOCIADO')); 

                
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
                        

                        $usuario->addIdRol($rol);
                    }
                       
                 }
                else {
                    
                    if((strlen($pastor_aso)>0)) //activar permiso
                     {
                         //activar permiso
                        $result->setActivo(TRUE);
                        $lista = $usuario->getIdRol();
                  
                        if($this->en_arreglo_rol($lista, $rol)==FALSE)
                        {
                            $usuario->addIdRol($rol);
                        }
                     }
                     else
                     {
                        //desactivar permiso
                         $result->setActivo(FALSE);
                         
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id));
                     }
                     $em->persist($result);
                     $em->flush(); 
                }
                
                
               $rol = $con->findOneBy(array('nombre'=>'ROLE_ADMIN')); 
               
                $lista = $usuario->getIdRol();


                if(strlen($administrador)>0)
                {

                  
                     if($this->en_arreglo_rol($lista, $rol)==FALSE)
                     {
                        $usuario->addIdRol($rol);
                     }
                }
                else
                {
                     if($this->en_arreglo_rol($lista, $rol)==TRUE)
                     {
                        $sql = "select eliminar_rol_user(:rol,:id)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':rol'=>$rol->getId(),':id'=>$id)); 
                     }                     
                }
                
                //encargados
                
                
                  $encargados = array();
                 //0: ganar
                 //1: consolidar
                 //2: discipular
                 //3: enviar
                 $encargados[0] = $e_ganar;
                 $encargados[1]= $e_consolidar;
                 $encargados[2]= $e_discipular;
                 $encargados[3]= $e_enviar;
                 
                 $sql = "select * from encargado where id=:id and tipo=:ti";
        
               foreach ($encargados as $key => $value) {
                   
                   
                    $smt = $em->getConnection()->prepare($sql);
                     $smt->execute(array(':id'=>$id, ':ti'=>$key));
                     $result = $smt->fetch();
                     
                
                     
                    if($key==0)
                        $rol = $con->findOneBy(array('nombre'=>'ROLE_GANAR')); 
                    elseif ($key==1) 
                        $rol = $con->findOneBy(array('nombre'=>'ROLE_CONSOLIDAR')); 
                    elseif ($key==2) 
                        $rol = $con->findOneBy(array('nombre'=>'ROLE_DISCIPULAR')); 
                    elseif($key==3) $rol = $con->findOneBy (array('nombre'=>'ROLE_ENVIAR'));
                    
                    
                     if($result==NULL)
                    {
                         if(strlen($value)>0)
                        {
                            $var = new Encargado();
                            $var->setId($persona);
                            $var->setActivo(TRUE);
                            $var->setFechaObtencion(new \DateTime());
                            $var->setTipo($key);
                        
                            $em->persist($var);
                            $em->flush();
                           
                            $usuario->addIdRol($rol);
                        }
                       

                    }
                    else
                    {
                        if(strlen($value)>0)
                        {
                           
                            $sql1 = "select activar_encargado(:id,:tip)";
                            $smt1 = $em->getConnection()->prepare($sql1);
                            $smt1->execute(array(':id'=>$id,':tip'=>  strval($key)));
                            
                              $lista = $usuario->getIdRol();
                  
                            if($this->en_arreglo_rol($lista, $rol)==FALSE)
                            {
                                $usuario->addIdRol($rol);
                            }
                        }
                        else
                        {
                            $sql1 = "select desactivar_encargado(:id,:tip)";
                            $smt1 = $em->getConnection()->prepare($sql1);
                            $smt1->execute(array(':id'=>$id,':tip'=>  strval($key)));
                            //$name= $value;

                            $sql2 = "select eliminar_rol_user(:rol,:id)";
                            $smt2 = $em->getConnection()->prepare($sql2);
                            $smt2->execute(array(':rol'=>$rol->getId(),':id'=>$id)); 
                        }
                    }
                   
               }
    
               /*
                * $return=array("responseCode"=>200,  "greeting"=>'bad');   
                *
            $return=json_encode($return);//jscon encode the array        
            return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    */
                $usuario->setEnabled(TRUE);
               $em->persist($usuario);
               $em->flush();
                       
               $em->commit();
               $em->clear();

               
           } catch (Exception $exc) {
               
               $em->rollback();
               //$em->clear();
               $em->close();
               throw $exc;
           }


           $return=array("responseCode"=>200,  "greeting"=>$usuario->getId());
        }
        else   
            $return=array("responseCode"=>400,  "greeting"=>'bad');

               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
   

}
