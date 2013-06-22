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

class AsistenciaController extends Controller
{
        
    public function asistencia_celulaAction()
    {
         $request = $this->get('request');
        
        $celula=$request->request->get('celulaid');
        $red = $request->request->get('data');
        $tipo = $request->request->get('tip_cell');
        $titulo = $request->request->get('titulocell');
        $dia = $request->request->get('dia_cell');
        $horario = $request->request->get('horario_cell');
        $dicto = $request->request->get('dictado_cell');
        $id = $request->request->get('asiste');
        $ofrenda = 0;
        $ofrendaT = NULL;
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->beginTransaction();

        try {
            $sql = "select ofrenda from clase_cell where id=:idx";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$id));
            $ofrendaT = $smt->fetch();
            $em->commit();
            
        } catch (Exception $exc) {
            throw $exc;
        }

        return $this->render('AEEnviarBundle:Default:asistencia_celula.html.twig',array('id'=>$id, 'celula'=>$celula,
            'red'=>$red,'tipo'=>intval($tipo)==0?'Evangelistica':'Mentoreo', 'titulo'=>$titulo,
            'dia'=>'horario por ponrer', 'dicto'=>$dicto, 'ofrenda'=>($ofrendaT==NULL)?0:$ofrendaT['ofrenda']));
    }
        
    public function asistencia_celula_updateAction($tipo)
    {
        $request = $this->get('request');
        
        $form=$request->request->get('formName');
  
        $datos = array();

        parse_str($form,$datos);   
      
        $clase = $datos['claseid'];
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $ofrenda = $datos['ofrenda']; // ofrenda
            
        $n = $datos['numfilas'];
        
        $invitados = $datos['invitados'];
        
        try{
            $em->beginTransaction(); 
            
            if(intval($tipo)==0)
            {
                for($i=0; $i<$n ;$i++)
                {
                    $var = "check".strval($i);
                    if(strpos($form, $var)!==false)
                    {
                        $id = $datos['miembro'.strval($i)];
                    
                        $sql = "select update_clase_cell_miembro(:miembro,:clase,:band)";
                        
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':miembro'=>$id,':clase'=>$clase,':band'=>TRUE));      
                    
                    }
                }
            }
            else
            {
                 for($i=0; $i<$n ;$i++)
                {
                    $var = "check".strval($i);
                    if(strpos($form, $var)!==false)
                    {
                        $id = $datos['miembro'.strval($i)];
                    
                        $sql = "select update_clase_cell_disc(:miembro,:clase,:band)";
                        
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':miembro'=>$id,':clase'=>$clase,':band'=>TRUE));      
                    
                    }
                }
            }
            
            $sql= "select update_clase_cell(:id,:monto,:invitados)";
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$clase,':monto'=>$ofrenda,':invitados'=>$invitados));
              
            $em->commit();
            
            $ret=array("responseCode"=>200, "greeting"=>'ok'); 
         }             
            catch(Exception  $e)
            {
                $em->rollback();
                $em->close();
                
                $ret=array("responseCode"=>400, "greeting"=>"Bad");
                
                throw $e;
            } 
       
        $return=json_encode($ret);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
        
    }
    
    public function asistenciaAction()
    {
        
         $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_LIDERSIN'))
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
            {
                $red = NULL;
            }

        }


        return $this->render('AEEnviarBundle:Default:asistencia.html.twig', array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
        
    }

}
