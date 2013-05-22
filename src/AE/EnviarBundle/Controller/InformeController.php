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
}
