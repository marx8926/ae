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
       $this->getDoctrine()->getEntityManager()->beginTransaction();
       
       $est = array();
       
       $em = $this->getDoctrine()->getEntityManager();
       
       try
       {
           $em->beginTransaction();
          
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

   
}