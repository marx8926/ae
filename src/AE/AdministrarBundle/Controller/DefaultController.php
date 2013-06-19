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
use AE\DataBundle\Entity\Usuario;

use Symfony\Component\HttpFoundation\Request;


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
                
                if($securityContext->isGranted('ROLE_GANAR') || $securityContext->isGranted('ROLE_ENVIAR') ||
                        $securityContext->isGranted('ROLE_CONSOLIDAR') || $securityContext->isGranted('ROLE_DISCIPULAR') )
                    $red =null;
            }
            
            return $this->render('AEAdministrarBundle:listas:miembro_red.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    public function lista_miembros_allAction()
    {
       $securityContext = $this->get('security.context');

       if ($securityContext->isGranted('ROLE_ADMIN'))
         return $this->render('AEAdministrarBundle:listas:miembros.html.twig');   

       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }

  
    public function lista_usuariosAction()
    {
        $securityContext = $this->get('security.context');

       if ($securityContext->isGranted('ROLE_ADMIN'))
        return $this->render('AEAdministrarBundle:listas:usuario.html.twig');
       else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
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
        $em = $this->getDoctrine()->getEntityManager();         


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
           		$fecha_inicio = explode('/', $datos["inputFecha"],3);
               $fecha = $fecha_inicio[2]."-".$fecha_inicio[1]."-".$fecha_inicio[0];
           }
           
           if(strpos($name, 'inputClases')!=false)
           {
               $clases = $datos['inputClases'];
           }
           

            $em->beginTransaction();
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
             
           
                $em->commit();
                
                   $em->clear();
                $return=array("responseCode"=>300, "id"=>$leche->getId() ); 

                
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
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
       
       $em = $this->getDoctrine()->getEntityManager();         

       if($name!=NULL){
                
            $cont = intval($num);
            
            $lista = array();
     
            $i=0;
            for($i=0;$i< $cont;$i++)
            {
                $lista[] = $datos['tema'.strval($i)] ;           
  
            }

            $em->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
                //$em->clear();
           
                for($i=0;$i<$cont; $i++)
                {
                    $tema = new TemaLeche();
                    
                    $tema->setIdLecheEspiritual($leche);
                    $tema->setTitulo($lista[$i]);
                    $em->persist($tema);
                    $em->flush();
                }   

                $em->commit();
                $em->clear();
                $return=array("responseCode"=>300, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
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

            $em->beginTransaction();
            try
            {
                //leche espiritual
                $como = $em->getRepository('AEDataBundle:LecheEspiritual');
                $leche = $como->findOneBy(array('id'=>$id));
                $em->clear();
           
                 $file = new Archivo();
         
                $file->setFecha(new \DateTime());
                $file->setDireccion($url);
                $file->setIdLecheEspiritual($leche);
                
                $em->persist($file);
                $em->flush();
                
                $em->commit();
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->clear();
                     $em->close();
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
        $em = $this->getDoctrine()->getEntityManager();

       
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
            $pastor = $datos['pastor'];
            
            $em->beginTransaction();
            
            //añadir las excepciones en los prepare
            
            try{
                  
                //cambiar pastor ejecutivo a cargo
                
                if(intval($pastor)!=-1)
                {
                    $sql = "UPDATE red SET  pastor=:pas WHERE id=:idx";
                    $smt = $em->getConnection()->prepare($sql);
                    $smt->execute(array(':pas'=>$pastor ,':idx'=>$code));
                }
                
                //cambio en tabla red
                if(intval($tip_red)==0)
                {
               
                    //cambio de lider
                    if(intval($ids)!= -1)
                    {                  
                                   
                        $sql = "select update_red(:idx,:tip,:igle,:lider,:pastor)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>$code,':tip'=>$tipo_red, ':igle'=>$iglesia, ':lider'=>$ids,':pastor'=>NULL));
                        
                        $em->clear();
                     
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
                        $em->clear();
              
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
                     
                        $em->clear();
                    }
                    else
                    {
                        $sql = "select update_red(:idx,:tip,:igle)";
                        $smt = $em->getConnection()->prepare($sql);
                   
                        $smt->execute(array(':idx'=>  $code,':tip'=> $tipo_red,':igle'=>$iglesia));
              
                        $em->clear();
                    }
            }
            
                //cambio de ubicacion
            
                $sql = "select  update_ubicacion(:idx,:dir, :refe, :lat,:lng, :ubigeo)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$ubicacion, ':dir'=>$direccion, ':refe'=>$referencia,':lat'=>$latitud,
                    ':lng'=>$longitud,':ubigeo'=>$distrito));
                $em->flush();
            
                $em->commit();
                $em->clear();
                
                $return=array("responseCode"=>200, "greeting"=>$retorno);
            }
            catch(Exception $e)
            {
                $em->rollback();
                $em->close();
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
        $em = $this->getDoctrine()->getEntityManager();            

       
        if($code!=NULL){               
            

            $em->beginTransaction();
            try
            {
                $sql = "select delete_red(:idx)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':idx'=>$code));
               
                $em->flush();
                
                $em->commit();
                
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>$code ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
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
    
    public function admincrearusuariosAction()
    {
        
         $request = $this->get('request');
        $code=$request->request->get('formName');
        $em = $this->getDoctrine()->getEntityManager();    
        
        $usuario = NULL; 
        $pass = NULL;
        $id = NULL;

         $datos = array();

        parse_str($code,$datos);
 
        if($code!=NULL){               
            

            $em->beginTransaction();
            try
            {
            $usuario = $datos['inputUsuario'];           
            $pass = $datos['inputPassword'];
            $id = $datos['persona_id'];
            
            $prev = $em->getRepository('AEDataBundle:Persona');
            $persona = $prev->findOneBy(array('id'=>$id));
                
                
               //roles
            $con = $em->getRepository('AEDataBundle:Rol');          
            $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
            
            //usuario                    
           $user = new Usuario();                 
                    $user->setNombre($usuario);
                    $user->setPassword($pass);
                    $user->setIdPersona($persona);
                    $user->addIdRol($rol);
                    
                    $em->persist($user);
                    $em->flush();
               
                $em->commit();
              
                $em->clear();
                $return=array("responseCode"=>200, "greeting"=>'ok' ); 
  
            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
   
               throw $e;
            }
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
             
        
                        $return=array("responseCode"=>200, "greeting"=>$code ); 

        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
   
    }
    
  
}
