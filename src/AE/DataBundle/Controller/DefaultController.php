<?php

namespace AE\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use AE\DataBundle\Entity\Usuario;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEDataBundle:Default:index.html.twig', array('name' => $name));
    }
   /* 
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
 
        return $this->render('AEDataBundle:Default:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    public function usuarioAction($user, $pass)
    {
        
        $usuario = new Usuario();

        $usuario->setNombre($user);
        
        $usuario->setPassword($pass);
        
        
        $em = $this->getDoctrine()->getEntityManager(); 
            
            
         
                
                
        $em->persist($usuario);
        $resul = $em->flush();
        return new \Symfony\Component\HttpFoundation\Response($resul);

    }
    
    public function logAction()
    {
         $user = $this->get('security.context')->getToken()->getUser()->getUsername();
         return new Response($user);
    }
    * */
    
}
