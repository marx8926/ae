<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AE\DataBundle\Entity\Usuario;


class UserController extends Controller
{
    public function cambiarusuarioAction()            
    {
        
          $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_USER'))
        {
            $usuario = $securityContext->getToken()->getUser();
           
            $user = NULL;
            
            if($usuario!=NULL)
                $user = $usuario->getNombre();

            return $this->render('AEAdministrarBundle:Usuario:cambiarclave.html.twig', array('usuario'=>$user));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    public function cambiarusuario_upAction()
    {
        
                $securityContext = $this->get('security.context');
                $user = $securityContext->getToken()->getUser();


                $request = $this->get('request');
		$form=$request->request->get('formName');
		
		$datos = array();
		
		parse_str($form,$datos);
		$usuario= null;
		$pass = null;
		$npass = null;
		
		if($form!=NULL){
				
			$usuario = $datos["user"];
			$pass = $datos["pass"];
			$npass = $datos["npass"];
						
			$em = $this->getDoctrine()->getEntityManager();	
			$em->beginTransaction();
			try
			{
                            if(strcmp($pass, $npass)==0)
			    {	$user->setNombre($usuario);	
                                $user->setPassword($pass);
				$em->persist($user);
				$em->flush();
                                
                          	$return=array("responseCode"=>200, "greetings"=>'ok' );
                            }
                            else $return=array("responseCode"=>400, "greeting"=>"Bad");


		
			}catch(Exception $e)
			{
				$em->rollback();
				$em->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$em->commit();
		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
    }
}
