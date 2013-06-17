<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class ContactoController extends Controller
{
    public function contactoAction()
    {
        
        $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_CONSOLIDADOR'))
        {
            $ganador = $securityContext->getToken()->getUser()->getIdPersona();
            $red = NULL;
            $consolidador = NULL;
            
            $em = $this->getDoctrine()->getEntityManager();
        
            if($ganador != NULL)
            {
                $sql = "select * from get_red_persona(:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':id'=>$ganador->getId()));
                $req = $smt->fetch();
                if(count($req)>0)
                {
                    $red = $req['red'];
                    $consolidador=$ganador->getId();
                }
            }
            
            if($securityContext->isGranted('ROLE_LIDER_RED'))
              $consolidador=NULL;
            
            if($securityContext->isGranted('ROLE_CONSOLIDAR'))
            {  
                $consolidador=NULL;
                $red = NULL;
            }
        return $this->render('AEConsolidarBundle:Default:contacto.html.twig',array('red'=>$red,'consolidador'=>$consolidador));
        
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }

    public function contactoherramientaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
        $em = $this->getDoctrine()->getEntityManager();         

        
        if($name!=NULL){
                 
            $consolida = $datos['consolida'];
            $tool = $datos['tools'];
            $dia_b = $datos['dia'];
            
            $dia_a =explode('/', $dia_b,3);
            $dia = $dia_a[2].'-'.$dia_a[1].'-'.$dia_a[0];
            
            $hora = $datos['hora'];
            

            $em->beginTransaction();
            try
            {
                $sql = "select update_consolida_herramienta(:tool,:consol,:tiempo)";
                $smt = $em->getConnection()->prepare($sql);
                
                $smt->execute(array(':tool'=>$tool,':consol'=>$consolida,':tiempo'=>$dia.' '.$hora));
                
                $em->commit();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
            }
        
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }
        $return=array("responseCode"=>200,  "greeting"=>'ook');

        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
}

