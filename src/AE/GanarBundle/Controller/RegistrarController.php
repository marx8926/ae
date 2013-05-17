<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\RedSocial;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\NuevoConvertido;

use Symfony\Component\HttpFoundation\Response;

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
        
        
            $usuario = NULL;        
            $email = NULL;
            $pass = NULL;
            $nombres = NULL;
            $apellidos = NULL;
            $civil = NULL;
            $sexo = NULL;            
            $fech = NULL;
            $edad = NULL;            
            $telefono = NULL;
            $celular = NULL;            
            $direccion = NULL;
            $referencia = NULL;            
            $departamento = NULL;
            $provincia = NULL;
            $distrito = NULL;
            $latitud = NULL;
            $longitud = NULL;   
            $red = NULL;
            $celula = NULL;            
            $fechaConv = NULL;            
            $lugar = NULL;            
            $facebook = NULL;
            $twitter = NULL;
            $webpage = NULL;            
            $peticion = NULL;

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
              
           
            $em->beginTransaction();
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
                $em->clear();
                  
                //persona
                $persona = new Persona();
               
                $persona->setNombre($nombres);
                $persona->setApellidos($apellidos);
                $persona->setEstadoCivil($civil);
                
                if(strlen($email)>0)
                {    $persona->setEmail($email);
                
                /*
                    $request = $this->getRequest();
                    $message = \Swift_Message::newInstance()
                            ->setSubject('Gracias por registrarse')
                                ->setFrom('cmclmtrujillo@gmail.com')
                            ->setTo($email)
                            ->setBody($this->renderView('AEloginBundle:Default:holamundo.txt.twig',
                                    array('nombres' => $nombres,
                'apellidos'=>$apellidos,
                'subject'=>'Registro en AC')));
                $this->get('mailer')->send($message);
                 * *
                 */

                //$this->get('session')->setFlash('notice', 'Tu contacto fue enviado exitosamente. Dios te bendiga!');
  
                }
                
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
                $em->clear();

                 if(strlen($usuario)>0)
                 {    
                       //roles
                    $con = $em->getRepository('AEDataBundle:Rol');          
                    $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
                    //usuario
                    $em->clear();
                    
                    $user = new Usuario();                 
                    $user->setNombre($usuario);
                    $user->setPassword($pass);
                    $user->setIdPersona($persona);
                    $user->addIdRol($rol);
                    
                    $em->persist($user);
                    $em->flush();
                    $em->clear();
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
                    $em->clear();
                }      
                //lugar                  
                $con1 = $em->getRepository('AEDataBundle:Lugar');          
                $lug= $con1->findOneBy(array('id'=>$lugar));
                $em->clear();
                
                
                //nuevo convertido
                $nuev_con = new NuevoConvertido();
                $nuev_con->setId($persona);
                if(strcmp($red, '-1')!=0)
                {
                    //red
                    $con2 = $em->getRepository('AEDataBundle:Red');
                    $redU = $con2->findOneBy(array('id'=>$red));
                    $em->clear();
                
                    //celula
                    if(strcmp($celula, '-1')!=0)
                    {
                        $con3 = $em->getRepository('AEDataBundle:Celula');
                        $cell = $con3->findOneBy(array('id'=>$celula));
                        $em->clear();
                
                        $nuev_con->setIdCelula($cell);
                    }
                    $nuev_con->setIdRed($redU);
                }
                
                $nuev_con->setIdLugar($lug);
                
                $nuev_con->setPeticion($peticion);
                $nuev_con->setConsolidado(FALSE);
                $nuev_con->setFechaConversion(new \DateTime($fechaConv)); 
                $em->persist($nuev_con);
                $em->flush();
                $em->clear();
                
                //miembro
                
                $miembro = new Miembro();
                $miembro->setId($persona);
                
                if(strcmp($red, '-1')!=0)
                {
                    $miembro->setIdRed($redU);
                    if(strcmp($celula, '-1')!=0)
                        $miembro->setIdCelula($cell);
                }
                $miembro->setActivo(TRUE);
                $miembro->setFechaObtencion(new \DateTime($fechaConv));
                $miembro->setAptoConsolidar(FALSE);
                
                $em->persist($miembro);
                $em->flush();
                $em->clear();
                
                
                $em->commit();
                

                $return=array("responseCode"=>200,  "greeting"=>'OK');
 
            }catch(Exception $e)
            {
               $em->rollback();
               $em->clear();
               $em->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
               
            }
        }else
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
         
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     
    }
 
}
