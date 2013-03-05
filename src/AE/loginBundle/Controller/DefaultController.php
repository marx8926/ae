<?php

namespace AE\loginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use AE\DataBundle\Entity\Usuario;

class DefaultController extends Controller
{
     public function indexAction()
    {
       
    }
    
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        $securityContext = $this->get('security.context');

 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
 
        return $this->render('AEloginBundle:Default:index.html.twig', array(
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
        return new Response($resul);

    }
    
    public function logAction()
    {
       
         return new Response('esdr');
    }
    
    public function mainAction()
    {
        
        
        return $this->render('AEloginBundle:Default:main.html.twig');
    }
}
