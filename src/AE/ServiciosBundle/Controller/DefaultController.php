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
use AE\DataBundle\Entity\NuevoConvertido;
use AE\DataBundle\Entity\Ubicacion;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AEServiciosBundle:Default:index.html.twig');
    }
  

    //ubicacion por id
    public function ubicacionidAction($id)
    {
         $sql = 'select * from ubicacion where id=:iddep';

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':iddep'=>$id));
 
        $redes = $smt->fetch();
        $em->clear();
   
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
        $em->clear();
   
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
        $em->clear();
   
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
        $em->clear();
   
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
        $em->clear();
   
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
        $em->clear();
   
       return new JsonResponse($redes);
    }
    
    
    //lista de personas con red, 
    
    public function listapersonaAction()
    {
       
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $em->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        //$em->clear();
        
        $resultado = "";
        
        $con1 = $em->getRepository('AEDataBundle:Miembro');
        
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
        $em->clear();
        
        return new JsonResponse ((array('aaData'=>$todo)));
     
    }
    
    
    //lista de personas sin red
    public function asignarpersonaAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
  
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $em->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        //$em->clear();
        $resultado = "";
        
        $con1 = $em->getRepository('AEDataBundle:Miembro');
        
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
        $em->clear();
        
        return new JsonResponse ((array('aaData'=>$todo)));
    }
    
    
    
    public function miembroAction($id)
    {
         $sql = "select * from miembro where miembro.id = :id";
   
        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
        $em->clear();
   
       return new JsonResponse($redes);
    }

    public function usuarioAction()
    {
        $sql = "select id, nombre from usuario";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
 
        $redes = $smt->fetchAll();
        $em->clear();
   
       return new JsonResponse($redes);
    }

    public function usuarioidAction($id)
    {
        $sql = "select id, nombre from usuario where usuario.id_persona = :id";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
        $em->clear();
   
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
        $em->clear();
   
       return new JsonResponse($redes); 
    }

    //servicio permiso por id
    public function permisoidAction($id)
    {
        
        $permiso = array();
        

        $em = $this->getDoctrine()->getEntityManager();
 
         $em->beginTransaction();
            try
            {
                $sql = "select * from rol";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute();
                $roles = $smt->fetchAll();
                
                foreach ($roles as $key => $value) {
                    $permiso[$value['nombre']] = FALSE;
                }
                
                $sql = "select * from get_persona_rol(:idx)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$id));
                
                $roles = $smt->fetchAll();
                
                foreach ($roles as $key => $value) {
                    $permiso[$value['nombre']] = TRUE;
                }
                
                //lider
                /*$sql = "select * from lider where lider.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['lider']= TRUE;
                }
                
                //lider_red
                
                $sql = "select * from lider_red where lider_red.id = :id and activo=true";
                 
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['lider_red']= TRUE;
                }
                
                //pastor_asociado
                $sql = "select * from pastor_asociado where pastor_asociado.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['pastor_asociado']= TRUE;
                }
                
                //misionero
                $sql = "select * from misionero where misionero.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['misionero']= TRUE;
                }
                
                //pastor_ejecutivo
                $sql = "select * from pastor_ejecutivo where pastor_ejecutivo.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['pastor_ejecutivo']= TRUE;
                }
                
                //estudiante
                $sql = "select * from estudiante where estudiante.id = :id and activo=true";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['estudiante']= TRUE;
                }
                
                //consolidador
                $sql = "select * from consolidador where consolidador.id = :id";
                
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$id));
                $redes = $smt->fetchAll();
                $em->clear();
                
                if(count($redes)>0)
                {   
                    $permiso['consolidador']= TRUE;
                }
                */
                $em->commit();
                $em->clear();
                
               
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
   
               throw $e;
            }
       
        
       return new JsonResponse($permiso); 
    }
    
    public function miembroallAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
           
        $con = $em->getRepository('AEDataBundle:Persona');
        
        $personas = $con->findAll();
        $em->clear();
        
        $con->clear();
        
        $resultado = "";
        
        $con1 = $em->getRepository('AEDataBundle:Miembro');
        
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
        $con1->clear();
        
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
        
        $con1->clear();
        
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
        
        $em->clear();
        
        return new JsonResponse(array('aaData'=>$todo));        
    }
  
    public function pastorejecAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "select pastor_ejecutivo.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, pastor_ejecutivo.fecha_obtencion from pastor_ejecutivo inner join persona on (pastor_ejecutivo.id = persona.id ) where pastor_ejecutivo.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        $em->clear();
     
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
        
        $em->clear();
     
        return new JsonResponse(array('aaData'=>$todo));   
    }
    
    public function misionerosAction()
    {
         $em = $this->getDoctrine()->getEntityManager();

        $sql = "select misionero.id, persona.nombre, persona.apellidos, persona.edad,  persona.telefono, persona.celular, misionero.fecha_obtencion from misionero inner join persona on (misionero.id = persona.id) where misionero.activo=true";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        $em->clear();
        
        return new JsonResponse(array('aaData'=>$todo));
        
    }
    
   //lista de usuarios
   public function listausuarioAction()
   {
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " select  * from lista_usuarios";
                
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        $em->clear();
        
        return new JsonResponse(array('aaData'=>$todo));
   }
   
   //lista lideres de red
   public function lista_liderAction()
   {
        $em = $this->getDoctrine()->getEntityManager();
        
        try
        {
            $em->beginTransaction();

            $sql = " select  * from lista_lideres_red_sin";
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            $em->commit();
            $em->clear();
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
            
            
            $em->commit();
            
            $em->clear();

        }
        catch (Exception $e)
        {
            $this->getDoctrine()->getEntityManager()->rollback();
            $this->getDoctrine()->getEntityManager()->close();
            
            throw $e;
        }
        
        return new JsonResponse($todo);
   }

   public function consolidadorAction($id)
   {
         $em = $this->getDoctrine()->getEntityManager();


        $sql = "select persona.id, persona.nombre, persona.apellidos from consolidador left join persona on persona.id = consolidador.id where consolidador.activo = true and consolidador.id =:id";
          
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute(array(':id'=>$id));
        
        $todo = $smt->fetch();
        
        $em->clear();
        
        return new JsonResponse($todo); 
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
        $em->clear();
             
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
       $em->clear();
       
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

       $em->clear();
       
       return new JsonResponse($todo);
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
   
 
   public function enviar_lista_celulaAction($id)
   {
       
       $todo = array();
       
       $retorno = "";
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       try
       {
           $sql = "select *from asistencia_celula(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));
           $todo = $smt->fetchAll();
           $em->clear();           
           
           $n = count($todo);
           
           for($i=0; $i<$n; $i++)
           {
               $temp = "<tr> <td>".$todo[$i]['id']."</td> <td>".$todo[$i]['nombre']."</td> <td>". $todo[$i]['apellidos']."</td> <td class='table-checkbox'> <input id= 'as".strval($i)."' name='as".strval($i)." 'class='selected-checkbox' type='checkbox'> </td> </tr>";
               $retorno = $retorno.$temp;
           }
  
           $em->commit();
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
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
           $sql = "select *from ver_tema_celula(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));
           $todo = $smt->fetch();
           $em->commit();
           $em->clear();

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
       
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $est = array();
       
       try
       {
           $con = $em->getRepository('AEDataBundle:ClaseCell');
           $rest = $con->findOneBy(array('id'=>$id));
           
           
           $est['id']= $rest->getId();
           $est['ofrenda'] = $rest->getOfrenda();
           
           if($rest->getFechaDicto()!=NULL)
            $est['fecha'] = $rest->getFechaDicto()->format('d-m-Y');
           else
            $est['fecha'] ='';
           
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse($est);
   }

   public function lider_sinAction($red)
   {
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
           $sql = "select * from get_lideres_por_tipo(:red,:tip)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red,':tip'=> 0));
           
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
   
   public function encargadoidAction($id)
   {
       
          $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
           $sql = "select * from get_red_encargado(:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':id'=>$id));
           
           $todo = $smt->fetchAll();
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse($todo);
   }
   
   
   
   public function encargadoid_doceAction($red, $id)
   {
       
       $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
           $sql = "select * from get_red_encargado_doce(:red,:id)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red, ':id'=>$id));
           
           $todo = $smt->fetchAll();
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse($todo);
   }
   
   public function personaidAction($id)
   {
          $em = $this->getDoctrine()->getEntityManager();

       $em->beginTransaction();
       
       $todo = array();
       
       try
       {
           $sql = "select * from persona where id=:idx";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array( ':idx'=>$id));
           
           $todo = $smt->fetchAll();
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse($todo);
   }
   
    public function load_datosAction($nombre)
    {
        
        getcwd();
        
        chdir('csv');
        
        set_time_limit ( 600 );
        
        $path = getcwd()."/".$nombre;
        
        $handle = fopen($path, "r");
        
        $todo = array();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        
        $primera = fgetcsv($handle, 0, ",");
        
        $em->beginTransaction();
        
        $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
        $ubigeo = $prev_div->findOneBy(array('id'=>1807));
        
        
        $redes = $em->getRepository('AEDataBundle:Red');
        
        $lugares = $em->getRepository('AEDataBundle:Lugar');
        $lugar = $lugares->findOneBy(array('nombre'=>'Coliseo'));
        
        $cont =0;
        while (($data = fgetcsv($handle, ",")) !== FALSE) {
            $todo[] = $data;
        
            $cont++;
            //registro de ubicacion 
            
            $ubiq = new Ubicacion();
            $ubiq->setDireccion($data[5]);
            $ubiq->setReferencia($data[5]);
            $ubiq->setLatitud(-8.097944);
            $ubiq->setLongitud(-79.03704479999999);
            $ubiq->setIdUbigeo($ubigeo);
            
            $em->persist($ubiq);
            $em->flush();
            
            
            $persona = new Persona();
            
            
            if(strlen($data[0])>5)
            $persona->setDni($data[0]);
            else  $persona->setDni("12345678");

           
            $persona->setNombre($data[1]);
            $persona->setApellidos($data[2]);
            
            //sexo
            if($data[3]=='M')
                $persona->setSexo(1);
            else $persona->setSexo(2);
            
            $persona->setCelular($data[6]);
            $persona->setOcupacion($data[7]);
            
            $est=0;
            if(strcmp($data[8],'S')==0)
                    $est=0;
                    
            if(strcmp($data[8],'C')==0)
                    $est=1;
            if(strcmp($data[8],'S')==0)
                    $est=0;
            if(strcmp($data[8],'CN')==0)
                    $est=2;
            
            if(strcmp($data[8],'VD')==0)
                    $est=3;
            
            if(strcmp($data[8],'SP')==0)
                    $est=4; 
            
            if(strcmp($data[8],'MS')==0)
                    $est=5;
            
            $persona->setEstadoCivil($est);
            
            $nac = NULL;
            //fecha nacimiento
            if(substr_count(strval($data[9]),'/')==2)
            {
                $fecha_a =explode('/', $data[9],3);
                
                if(intval($fecha_a[0])<13)
                    $fecha = $fecha_a[2].'-'.$fecha_a[0].'-'.$fecha_a[1];
                else
                    $fecha = $fecha_a[2].'-'.$fecha_a[1].'-'.$fecha_a[0];

                                 $nac = new \DateTime($fecha);
            }
            else
            {
                $nac = new \DateTime('1980-01-01');
            }
            
            $persona->setFechaNacimiento($nac);
            
            
            //edad
            
            $datetime1 = new \DateTime();
            $interval = $datetime1->diff($nac);
            
            $persona->setEdad(intval($interval->format('%y')));
            
            $persona->setWebsite(NULL);
            $persona->setIdUbicacion($ubiq);
            
            $em->persist($persona);
            $em->flush();
            
            
            $nuevo_con = new NuevoConvertido();
            $miembro = new Miembro();
            //conversion
               $conv = NULL;
            //fecha nacimiento
            if(substr_count(strval($data[10]),'/')==2)
            {
               
                $fecha_a =explode('/', $data[10],3);
                
                if(intval($fecha_a[0])<13)
                    $fecha = $fecha_a[2].'-'.$fecha_a[0].'-'.$fecha_a[1];
                else
                    $fecha = $fecha_a[2].'-'.$fecha_a[1].'-'.$fecha_a[0];

                 
                $conv = new \DateTime($fecha);
            }
            else
            {
                $conv = new \DateTime('2000-01-01');
            }
            
            //registro nuevo convertido
          
            $nuevo_con->setConsolidado(FALSE);
            $nuevo_con->setDia("Lunes");
            $nuevo_con->setHora(new \DateTime());
            $nuevo_con->setFechaConversion($conv);
            $nuevo_con->setId($persona);
            $nuevo_con->setIdCelula(NULL);
            $nuevo_con->setIdLugar($lugar);
            
            $red = $redes->findOneBy(array('id'=>$data[3]));
            
            $nuevo_con->setIdRed($red);
            $nuevo_con->setPeticion("");
            
            $em->persist($nuevo_con);
            $em->flush();
            
            //miembro
            
            $miembro->setActivo(TRUE);
            $miembro->setAptoConsolidar(FALSE);
            $miembro->setFechaObtencion($conv);
            $miembro->setIdRed($red);
            $miembro->setId($persona);
            $miembro->setIdCelula(NULL);
            
            $em->persist($miembro);
            $em->flush();
            
          }
          
        $em->commit();
        
        fclose($handle);
        
        return new JsonResponse($cont);
    }
   
}
