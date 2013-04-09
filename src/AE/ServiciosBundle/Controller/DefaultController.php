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


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEServiciosBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function regionAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
    
        $em = $this->getDoctrine()->getEntityManager();
       
        $sql = 'select coddepartamento, departamento from ubigeo group by coddepartamento, departamento';
            
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();        
    
        return new Response($serializer->serialize($redes, 'json')); 
    }
    
    public function provinciaAction($id)
    {      
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
    
        $em = $this->getDoctrine()->getEntityManager();
       
        $sql = 'select codprovincia, provincia from ubigeo where coddepartamento=:id group by codprovincia, provincia';
      
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetchAll();

       return new Response($serializer->serialize($redes, 'json'));         
    }
    public function distritoAction($dep, $prov)
    {
       $sql = 'select * from ubigeo where coddepartamento=:iddep and codprovincia=:idprov group by coddistrito, distrito,id';
 
       $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
    
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$dep,':idprov'=>$prov));
 
        $redes = $smt->fetchAll();
   
       return new Response($serializer->serialize($redes, 'json'));
    }
    
    public function ubigeoAction($id)
    {
        $sql = 'select * from ubigeo where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
       
    }
    
    //ubicacion por id
    public function ubicacionidAction($id)
    {
         $sql = 'select * from ubicacion where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }


    //iglesias 
    public function iglesiaAction()
    {
          $sql = 'select * from iglesia';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes[0]);
    }
    //iglesia por id
    
    public function iglesiaidAction($id)
    {
         $sql = 'select * from iglesia where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
      
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }


    //lista de redes activas
    public function redAction()
    {
        $sql = 'select * from red where activo=true';
 
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
    }
    
    //red por id
    
    public function redidAction($id)
    {
        $sql = "select * from red where activo=true and red.id = :id";
    
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes); 
    }
    //celula id
    public function celulaAction($id)
    {
        $sql = 'select * from celula where activo=true and id_red=:idred ';
 
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':idred'=>$id));
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
    }
    //celula red id
    
    public function celulaidAction($red ,$id )
    {
        
        $sql = 'select * from celula where activo=true and id_red=:red and id=:id ';
 
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id,':red'=>$red));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
       
    }
     
    public function lugarAction()
    {
         $sql = 'select * from lugar';
 
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
    }
    
    //lugar por id
    public function lugaridAction($id)
    {
         $sql = 'select * from lugar where id=:id';
 
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
    }
    
    
    //lista de personas con red, 
    
    public function listapersonaAction()
    {
       
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        
        $resultado = "";
        
        $con1 = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Miembro');
        
        $per;
        $todo = array();
        
       
        foreach($personas as $per)
        {
            $miembro = $con1->findOneBy(array('id'=>$per->getId()));
            if($miembro!=NULL )
                if($miembro->getIdRed()!=NULL)
                    $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>$miembro->getIdRed()->getId(),
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y'));
                else
                    $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>'-','fecha'=>$miembro->getFechaObtencion()->format('d-m-Y'));
            
        
            
        }
        
        return new JsonResponse ((array('aaData'=>$todo)));
     
    }
    
    
    //lista de personas sin red
    public function asignarpersonaAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
  
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        
        $resultado = "";
        
        $con1 = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Miembro');
        
        $per;
        $todo = array();
        
       
        foreach($personas as $per)
        {
            $miembro = $con1->findOneBy(array('id'=>$per->getId()));
            if($miembro!=NULL )
                if($miembro->getIdRed()==NULL)
                  $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>'-','fecha'=>$miembro->getFechaObtencion()->format('d-m-Y'));
            
        
            
        }
        
        return new JsonResponse ((array('aaData'=>$todo)));
    }
    
    public function personaAction($id)
    {        
        $sql_persona = "select * from persona inner join ubicacion on (ubicacion.id=persona.id_ubicacion) where persona.id = :id";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql_persona);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }
    
    public function miembroAction($id)
    {
         $sql = "select * from miembro where miembro.id = :id";
   
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }
    
    public function nuevoconvertidoAction($id)
    {
         $sql = "select * from nuevo_convertido where nuevo_convertido.id = :id and consolidado=false";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }

    public function listaconvertidosAction()
    {
        $sql = "select *from nuevos_convertidos";
        
        $em = $this->getDoctrine()->getEntityManager();
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $redes = $smt->fetchAll();
                
        return new JsonResponse(array('aaData'=>$redes));
    }
    public function usuarioAction()
    {
        $sql = "select id, nombre from usuario";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes);
    }

    public function usuarioidAction($id)
    {
        $sql = "select id, nombre from usuario where usuario.id_persona = :id";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
    }
    
    //redes sociales por persona
    public function redsocialAction($id)
    {
        $sql = "select * from red_social where red_social.id_persona = :id";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetchAll();
   
       return new JsonResponse($redes); 
    }

    //servicio permiso por id
    public function permisoidAction($id)
    {
        
        $permiso = array();
        
        $permiso['lider'] = FALSE;
        $permiso['lider_red'] = FALSE;
        $permiso['pastor_asociado'] = FALSE;
        $permiso['misionero'] = FALSE;
        $permiso['pastor_ejecutivo'] = FALSE;
        $permiso['estudiante'] = FALSE;
        $permiso['consolidador'] = FALSE;

        $em = $this->getDoctrine()->getEntityManager();
 
         $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //lider
                $sql = "select * from lider where lider.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['lider']= TRUE;
                }
                
                //lider_red
                
                $sql = "select * from lider_red where lider_red.id = :id and activo=true";
                 
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['lider_red']= TRUE;
                }
                
                //pastor_asociado
                $sql = "select * from pastor_asociado where pastor_asociado.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['pastor_asociado']= TRUE;
                }
                
                //misionero
                $sql = "select * from misionero where misionero.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['misionero']= TRUE;
                }
                
                //pastor_ejecutivo
                $sql = "select * from pastor_ejecutivo where pastor_ejecutivo.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['pastor_ejecutivo']= TRUE;
                }
                
                //estudiante
                $sql = "select * from estudiante where estudiante.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['estudiante']= TRUE;
                }
                
                //consolidador
                $sql = "select * from consolidador where consolidador.id = :id";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                
                if(count($redes)>0)
                {   
                    $permiso['consolidador']= TRUE;
                }
                
                $this->getDoctrine()->getEntityManager()->commit();
                
               
                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
   
               throw $e;
            }
       
        
       return new JsonResponse($permiso); 
    }
    
    public function miembroallAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        
        $resultado = "";
        
        $con1 = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Miembro');
        
        $per;
        $todo = array();
        
       
        foreach($personas as $per)
        {
            $miembro = $con1->findOneBy(array('id'=>$per->getId()));
            if($miembro!=NULL && $miembro->getActivo()==TRUE)
            {
                if($miembro->getIdRed()!=NULL )
                {
                    if($miembro->getIdCelula()!=NULL)
                        $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>$miembro->getIdRed()->getId(),
                            'celula'=>$miembro->getIdCelula()->getId(),
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y'));
                    else
                        $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>$miembro->getIdRed()->getId(),
                            'celula'=>'-',
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y')); 
                
                }
                else
                {
                    $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>'-', 'celula'=>'-',
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y')); 
                
                }
        
            }
        }
        
        return new JsonResponse ((array('aaData'=>$todo)));
    }
  
    public function miembroredAction($red)
    {
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        
        $resultado = "";
        
        $con1 = $this->getDoctrine()->getEntityManager()->getRepository('AEDataBundle:Miembro');
        
        $per;
        $todo = array();
        
       
        foreach($personas as $per)
        {
            $miembro = $con1->findOneBy(array('id'=>$per->getId()));
            
            if($miembro!=NULL && $miembro->getActivo()==TRUE)
            {
                if($miembro->getIdRed()!=NULL && strncmp($miembro->getIdRed()->getId(),$red,  strlen($red))==0)
                {
                    if($miembro->getIdCelula()!=NULL)
                        $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>$miembro->getIdRed()->getId(),
                            'celula'=>$miembro->getIdCelula()->getId(),
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y'));
                    else
                        $todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre(),
                            'apellidos'=>$per->getApellidos(),'edad'=>$per->getEdad(),
                            'red'=>$miembro->getIdRed()->getId(),
                            'celula'=>'-',
                            'fecha'=>$miembro->getFechaObtencion()->format('d-m-Y')); 
                
                }
               
        
            }
        }
        
        return new JsonResponse ((array('aaData'=>$todo)));
    }
    
    public function pastorasocAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select pastor_asociado.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, pastor_asociado.fecha_obtencion from pastor_asociado inner join persona on (pastor_asociado.id = persona.id) where pastor_asociado.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = array();
        while ($result = $smt->fetch())
        {
            $sql1 = "select red.id from pastor_asociado, red where red.id_pastor_asociado = :id";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':id'=>$result['id']));
            
            $final = $smt1->fetch();
            
            
            
            if($final==false)
            {
                $todo[] = array('id'=>$result['id'],'nombre'=>$result['nombre'],
                            'apellidos'=>$result['apellidos'],'edad'=>$result['edad'],
                            'red'=>'-',
                            'celular'=>$result['celular'],
                            'telefono'=>$result['telefono'],
                            'fecha inicio'=>$result['fecha_obtencion']);
                
            }  
            else {
                $todo[] = array('id'=>$result['id'],'nombre'=>$result['nombre'],
                            'apellidos'=>$result['apellidos'],'edad'=>$result['edad'],
                            'red'=>$final['id'],
                            'celular'=>$result['celular'],
                            'telefono'=>$result['telefono'],
                            'fecha inicio'=>$result['fecha_obtencion']); 
            }
        }
        
     
        return new JsonResponse(array('aaData'=>$todo));        
    }
  
    public function pastorejecAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select pastor_ejecutivo.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, pastor_ejecutivo.fecha_obtencion from pastor_ejecutivo inner join persona on (pastor_ejecutivo.id = persona.id ) where pastor_ejecutivo.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
     
        return new JsonResponse(array('aaData'=>$todo));      
    }
    
    public function lider_redAction()
    {
          $em = $this->getDoctrine()->getEntityManager();

        $sql = "select lider_red.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, lider_red.fecha_obtencion from lider_red inner join persona on (lider_red.id = persona.id) where lider_red.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = array();
        while ($result = $smt->fetch())
        {
            $sql1 = "select red.id from lider_red, red where red.id_lider_red = :id";
            $smt1 = $em->getConnection()->prepare($sql1);
            $smt1->execute(array(':id'=>$result['id']));
            
            $final = $smt1->fetch();
            
            
            
            if($final==false)
            {
                $todo[] = array('id'=>$result['id'],'nombre'=>$result['nombre'],
                            'apellidos'=>$result['apellidos'],'edad'=>$result['edad'],
                            'red'=>'-',
                            'celular'=>$result['celular'],
                            'telefono'=>$result['telefono'],
                            'fecha inicio'=>$result['fecha_obtencion']);
                
            }  
            else {
                $todo[] = array('id'=>$result['id'],'nombre'=>$result['nombre'],
                            'apellidos'=>$result['apellidos'],'edad'=>$result['edad'],
                            'red'=>$final['id'],
                            'celular'=>$result['celular'],
                            'telefono'=>$result['telefono'],
                            'fecha inicio'=>$result['fecha_obtencion']); 
            }
        }
        
     
        return new JsonResponse(array('aaData'=>$todo));   
    }
    
    public function misionerosAction()
    {
         $em = $this->getDoctrine()->getEntityManager();

        $sql = "select misionero.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, misionero.fecha_obtencion from misionero inner join persona on (misionero.id = persona.id) where misionero.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo));
        
    }
    
   //lista de usuarios
   public function listausuarioAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select  usuario.id_persona as id, usuario.nombre as usuario, persona.nombre, persona.apellidos, persona.edad, persona.email from usuario inner join persona on persona.id=usuario.id_persona";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
   //lista lideres de red
   public function lista_liderAction()
   {
        $em = $this->getDoctrine()->getEntityManager();
        
        try
        {
            $this->getDoctrine()->getEntityManager()->beginTransaction();

            $sql = " select  * from lista_lideres_red_sin";
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            $this->getDoctrine()->getEntityManager()->commit();
        }
        catch (Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            throw $e;
        }
        
        return new JsonResponse($todo);
   }
   
   public function lista_pastor_asocAction()
   {
         $em = $this->getDoctrine()->getEntityManager();
         $todo = null;
        
        try
        {
            $this->getDoctrine()->getEntityManager()->beginTransaction();

            $sql = "select * from lista_pastor_asoc_sin";
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            $this->getDoctrine()->getEntityManager()->commit();
        }
        catch (Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            throw $e;
        }
        
        return new JsonResponse($todo);
   }
   
   public function nuevos_consolidadoresAction()
   {
       $em = $this->getDoctrine()->getEntityManager();

        $sql = " select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha from miembro inner join persona on persona.id = miembro.id where miembro.activo=true and miembro.apto_consolidar=false;
";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
   public function lconsolidadoresAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha, consolidador.activo from consolidador left join miembro on miembro.id = consolidador.id left join persona on persona.id=miembro.id" ;
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
   public function l_act_consolidadoresAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red as red, miembro.id_celula as celula, miembro.fecha_obtencion as fecha, consolidador.activo from consolidador left join miembro on miembro.id = consolidador.id left join persona on persona.id=miembro.id where consolidador.activo=true ";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse($todo);
   }
   
   public  function lista_espiritualAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select * from leche_espiritual";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse($todo);
   }
   
   public function leche_esp_temasAction($id)
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select * from tema_leche where id_leche_espiritual = :id ";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse($todo);
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

        $sql = " select id_consolida as id, id_tema_leche as leche, fecha_hora_inicio as inicio, fecha_hora_fin as fin, fecha_hora_limite as limite, tema_leche.titulo  from many_consolidacion_has_many_tema_leche inner join tema_leche on tema_leche.id = many_consolidacion_has_many_tema_leche.id_tema_leche where id_consolida = :id ";    
        
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$cons));
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse($todo);
   }

   public function consolidadorAction($id)
   {
         $em = $this->getDoctrine()->getEntityManager();


        $sql = "select persona.id, persona.nombre, persona.apellidos from consolidador left join persona on persona.id = consolidador.id where consolidador.activo = true and consolidador.id =:id";
          
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $todo = $smt->fetch();
        
        return new JsonResponse($todo); 
   }
   
     public function pordescartarAction()
   {
       $em = $this->getDoctrine()->getEntityManager();


        $sql = "select * from descartar";
          
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo)); 
   }
   
   public function lista_descartadosAction()
   {
       $em = $this->getDoctrine()->getEntityManager();


        $sql = "select * from lista_descartar";
          
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        return new JsonResponse(array('aaData'=>$todo)); 
   }
 
   public function consolida_idAction($id)
   {
       $em = $this->getDoctrine()->getEntityManager();

       
        $sql = " select consolida.fecha_inicio, consolida.fecha_fin, consolida.fecha_pausa, consolida.fecha_reanudacion, consolida.id_consolidador, consolida.id, persona.nombre, 
persona.apellidos from consolida left join persona on persona.id=consolida.id_consolidador where consolida.id_miembro=:id";
         
       
        //$sql = "select * from consolida where consolida.id=:id";
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $consolida = $smt->fetch();
             
        return new JsonResponse($consolida); 
       
   }
   
   public function leche_consolidaAction($id)
   {
       $em = $this->getDoctrine()->getEntityManager();
       
       $sql = "select  *from many_consolidacion_has_many_tema_leche inner join tema_leche on tema_leche.id = many_consolidacion_has_many_tema_leche.id_tema_leche 
           where many_consolidacion_has_many_tema_leche.id_consolida =:id";
       
       $smt = $em->getConnection()->prepare($sql);
       $smt->execute(array(':id'=>$id));
       
       $leche = $smt->fetchAll();
       
       $n = count($leche);
       
       $cadena = "";
       
       for($i=0; $i<$n; $i++)
       {
           $temp = " <tr>";
           
           $temp = $temp." <td> ".$leche[$i]['titulo']."</td>";
           $temp = $temp." <td> ".$leche[$i]['fecha_hora_inicio']."</td>";
           $temp = $temp." <td> ".$leche[$i]['fecha_hora_fin']."</td>";
           $temp = $temp." <td> ".$leche[$i]['fecha_hora_limite']."</td>";
           $temp = $temp."</tr> ";
           $cadena = $cadena.$temp;
       }
  
      // return new Response("<table> <thead> <tr> <td>uno</td> <td>dos</td> <td>tres</td> <td>cuatro</td> </tr> </thead> <tbody>".$cadena." </tbody> </table>");
       
       return new Response($cadena);

   }

   
   public function lider_red_idAction($id)
   {
       $em = $this->getDoctrine()->getEntityManager();
       
       $sql = "select red.id as redid,persona.id , persona.nombre, persona.apellidos from red inner join persona on persona.id = red.id_lider_red where red.id =  :id";
   
       $smt = $em->getConnection()->prepare($sql);
       
       $smt->execute(array(':id'=>$id));
       
       $todo = $smt->fetch();
       
       return new JsonResponse($todo);
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
   
   public function consolidado_terminoAction()
   {
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from consolidado_termino";
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
   
   public  function consolidado_seguirAction()
   {
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from consolidando";
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
   
   public function clase_celulaAction()
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
   
   public function enviar_lista_celulaAction($id)
   {
         $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       $retorno = "";
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from asistencia_celula(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));
           $todo = $smt->fetchAll();
           
           
           $n = count($todo);
           
           for($i=0; $i<$n; $i++)
           {
               $temp = "<tr> <td>".$todo[$i]['id']."</td> <td>".$todo[$i]['nombre']."</td> <td>". $todo[$i]['apellidos']."</td> <td class='table-checkbox'> <input id= 'as".strval($i)."' name='as".strval($i)." 'class='selected-checkbox' type='checkbox'> </td> </tr>";
               $retorno = $retorno.$temp;
           }
  
           $this->getDoctrine()->getEntityManager()->commit();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new Response($retorno);  
   }
   
   public function enviar_datos_tema_celulaAction($id)
   {
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $todo = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $sql = "select *from ver_tema_celula(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));
           $todo = $smt->fetch();
           
           $this->getDoctrine()->getEntityManager()->commit();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse($todo);
   }
   
   public function enviar_asistencia_celulaAction($id)
   {
        $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $est = array();
       
       try
       {
           $em = $this->getDoctrine()->getEntityManager();
           $con = $em->getRepository('AEDataBundle:ClaseCell');
           $rest = $con->findOneBy(array('id'=>$id));
           
           
           $est['id']= $rest->getId();
           $est['ofrenda'] = $rest->getOfrenda();
           
           if($rest->getFechaDicto()!=NULL)
            $est['fecha'] = $rest->getFechaDicto()->format('d-m-Y');
           else
            $est['fecha'] ='';
           
           $this->getDoctrine()->getEntityManager()->commit();
       }
       catch (Exception $e)
       {
           $this->getDoctrine()->getEntityManager()->rollback();
           $this->getDoctrine()->getEntityManager()->close();
       }
       
       return new JsonResponse($est);
   }
}
