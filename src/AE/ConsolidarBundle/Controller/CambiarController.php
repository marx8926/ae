<?php

namespace AE\ConsolidarBundle\Controller;

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


class CambiarController extends Controller
{
  public function cambiar_consolidadorAction()
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
                    $red =NULL;
            }
        return $this->render('AEConsolidarBundle:Default:cambiar_consolidador.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

     }
     
     public function cambiar_consolidador_updateAction()
     {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $lista = $request->request->get('lista');
        
         $datos = array();

        parse_str($name,$datos);
        
     
        $em = $this->getDoctrine()->getEntityManager();
        
        // $this->getDoctrine()->getEntityManager()->beginTransaction();
        $return = null;
        
        $em->beginTransaction();
        try{
            
     
            $sql = "select update_consolida_consolidador(:id1,:id2,:idp)";
        
            $smt = $em->getConnection()->prepare($sql);
         
            $smt->execute(array(':id1'=>$lista[0],':id2'=>$datos['select_consolidador'],':idp'=>$lista[6]));
 
            $return=array("responseCode"=>200, "greeting"=>'Good');

            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            $return=array("responseCode"=>400, "greeting"=>'Bad');

        }
        
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
     }

}

