<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServiciosController extends Controller
{

    
   public function administrar_doze_redAction($red)
   {
       
       $est = array();
       
        $securityContext = $this->get('security.context');
        
        
      
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       try
       {
           $sql = "select * from info_doce_red(:red)";
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
       return new JsonResponse($est);
   }
   
}