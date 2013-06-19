<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;


class IglesiaController extends Controller
{
   
      //iglesia
    public function igleAction()
    {
        $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_ADMIN'))
            return $this->render('AEAdministrarBundle:Iglesia:regIglesia.html.twig');
        
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
    }
    
    
            
    //registrar iglesia
    public function addigleAction()
    {
                
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        
        $em = $this->getDoctrine()->getEntityManager();         
        
       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $telefono = $datos['inputTelef'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
   
            $iglesias = $em->getRepository('AEDataBundle:Iglesia');
            $iglesias = $iglesias->findAll();
            
            if(count($iglesias)==0)
            {
            $em->beginTransaction();
            try
            {
                //ubigeo
               $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));
                $em->clear();
             
                
                  //ubicacion
                $ubicacion = new Ubicacion();
                $ubicacion->setDireccion($direccion);
                $ubicacion->setReferencia($referencia);
                $ubicacion->setLatitud($latitud);
                $ubicacion->setLongitud($longitud);
                $ubicacion->setIdUbigeo($ubigeo);
                
                
                $em->persist($ubicacion);
                $em->flush();

                $iglesia = new Iglesia();
                $iglesia->setIdUbicacion($ubicacion);
                $iglesia->setNombre($nombre);
                $iglesia->setTelefono($telefono);
                
                $em->persist($iglesia);
                $em->flush();
                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200,  "greeting"=>'OK');
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>500, "greeting"=>"Bad");     
        }
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }

        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    }

    
    public function modiglesiaAction()
    {
        
        $securityContext = $this->get('security.context');
        
        if($securityContext->isGranted('ROLE_ADMIN'))
        {
            $em = $this->getDoctrine()->getEntityManager();         
         
            $iglesias = $em->getRepository('AEDataBundle:Iglesia');
            $igle = $iglesias->findAll();
            $em->clear();
          
            $em->close();
                  
            return $this->render('AEAdministrarBundle:Iglesia:modificar.html.twig',array('id'=> $igle[0]->getId()));
   
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    
    public function updateiglesiaAction()
    {
                
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('other');

        $datos = array();

        parse_str($name,$datos);
        $em = $this->getDoctrine()->getEntityManager();         

    
       if($name!=NULL){
                   
            $nombre = $datos['inputNom'];
            $telefono = $datos['inputTelef'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];

            //   $em->persist($user);
            //$em->flush();
            $iglesias = $em->getRepository('AEDataBundle:Iglesia');
            $iglesia = $iglesias->findOneBy(array('id'=>$id));
           
            $em->beginTransaction();
            try
            {
                //ubicacion
               $sql = 'UPDATE ubicacion SET id_ubigeo=:idgeo, direccion=:dir, referencia=:ref, latitud= :lat, longitud= :lng where id=:iddep';

                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idgeo'=>$distrito,':dir'=> $direccion, ':ref'=>$referencia,
                    ':lat'=>$latitud,':lng'=>$longitud, ':iddep'=>$id));
                //iglesia 
                
                $iglesia->setNombre($nombre);
                $iglesia->setTelefono($telefono);

                $em->persist($iglesia);
                $em->flush();
   
                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200,  "greeting"=>$datos);
                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }

       
       }else
       {
           $return=array("responseCode"=>400, "greeting"=>"Bad");     
       }

        
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    }
    
}
