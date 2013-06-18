<?php

namespace AE\EnviarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Celula;
use AE\DataBundle\Entity\TemaCelula;
use AE\DataBundle\Entity\Archivo;
use AE\DataBundle\Entity\Horario;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class LideresController extends Controller
{
    public function lista_lideresAction()
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
                if($securityContext->isGranted('ROLE_ENVIAR'))
                    $red = NULL;
            }
            
        return $this->render('AEEnviarBundle:Default:lista_lideres.html.twig',array('red'=>$red)); 
       }
       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }   
}
