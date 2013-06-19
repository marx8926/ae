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
                            if(strcmp($pass, $npass)==0  )
			    {
                                if(strcmp($user->getNombre(),$usuario)==0)
                                {
                                    $user->setNombre($usuario);	                                   
                                }
                                else
                                {
                                    $user = $em->getRepository('AEDataBundle:Usuario')->findOneBy(array('nombre'=>$usuario));                                    
                                }
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
    
    public function check_userAction($user)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $todo = array();
        
        $securityContext = $this->get('security.context');
        $usuario = $securityContext->getToken()->getUser();
        
        
        $em->beginTransaction();
        
        try {
            
            
            if(strcmp($usuario->getNombre(),$user)!=0)
            {        
                $sql = "select id from usuario where nombre=:usr";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':usr'=>$user));
                $todo = $smt->fetchAll();
            }
            
        } catch (Exception $exc) {
            throw $exc;
        }
        $em->commit();
        $em->clear();
        
        return new JsonResponse($todo);
     }
    
     
     
    public function estadousuario_upAction()
    {
        
              
                $request = $this->get('request');
		$form=$request->request->get('formName');
		
		$datos = array();
		
		parse_str($form,$datos);
		$usuario= null;
		$estado = null;
		
		if($form!=NULL){
				
			$usuario = $datos["uss"];
			$estado = $datos["estado"];
						
			$em = $this->getDoctrine()->getEntityManager();	
			$em->beginTransaction();
			try
			{
                            $sql = "UPDATE usuario SET  enabled=:est WHERE nombre=:nom";
                            $smt = $em->getConnection()->prepare($sql);
                            
                            if(strcmp("Activo",$estado)==0)
                            {
                                $smt->execute(array(':est'=>'FALSE',':nom'=>$usuario));
                            }
                            else $smt->execute(array(':est'=>'TRUE',':nom'=>$usuario));

                        }catch(Exception $e)
			{
				$em->rollback();
				$em->close();
				$return=array("responseCode"=>400, "greeting"=>"Bad");
					
				throw $e;
			}
			$em->commit();
       			$return = array("responseCode"=>200, "greeting"=>"oK");

		}
		else{
			$return = array("responseCode"=>400, "greeting"=>"Bad");
		}
			
		$return=json_encode($return);
		
		return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
		
    }
    
}
