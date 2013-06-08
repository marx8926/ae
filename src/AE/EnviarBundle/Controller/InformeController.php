<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;

class InformeController extends Controller
{
    public function celulogramaAction()
    {
        return $this->render('AEEnviarBundle:Default:celulograma.html.twig');
    }
    
    public function celulogramaUpAction()
    {
         $request = $this->get('request');
        
        $form=$request->request->get('formName');
  
        $datos = array();

        parse_str($form,$datos);   
        
        //$clase = $datos['claseid'];
        
        $em = $this->getDoctrine()->getEntityManager();
        
        //$ofrenda = $datos['ofrenda']; // ofrenda
            
        //$n = $datos['numfilas'];
        
        $ret=array("responseCode"=>200, "greeting"=>$datos);                
        
        $return=json_encode($ret);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type               
    }
    
    public function informe_semanalvistaAction()
    {
        $securityContext = $this->get('security.context');
        
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
            
        return $this->render('AEEnviarBundle:Default:informecelulas.html.twig',array('red'=>$red));
    
    }
}
