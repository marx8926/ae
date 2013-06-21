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

class AdministrarServicioController extends Controller
{
    public function herramientasAction()
    {
         $em = $this->getDoctrine()->getEntityManager();
        
        $todo = array();
        try
        {
            $em->beginTransaction();
            
            $sql = " select * from herramienta" ;
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
        
            $todo = $smt->fetchAll();
            
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
    
    public function getTablaEventosAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$sql = "SELECT * FROM lista_eventos";
    
    	$smt = $em->getConnection()->prepare($sql);
    	$smt->execute();
    
    	$todo = $smt->fetchAll();
        $em->clear();
    
    	return new JsonResponse(array('aaData'=>$todo));
    }
    
    public function getTablaPersonasNoEventoAction($idEvento)
    {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$sql = "select 
persona.id, Concat(persona.nombre,' ',persona.apellidos) as nombre, persona.edad, 
persona.telefono, persona.celular,persona.email, (case when persona.sexo=1 then 'Masculino' else 'Femenino' end) as sexo
from persona
where NOT EXISTS (SELECT er.id_persona FROM evento_realizado as er where er.id_persona = persona.id and er.id_evento=".$idEvento.")";
    	
    	$smt = $em->getConnection()->prepare($sql);
    	$smt->execute();
    	
    	$pre_todo = $smt->fetchAll();
        $todo=array();
        
        /*foreach($pre_todo as $per)
        {
        	if($per['sexo']==1)
        		$sexo="Masculino";
        	else
        		$sexo="Femenino";
        	
			$todo[] = array('id'=>$per['id'],'nombre'=>$per['nombre'],
						'edad'=>$per['edad'],'telefono'=>$per['telefono'],'celular'=>$per['celular'],
        				'e-mail'=>$per['email'], 'sexo'=>$sexo);        
        	}
    */
        
    	return new JsonResponse(array('aaData'=>$pre_todo));
    
    }
    
    public function getTablaAsistenciaEventoAction($idEvento)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$evento_realizado = $em->getRepository('AEDataBundle:EventoRealizado')
    	->findBy(   array('idEvento' => $idEvento));
    	
    	$todo=array();
    	
    	foreach($evento_realizado as $er)
    	{
    		$per = $er->getIdPersona();
    		
    		if($per->getSexo()==1)
    			$sexo="Masculino";
    		else
    			$sexo="Femenino";
    		 
    		$todo[] = array('id'=>$per->getId(),'nombre'=>$per->getNombre()." ".$per->getApellidos(),
    				'edad'=>$per->getEdad(),'telefono'=>$per->getTelefono(),'celular'=>$per->getCelular(),
    				'e-mail'=>$per->getEmail(), 'sexo'=>$sexo);
    	}
    
    	return new JsonResponse(array('aaData'=>$todo));
    
    }
    
       
   public function lista_redes_ubicacionAction()
   {
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();

       try
       {
          
           $sql = "select * from lista_red_ubicacion";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           
           $est = $smt->fetchAll();
           
           $em->flush();
           
           $em->commit();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array("aaData"=>$est));
   }

   public function doce_redAction($red)
   {
       $est= array();
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       try {
           $sql = "select * from info_doce_red(:red)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red));
           $est = $smt->fetchAll();
           $em->commit();
           $em->clear();
       } catch (Exception $exc) {
           throw $exc;
       }

       return new JsonResponse($est);
   }
   
   public function ciento_redAction($red, $lider)
   {
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       try
       {
           $sql = "select * from info_ciento_red(:red,:padre)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red,':padre'=>$lider));
           
           $est = $smt->fetchAll();
                      
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
           throw $e;
       }
       
       return new JsonResponse($est);
   }
  
   public function mil_redAction($red, $doce, $lider)
   {
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       try
       {
           $sql = "select * from info_mil_red(:red,:doce,:ciento)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red,':doce'=>$doce,':ciento'=>$lider));
           
           $est = $smt->fetchAll();
                      
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
           throw $e;
       }
       
       return new JsonResponse($est);
   }
  
   
   public function pastores_ejecutivosAction()
   {
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       $result = "<option value='-1'>Sin pastor </option>";
       
       try
       {
           $sql = "select * from pastores_ejecutivos";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           
           $est = $smt->fetchAll();
          /* 
           foreach ($est as $key => $value) {
               $result = $result."<option value='".$value['id']."' >".$value['nombres']."</option>";
           }
            */          
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
   
   public function servicio_asistencia_redAction($red)
   {
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       
       try
       {
           $sql = "select * from asistencia_culto where id_red= :net order by culto desc";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':net'=>$red));
           
           $est = $smt->fetchAll();
          /* 
           foreach ($est as $key => $value) {
               $result = $result."<option value='".$value['id']."' >".$value['nombres']."</option>";
           }
            */          
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$est));
   }
   
   public function miembrosintallaAction($red)
   {
          $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       
       try
       {
           $sql ="select * from get_miembros_sinropa_red(:net)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':net'=>$red));
           
           $est = $smt->fetchAll();
          /* 
           foreach ($est as $key => $value) {
               $result = $result."<option value='".$value['id']."' >".$value['nombres']."</option>";
           }
            */          
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$est));
   }
   
   public function informe_ropa_liderredAction()
   {
           $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       
       try
       {
           $sql ="select * from datos_lideres_red";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute();
           
           $est = $smt->fetchAll();
                  
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$est));
   }

   
    
   public function informe_ropaAction($red, $tipo)
   {
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       
       try
       {
           $sql ="select * from get_ropa_por_tipo_lider(:red,:tipo)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red,':tipo'=>$tipo));
           
           $est = $smt->fetchAll();
                  
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$est));
   }

   
   public function informe_ropa_miembrosAction($red)
   {
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       
       try
       {
           $sql ="select * from get_ropa_por_red(:red)";
           $smt = $em->getConnection()->prepare($sql);
           $smt->execute(array(':red'=>$red));
           
           $est = $smt->fetchAll();
                  
           $em->commit();
           $em->clear();
       }
       catch (Exception $e)
       {
           $em->rollback();
           $em->close();
       }
       
       return new JsonResponse(array('aaData'=>$est));
   }

}