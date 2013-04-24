<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\RedSocial;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\NuevoConvertido;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class RegistrarController extends Controller
{
        
    public function personaAction()
    {
        return $this->render('AEGanarBundle:Default:registrar.html.twig');
    }
    public function addpersonaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');

        $datos = array();

        parse_str($name,$datos);

        if($name!=NULL){

             $usuario = $datos['inputUsuario'];           
     
            $email = $datos['inputEmail'];
            $pass = $datos['inputPassword'];
        
            $nombres = $datos['inputNombres'];
            $apellidos = $datos['inputApellidos'];
            
            $civil = $datos['select_Civil'];
            $sexo = $datos['select_Sexo'];
            
            $fech = $datos['inputFechaNacimiento'];
            $edad = $datos['inputEdad'];
            
            $telefono = $datos['inputTelefono'];
            $celular = $datos['inputCelular'];
            
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
   
            $red = $datos['red_lista'];
            if(strcmp($red, '-1')!=0)
             $celula = $datos['celula_lista'];
            
            $fechaConv = $datos['inputFechaConversion'];
            
            $lugar = $datos['lugar'];
            
            $facebook = $datos['inputFacebook'];
            $twitter = $datos['inputTwitter'];
            $webpage = $datos['inputWebpage'];
            
            $peticion = $datos['inputDescripcion'];
            
            $em = $this->getDoctrine()->getEntityManager();         
            
         
              $return=array("responseCode"=>200,  "greeting"=>'OK');
              
           
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                //ubigeo
                $prev_div = $em->getRepository('AEDataBundle:Ubigeo');
                $ubigeo = $prev_div->findOneBy(array('id'=>$distrito));

                  //ubicacion
                $ubicacion = new Ubicacion();
                $ubicacion->setDireccion($direccion);
                $ubicacion->setReferencia($referencia);
                $ubicacion->setLatitud($latitud);
                $ubicacion->setLongitud($longitud);
                $ubicacion->setIdUbigeo($ubigeo);
                            
                $em->persist($ubicacion);
                $em->flush();
                  
                //persona
                $persona = new Persona();
               
                $persona->setNombre($nombres);
                $persona->setApellidos($apellidos);
                $persona->setEstadoCivil($civil);
                
                if(strlen($email)>0)
                    $persona->setEmail($email);
                
                $persona->setCelular($celular);
                $persona->setTelefono($telefono);
                $persona->setEdad($edad);
               
                $persona->setFechaNacimiento(new \DateTime($fech));
                $persona->setIdUbicacion($ubicacion);
                
                if(strlen($webpage)>0)
                    $persona->setWebsite($webpage);
                
                $persona->setSexo($sexo);
                
                $em->persist($persona);
                $em->flush();

                 if(strlen($usuario)>0)
                 {    
                       //roles
                    $con = $this->getDoctrine()->getManager()->getRepository('AEDataBundle:Rol');          
                    $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
                    //usuario
                    
                    $user = new Usuario();                 
                    $user->setNombre($usuario);
                    $user->setPassword($pass);
                    $user->setIdPersona($persona);
                    $user->addIdRol($rol);
                    
                    $em->persist($user);
                    $em->flush();
                 }               
                 
                
                if(strlen($facebook)>0)
                {
                    //red_social
                    $red_social = new RedSocial();
                    $red_social->setIdPersona($persona);
                    $red_social->setTipo('facebook');
                    $red_social->setEnlace($facebook);
                    $em->persist($red_social);
                    $em->flush();
                }      
                //lugar                  
                $con1 = $this->getDoctrine()->getManager()->getRepository('AEDataBundle:Lugar');          
                $lug= $con1->findOneBy(array('id'=>$lugar)); 
                
                //nuevo convertido
                $nuev_con = new NuevoConvertido();
                $nuev_con->setId($persona);
                if(strcmp($red, '-1')!=0)
                {
                    //red
                    $con2 = $this->getDoctrine()->getManager()->getRepository('AEDataBundle:Red');
                    $redU = $con2->findOneBy(array('id'=>$red));
                
                    //celula
                    $con3 = $this->getDoctrine()->getManager()->getRepository('AEDataBundle:Celula');
                    $cell = $con3->findOneBy(array('id'=>$celula));
                
                    $nuev_con->setIdCelula($cell);
                    $nuev_con->setIdRed($redU);
                }
                
                $nuev_con->setIdLugar($lug);
                
                $nuev_con->setPeticion($peticion);
                $nuev_con->setConsolidado(FALSE);
                $nuev_con->setFechaConversion(new \DateTime($fechaConv)); 
                $em->persist($nuev_con);
                $em->flush();
                
                //miembro
                /*
                $miembro = new Miembro();
                $miembro->setId($persona);
                
                if(strcmp($red, '-1')!=0)
                {
                    $miembro->setIdRed($redU);
                    $miembro->setIdCelula($cell);
                }
                $miembro->setActivo(TRUE);
                $miembro->setFechaObtencion(new \DateTime($fechaConv));
                $miembro->setAptoConsolidar(FALSE);
                
                $em->persist($miembro);
                $em->flush();
                */
                
                $this->getDoctrine()->getEntityManager()->commit();

                $return=array("responseCode"=>200,  "greeting"=>'OK');
 
            }catch(Exception $e)
            {
               $this->getDoctrine()->getEntityManager()->rollback();
               $this->getDoctrine()->getEntityManager()->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
               
            }
        }else
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
         
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     
    }
 
}
