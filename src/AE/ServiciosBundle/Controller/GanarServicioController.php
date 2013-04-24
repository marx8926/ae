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


class GanarServicioController extends Controller
{
     //lista de redes activas
    public function redAction()
    {
        $redes = array();
        $em = $this->getDoctrine()->getEntityManager();

        
        try {
            $em->beginTransaction();
            $sql = 'select * from lista_redes';

            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
    }
    
    public function regionAction()
    {
      
    
        $em = $this->getDoctrine()->getEntityManager();
        $redes = array();
       
        try{
            
            $em->beginTransaction();
            
            $sql = 'select * from lista_regiones';
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            
            $em->commit();
        }  catch (Exception $e)
        {
            $em->rollback();
            $em->close();
            throw  $e;
        }
    
        return new JsonResponse($redes); 
    }
    
     public function lugarAction()
     {
        $sql = 'select * from lugar';
        
        $redes = array();
 
        $em = $this->getDoctrine()->getEntityManager();
        try {
            
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
 
            $redes = $smt->fetchAll();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
      }
      
    //celula id
    public function celulaAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        
        try {
            
            $em->beginTransaction();
            
            $sql = 'select * from celulas_por_red(:red,:tip) ';
       
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':red'=>$id,':tip'=>0));
 
            $redes = $smt->fetchAll();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);
    }
    
    public function provinciaAction($id)
    {      
  
        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
        try {
            $em->beginTransaction();
            $sql = 'SELECT cod as codprovincia, prov as provincia from ver_provincia(:id)';
      
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $redes = $smt->fetchAll();
            $em->commit();
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       return new JsonResponse($redes);         
    }
    public function distritoAction($dep, $prov)
    {
        
         $em = $this->getDoctrine()->getEntityManager();
         $redes = array();
         
         try {
             $em->beginTransaction();
             
             $sql = 'select ids as id, 
                    coddep as coddepartamento,
                    codprov as codprovincia,
                    coddis as coddistrito,
                    dep as departamento,
                    prov as provincia,
                    dist as distrito,
                    lati as provincia,
                    longi as "long" from ver_distrito(:dep,:prov)';
             $smt = $em->getConnection()->prepare($sql);
             $smt->execute(array(':dep'=>$dep,':prov'=>$prov));
 
             $redes = $smt->fetchAll();
             
             $em->commit();
        
         } catch (Exception $exc) {
             $em->rollback();
             $em->close();
             throw $exc;
         }

       return new JsonResponse($redes);
    }

    public function listaconvertidosAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $redes = array();
        try {
            $em->beginTransaction();
            
            $sql = "select *from nuevos_convertidos";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute();
            $redes = $smt->fetchAll();
            
            $em->commit();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }
        
        return new JsonResponse(array('aaData'=>$redes));
    }
    
    public function personaAction($id)
    {        
        $sql_persona = "select * from get_persona(:id)";

        $em = $this->getDoctrine()->getEntityManager();
       
        $smt = $em->getConnection()->prepare($sql_persona);
        $smt->execute(array(':id'=>$id));
 
        $redes = $smt->fetch();
   
       return new JsonResponse($redes);
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
    
    public function nuevoconvertidoAction($id)
    {
        $sql = "select * from get_convertido(:id)";

        $em = $this->getDoctrine()->getEntityManager();
        
        $redes = array();
       
        try{
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $redes = $smt->fetch();
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            throw $e;
        }
       return new JsonResponse($redes);
    }
}

