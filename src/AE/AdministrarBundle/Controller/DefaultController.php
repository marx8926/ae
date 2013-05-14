<?php

namespace AE\AdministrarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Iglesia;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\Consolidador;
use AE\DataBundle\Entity\LecheEspiritual;
use AE\DataBundle\Entity\TemaLeche;
use AE\DataBundle\Entity\Archivo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEAdministrarBundle:Default:index.html.twig', array('name' => $name));
    }

 
    public function lista_pastor_asociadoAction()
    {
        return $this->render('AEAdministrarBundle:listas:pastores_asoc.html.twig');
    }
  
    public function lista_pastor_ejecutivoAction()
    {
         return $this->render('AEAdministrarBundle:listas:pastores_eje.html.twig');
    }
  
    public function lista_lider_redAction()
    {
         return $this->render('AEAdministrarBundle:listas:liderred.html.twig');
    }
  
    public function lista_misioneroAction()
    {
         return $this->render('AEAdministrarBundle:listas:misionero.html.twig');
    }
  
    public function lista_miembrosAction()
    {
                return $this->render('AEAdministrarBundle:listas:miembro_red.html.twig');

    }
    
    public function lista_miembros_allAction()
    {
       return $this->render('AEAdministrarBundle:listas:miembros.html.twig');   
    }

  
    public function lista_usuariosAction()
    {
        return $this->render('AEAdministrarBundle:listas:usuario.html.twig');
    }
    public function claseAction()
    {
        return $this->render('AEAdministrarBundle:leche:clase.html.twig');
    }
    
    public function clase_regAction()
    {
        
         
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        $nombre = NULL;
        $descripcion = NULL;
        $fecha = NULL;
        $clases = NULL;

       if($name!=NULL){
                
           if(strpos($name, 'nombre')!==false)
           {
               $nombre = $datos['nombre'];           
           }
           
           if(strpos($name, 'inputDescripcion')!=false)
           {
               $descripcion = $datos['inputDescripcion'];
           }
           
           if(strpos($name,'inputFecha')!=false)
           {
               $fecha = $datos['inputFecha'];
           }
           
           if(strpos($name, 'inputClases')!=false)
           {
               $clases = $datos['inputClases'];
           }
           
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                $leche = new LecheEspiritual();
           
                if($fecha ==NULL)
                    $leche->setFechaCreacion(new \DateTime());
                else
                    $leche->setFechaCreacion(new \DateTime($fecha));
           
                $leche->setNombre($nombre);
                $leche->setResumen($descripcion);
           
                $em->persist($leche);
                $em->flush();
           
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>300, "id"=>$leche->getId() ); 

                
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
    public function temas_lecheAction()
    {
           
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');
        $num = $request->request->get('num');
        
        $datos = array();

        parse_str($name,$datos);
       

       if($name!=NULL){
                
            $cont = intval($num);
            
            $lista = array();
     
            $i=0;
            for($i=0;$i< $cont;$i++)
            {
                $lista[] = $datos['tema'.strval($i)] ;           
  
            }
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
           
                for($i=0;$i<$cont; $i++)
                {
                    $tema = new TemaLeche();
                    
                    $tema->setIdLecheEspiritual($leche);
                    $tema->setTitulo($lista[$i]);
                    $em->persist($tema);
                    $em->flush();
                }   

                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>300, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
        
    }
    
    public function file_lecheAction(Request $request)
    {
        
         $name = $request->query->get('name');
         $id = $request->query->get('id');
         
         $uploadName = date("Y-m-d-H-i-s").$name;
         $url = "uploads/".$uploadName;

        $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
           
                 $file = new Archivo();
         
                $file->setFecha(new \DateTime());
                $file->setDireccion($url);
                $file->setIdLecheEspiritual($leche);
                
                $em->persist($file);
                $em->flush();
                
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
            
             $return=json_encode($return);//jscon encode the array
        
       // return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
                return new Response();
    }

    public function lista_redesAction()
    {
         return $this->render('AEAdministrarBundle:Red:lista_redes.html.twig');
    }
    
    public function lista_redes_modificarAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $code = $request->request->get('code');
        $ubicacion = $request->request->get('ubicacion');
        
        $datos = array();

        parse_str($name,$datos);
        
        $retorno = 'ok';
       
        if($name!=NULL){
            
            $tipo_red = $datos['tipo_red'];
            $iglesia  = $datos['iglesia_lista'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            $tip_red = $datos['tip_red'];
            $ids = $datos['ids'];
            
            $em = $this->getDoctrine()->getEntityManager();
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            
            //añadir las excepciones en los prepare
            
            try{
                        
                //cambio en tabla red
                if(intval($tip_red)==0)
                {
               
                    //cambio de lider
                    if(intval($ids)!= -1)
                    {                  
                                   
                        $sql = "select update_red(:idx,:tip,:igle,:lider,:pastor)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>$code,':tip'=>$tipo_red, ':igle'=>$iglesia, ':lider'=>$ids,':pastor'=>NULL));
                     
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
              
                    }

            }
            else
            {
                   //cambio pastor
                 if(intval($ids)!= -1)
                    {                  
                                   
                        $sql = "select update_red(:idx,:tip,:igle,:lider,:pastor)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>$code,':tip'=>$tipo_red, ':igle'=>$iglesia, ':lider'=>NULL,':pastor'=>$ids));
                     
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
              
                    }
            }
            
                //cambio de ubicacion
            
                $sql = "select  update_ubicacion(:idx,:dir, :refe, :lat,:lng, :ubigeo)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$ubicacion, ':dir'=>$direccion, ':refe'=>$referencia,':lat'=>$latitud,
                    ':lng'=>$longitud,':ubigeo'=>$distrito));
                $em->flush();
            
                $this->getDoctrine()->getEntityManager()->commit();
                
                $return=array("responseCode"=>200, "greeting"=>$retorno);
            }
            catch(Exception $e)
            {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                 $return=array("responseCode"=>400, "greeting"=>'bad');
                
                throw $e;
            }

        }
        else 
        {
          $return = array("responseCode"=>400, "greeting"=>"Bad");
        }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
    public function lista_redes_modificar_vistaAction($id)
    {
       return $this->render('AEAdministrarBundle:Red:modred.html.twig',array('id'=>$id));

    }


    public function lista_redes_eliminarAction()
    {
        $request = $this->get('request');
        $code=$request->request->get('formName');
       

       
        if($code!=NULL){
                
            
            $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                $sql = "select delete_red(:idx)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$code));
               
                $em->flush();
                
                $this->getDoctrine()->getEntityManager()->commit();
                $return=array("responseCode"=>200, "greeting"=>$code ); 
  
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
}
