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
               $resultado = $this->redirect($this->generateUrl('ingreso'));
               $resultado->setMaxAge(60);
               $resultado->setPublic();
               
               return $resultado;

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
 
        $resultado = $this->render('AEloginBundle:Default:index.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
        
        $resultado->setMaxAge(60);
        
        $resultado->setPublic();
        
        return $resultado;
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
        
        $securityContext = $this->get('security.context');

        $usuario = $securityContext->getToken()->getUser();
        
        if($usuario->getEnabled())
        {
        $response = $this->render('AEloginBundle:Default:main.html.twig');
        
        $response->setSharedMaxAge(60);
        $response->setPublic();
        
        return $response;
        }
        else {
               return $this->redirect($this->generateUrl('salir'));
        }
    }
    
    public function sobreAction()
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
        
        return $this->render('AEloginBundle:Default:sobre.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    public function contactoAction()
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
        
        return $this->render('AEloginBundle:Default:contacto.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));        
    }
    
    public function contactoUpAction()
    {
        
        $request = $this->getRequest();
        $message = \Swift_Message::newInstance()
            ->setSubject($request->get('subject'))
            ->setFrom('artmar89@gmail.com')
            ->setTo('contacto@clmdevelopers.com')
            ->setBody($this->renderView('AEloginBundle:Default:holamundo.txt.twig', array('nombres' => $request->get('nombres'),
                'apellidos'=>$request->get('apellidos'),'email'=>$request->get('email'),
                'subject'=>$request->get('subject'),'message'=>$request->get('message')
                )));
        $this->get('mailer')->send($message);

        $this->get('session')->setFlash('notice', 'Tu contacto fue enviado exitosamente. Dios te bendiga!');
            
        
        return $this->redirect($this->generateUrl('contacto'));

    }
}
