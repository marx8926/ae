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


class InformesController extends Controller
{
    public function informeNoconsolidadoAction()
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
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:informeNoconsolidado.html.twig',array('red'=>$red));
    }
    
    public function informeHerramientasAction()
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
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:informeHerramientas.html.twig',array('red'=>$red));
    }
    
    public function informeLecheEspiritualAction()
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
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:InformeLecheEspiritual.html.twig',array('red'=>$red));
    }
    
    public function informeDescartadosAction()
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
            
            $red = $req['red'];
        }
        return $this->render('AEConsolidarBundle:Default:InformeDescartados.html.twig', array('red'=>$red));
    }
}

