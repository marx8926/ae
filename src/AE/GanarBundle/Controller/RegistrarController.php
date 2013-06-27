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
            
            $red = $req['red'];
        }

          $result = $this->render('AEGanarBundle:Default:registrar.html.twig', array('red'=>$red));
          $result->setMaxAge(100);
          
        }
        else{ $result = $this->render('AEGanarBundle:Default:sinacceso.html.twig');
            $result->setPublic();
            $result->setMaxAge(100);
        }
        
        return $result;

    }
    public function addpersonaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $securityContext = $this->get('security.context');


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
            $dni = NULL;
            $ocupacion = NULL;
            $dia = NULL;
            $hora = NULL;
            $ganador = NULL;

        if($name!=NULL){

             $usuario = $datos['inputUsuario'];           
     
            $email = $datos['inputEmail'];
            $pass = $datos['inputPassword'];
        
            $nombres = $datos['inputNombres'];
            $apellidos = $datos['inputApellidos'];
            
            $civil = $datos['select_Civil'];
            $sexo = $datos['select_Sexo'];
            
            $fech_b = $datos['inputFechaNacimiento'];
            
            $fech_a =explode('/', $fech_b,3);
            $fech = $fech_a[2].'-'.$fech_a[1].'-'.$fech_a[0];
            
            
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
            
            $fechaConv_b = $datos['inputFechaConversion'];
            
            $fechaConv_a = explode('/', $fechaConv_b, 3);
            
            $fechaConv = $fechaConv_a[2].'-'.$fechaConv_a[1].'-'.$fechaConv_a[0];            
            
            $lugar = $datos['lugar'];
            
            $facebook = $datos['inputFacebook'];
            $twitter = $datos['inputTwitter'];
            $webpage = $datos['inputWebpage'];
            
            $peticion = $datos['inputDescripcion'];            
            
            $dni = $datos['inputDni'];
            $ocupacion = $datos['inputOcupacion'];
            
            $dia = $datos['dia_lista'];
            $hora = $datos['inputHora'];            
                
            $em = $this->getDoctrine()->getEntityManager();       

          
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
                
                //persona           

                $persona = new Persona();
                $persona->setApellidos($apellidos);
                $persona->setNombre($nombres);
                $persona->setCelular($celular);
                $persona->setTelefono($telefono);
                $persona->setEstadoCivil($civil);
                $persona->setEdad($edad);
                $persona->setFechaNacimiento(new \DateTime($fech));
                $persona->setSexo($sexo);
                $persona->setIdUbicacion($ubicacion);
                $persona->setDni($dni);
                $persona->setOcupacion($ocupacion);
                
                if(strlen($webpage)>0)
                    $persona->setWebsite($webpage);
                
                if(strlen($email)>0)
                {    $persona->setEmail($email);
                
                }
                $em->persist($persona);
                $em->flush();
                
                 if(strlen($usuario)>0)
                 {    
                       //roles
                    $con = $em->getRepository('AEDataBundle:Rol');          
                    $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
                    //usuario
                    
                    $user = new Usuario();                 
                    $user->setNombre($usuario);
                    $user->setPassword($pass);
                    $user->setIdPersona($persona);
                    $user->addIdRol($rol);
                    $user->setEnabled(TRUE);
                    
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
                $con1 = $em->getRepository('AEDataBundle:Lugar');          
                $lug= $con1->findOneBy(array('id'=>$lugar));
                
                
              
                //nuevo convertido
                $nuev_con = new NuevoConvertido();
                $nuev_con->setId($persona);
                if(strcmp($red, '-1')!=0)
                {
                    //red
                    $con2 = $em->getRepository('AEDataBundle:Red');
                    $redU = $con2->findOneBy(array('id'=>$red));
                
                    //celula
                    if(strcmp($celula, '-1')!=0)
                    {
                        $con3 = $em->getRepository('AEDataBundle:Celula');
                        $cell = $con3->findOneBy(array('id'=>$celula));
                
                        $nuev_con->setIdCelula($cell);
                    }
                    $nuev_con->setIdRed($redU);
                }
                
                $nuev_con->setIdLugar($lug);
                
                $nuev_con->setPeticion($peticion);
                $nuev_con->setConsolidado(FALSE);
                $nuev_con->setFechaConversion(new \DateTime($fechaConv)); 
                $nuev_con->setDia($dia);
                $nuev_con->setHora(new \DateTime($hora));
                

                if($securityContext->isGranted('ROLE_PROFESOR') || $securityContext->isGranted('ROLE_LIDERSIN') ||
                        $securityContext->isGranted('ROLE_CONSOLIDADOR'))
                {
                    $ganador = $securityContext->getToken()->getUser()->getIdPersona();
                            
                    if( $ganador !=NULL)
                    {
                        $nuev_con->setGanador($ganador->getId());
                    }
                }
                
                $em->persist($nuev_con);
                $em->flush();
           
                
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
                                
                $em->commit();
                
                
                $em->clear();

                $return=array("responseCode"=>200,  "greeting"=>'ok');
 
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
