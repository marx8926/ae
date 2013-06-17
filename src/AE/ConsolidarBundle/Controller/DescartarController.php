<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Descartado;


class DescartarController extends Controller
{
   public function descartarAction()
     {
        $securityContext = $this->get('security.context');
      
        if($securityContext->isGranted('ROLE_LIDER_RED'))
        {
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                    $red = $req['red'];
            }
            
            if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                $red = NULL;
            
            return $this->render('AEConsolidarBundle:Default:descartar.html.twig',array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

     }
     
     public function descartar_updateAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
              
        $em = $this->getDoctrine()->getEntityManager();
        
        $em->beginTransaction();
        $id= NULL;
        $descripcion = NULL;
        $idP = NULL; //id persona
        
        if($name!=NULL)
        {
        try
        {
             $datos = array();

            parse_str($name,$datos);
            
            $id = $datos['nomb'];
            $descripcion = $datos['motivo'];
            $idP = $datos['pers'];
        
            $descartado = new Descartado();
            $descartado->setFechaDescarte(new \DateTime());
            $descartado->setCometario($descripcion);
           
            $persona = $em->getRepository('AEDataBundle:Persona')->findOneBy(array('id'=>$idP));
            
            $descartado->setId($persona);
            
            $em->persist($descartado);
            $em->flush();
           
            $sql = "select update_consolida(:id)";
            
            $smt = $em->getConnection()->prepare($sql);
                         
            if(!$smt->execute(array(':id'=>$id)))
            {
                $return=array("responseCode"=>400, "greeting"=>"Bad");

                $return=json_encode($return);//jscon encode the array
     
                return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
            }
            
            $em->commit();
            $em->clear();
            
        }catch(Exception $e)
        {
             $em->rollback();
             $em->close();
                
             $return=array("responseCode"=>400, "greeting"=>"Bad");

             throw $e;
        }
        $return=array("responseCode"=>200, "greeting"=>$descripcion);
        }
        else  $return=array("responseCode"=>400, "greeting"=>"Bad");
            
  
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     }
     
     public function lista_descartarAction()
     {
          $securityContext = $this->get('security.context');
              
        if($securityContext->isGranted('ROLE_LIDER_RED'))
        {
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
        
            $red = NULL;
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                $red = $req['red'];
                
                if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                    $red = NULL;
            }
         return $this->render('AEConsolidarBundle:Default:lista_descartados.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
     }
}


