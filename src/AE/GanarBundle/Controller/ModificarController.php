<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Usuario;

use Symfony\Component\HttpFoundation\Response;

class ModificarController extends Controller
{
    //actualizar miembro con metodo post
    public function actualizarmiembroAction()
    {
         $request = $this->get('request');
        $name=$request->request->get('formName');

        $datos = array();

        parse_str($name,$datos);
              
                  
        if($name!=NULL){

            $id = $datos['inputId']; 
            $red = $datos['red_lista'];
            $celula = $datos['celula_lista'];
                        
            $em = $this->getDoctrine()->getEntityManager();         
      
            if(strcmp($red,'-1')==0)
                    $red=NULL;
            if(strcmp($celula,'-1')==0)
                    $celula=NULL;
           
            $em->beginTransaction();
            try
            {
               
                //actualizar miembro
               
                $sql = "UPDATE miembro SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                $smt = $em->getConnection()->prepare($sql);
                if($smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                {                    
                      //actualizar nuevo convertido               
                
                    $sql = "UPDATE nuevo_convertido SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if($smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                        $return=array("responseCode"=>200,  "greeting"=>'OK');
                    else {
                        $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                }
                else  $return=array("responseCode"=>400,  "greeting"=>'Bad');
           
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
        }else
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
         
        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
     
    }
    
        
        //vista de modificar
    public  function modificarAction($id)
    {
        
        $nombre = NULL;
        $dni = NULL;
        $ocupacion = NULL;
        $apellidos = NULL;
        $estado_civil = NULL;
        $edad = NULL;
        $telefono = NULL;
        $celular = NULL;
        $fecha_nacimiento = NULL;
        $email = NULL;
        $website = NULL;
        $sexo = NULL;
        $id_ubicacion = NULL;
        $direccion = NULL;
        $referencia = NULL;
        $latitud = NULL;
        $longitud = NULL;
        $id_ubigeo = NULL;
        $departamento = NULL;
        $provincia = NULL;
        $distrito = NULL;
        $red = NULL;
        $celula = NULL;
        $lider_ap = NULL;
        $lider_nom = NULL;
        $cons_ap = NULL;
        $cons_nom = NULL;
        $conversion = NULL;
        $peticion = NULL;
        $lugar = NULL;
        
        $ubigeo = array();
        $redes = array();
        $convertido = array();
        $dia = NULL;
        $hora = NULL;
        
        $em = $this->getDoctrine()->getEntityManager();

        
        try {
            $em->beginTransaction();
            $sql_persona = "select * from get_persona(:id)";

       
            $smt = $em->getConnection()->prepare($sql_persona);
            $smt->execute(array(':id'=>$id));
 
            $redes = $smt->fetch();
            
            $nombre = $redes['nombre'];
            $apellidos = $redes['apellidos'];
            $estado_civil = $redes['estado_civil'];
            $edad = $redes['edad'];
            $telefono = $redes['telefono'];
            $celular = $redes['celular'];
            $dni = $redes['dni'];
            $ocupacion = $redes['ocupacion'];
            
            $fecha_nacimiento = $redes['fecha_nacimiento'];
                     
            $email = $redes['email'];
            $website = $redes['website'];
            $sexo = $redes['sexo'];
            $id_ubicacion = $redes['id_ubicacion'];
            $direccion = $redes['direccion'];
            $referencia = $redes['referencia'];
            $latitud = $redes['latitud'];
            $longitud = $redes['longitud'];
            $id_ubigeo = $redes['id_ubigeo'];
            
            
            
            //ubigeo
            $sql = " select  * from get_ubigeo(:id)";
            $smt1 = $em->getConnection()->prepare($sql);
            $smt1->execute(array(':id'=>$id_ubigeo));
            $ubigeo = $smt1->fetch();
            
            $departamento = $ubigeo['coddepartamento'];
            $provincia = $ubigeo['codprovincia'];
            $distrito = $ubigeo['id1'];
            
            //nuevo convertido
            
            $sql = "select * from get_convertido(:id)";
             $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $convertido = $smt->fetch();
            $red = $convertido['id_red'];
            $celula = $convertido['id_celula'];            
            
            $conversion = $convertido['fecha_conversion'];              
                       
            $peticion = $convertido['peticion'];
            $lugar = $convertido['id_lugar'];
            $hora = $convertido['hora'];
            $dia = $convertido['dia'];
            
            //lider de red
            
            $sql = "select * from get_lider_red(:idx)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$red));
            $lider_red = $smt->fetch();
            $lider_nom = $lider_red['nombre'];
            $lider_ap = $lider_red['apellidos'];
            
            
            //lider de celula
            $sql = "select * from get_consolidador(:idx)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$convertido['id']));
            $cons = $smt->fetch();
            
            $cons_nom = $cons['nombre'];
            $cons_ap = $cons['apellidos'];
            
            $em->commit();
            $em->clear();
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       $nac = new \DateTime($fecha_nacimiento);
       $conv = new \DateTime($conversion);
       
        return $this->render('AEGanarBundle:Default:modificar.html.twig',array('id'=>$id,'nombre'=>$nombre,
            'apellidos'=>$apellidos,'estado_civil'=>$estado_civil,'edad'=>$edad, 'telefono'=>$telefono,'celular'=>$celular,
            'fecha_nacimiento'=>$nac->format('d/m/Y'),'email'=>$email,'website'=>$website,'sexo'=>$sexo,'id_ubicacion'=>$id_ubicacion,
            'direccion'=>$direccion,'referencia'=>$referencia,'latitud'=>$latitud,'longitud'=>$longitud,'id_ubigeo'=>$id_ubigeo,
            'departamento'=>$departamento,'provincia'=>$provincia,'distrito'=>$distrito,
            'red'=>$red,'celula'=>$celula,'lider'=>$lider_ap.' '.$lider_nom,'cons'=>$cons_ap.' '.$cons_nom,
            'conversion'=>$conv->format('d/m/Y'),'peticion'=>$peticion,'lugar'=>$lugar,'dni'=>$dni,
            'ocupacion'=>$ocupacion,'dia'=>$dia,'hora'=>$hora));
       //return $this->render('AEGanarBundle:Default:modificar.html.twig',array('id'=>$id));
    }
    
    //modificar miembro
    public function modificaractualizarAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $ubigeo = $request->request->get('ubi');
        $id = $request->request->get('id');

        $datos = array();

        $em = $this->getDoctrine()->getEntityManager();         

        parse_str($name,$datos);
                  
        if($name!=NULL){
            
            $user   = $datos['inputUsuario'];
            $email  = $datos['inputEmail'];
            $pass   = $datos['inputPassword'];
            $passN  = $datos['inputPasswordN'];
            $nombre = $datos['inputNombres'];
            $apellido = $datos['inputApellidos'];
            $civil = $datos['select_Civil'];
            $sexo = $datos['select_Sexo'];
            
            
            $naci_b = $datos['inputFechaNacimiento'];              
            $naci_a = explode('/', $naci_b, 3);            
            $naci = $naci_a[2].'-'.$naci_a[1].'-'.$naci_a[0];          
            
            $edad = $datos['inputEdad'];
            $tel = $datos['inputTelefono'];
            $cel = $datos['inputCelular'];
            $dir = $datos['inputDireccion'];
            $ref = $datos['inputReferencia'];
            $dni = $datos['inputDni'];
            $ocupacion = $datos['inputOcupacion'];
            
            $dia = $datos['dia_lista'];
            $hora = $datos['inputHora'];
            
            $dep = $datos['departamento_lista'];
            $prov = $datos['provincia_lista'];
            $dist = $datos['distrito_lista'];
            $lat = $datos['latitud'];
            $lng = $datos['longitud'];
            $red = $datos['red_lista'];
            $cell = $datos['celula_lista'];
            $fech_con_b = $datos['inputFechaConversion'];
                          
            $fecha_con_a = explode('/', $fech_con_b, 3);
            
            $fech_con = $fecha_con_a[2].'-'.$fecha_con_a[1].'-'.$fecha_con_a[0];
          
            
            $lugar = $datos['lugar'];
            $face = $datos['inputFacebook'];
            $twitt = $datos['inputTwitter'];
            $web = $datos['inputWebpage'];
            $desc = $datos['inputDescripcion'];

            $em->beginTransaction();
            try {
                     
            //actualizar persona
                
            $sql = "UPDATE persona SET  nombre = :nombre ,  apellidos = :apellido, estado_civil = :civil, edad = :edad, telefono = :tel, celular = :cel, fecha_nacimiento = :nac , email = :email, website = :website, sexo = :sexo, ".
                    "ocupacion=:ocu, dni=:iden WHERE id = :codigo";
             
            $smt = $em->getConnection()->prepare($sql);
            if($smt->execute(array(':nombre'=>$nombre, ':apellido'=>$apellido,':codigo'=>$id,':civil'=>$civil,
                ':edad'=>$edad,':tel'=>$tel, ':cel'=>$cel, ':nac'=>$naci, ':email'=>$email, ':website'=>$web,
                ':sexo'=>$sexo, ':ocu'=>$ocupacion, ':iden'=>$dni)))
            {
                
                //actualizar ubicacion
               $sql = "UPDATE ubicacion SET direccion=:dir, latitud=:lat,longitud =:lng, referencia=:ref, id_ubigeo=:ubig where id=:ubq";
                $smt_ubi = $em->getConnection()->prepare($sql);
                if(!$smt_ubi->execute(array(':dir'=>$dir,':lat'=>$lat,':lng'=>$lng, ':ref'=>$ref, ':ubig'=>$dist,':ubq'=>$ubigeo)))
                {
                    
                }
                
                
                //actualizar usuario
                
                if(strlen($user)>0 && strlen($email)>0 && strlen($passN)>0)
                {
                    
                    $prev_div = $em->getRepository('AEDataBundle:Usuario');
                    $usuario = $prev_div->findOneBy(array('nombre'=>$user));
                    if($usuario !=NULL)
                    {
                    $usuario->setPassword($passN);
                    
                    $em->persist($usuario);
                    $em->flush();
                    }
                    else
                    {
                        $nuser = new Usuario();
                        $nuser->setNombre($user);
                        $nuser->setPassword($pass);
                    
                       //roles
                        $con = $em->getRepository('AEDataBundle:Rol');          
                        $rol = $con->findOneBy(array('nombre'=>'ROLE_USER')); 
                    
                        $nuser->addIdRol($rol);
                    
                        $pers = $em->getRepository('AEDataBundle:Persona');
                        $persona = $pers->findOneBy(array('id'=>$id));
                    
                        $nuser->setIdPersona($persona);
                    
                        $em->persist($nuser);
                    
                        $em->flush();
                    }
                }
                else
                {
                    
                }
                
                if(strcmp($red, '-1')==0)
                        $red = NULL;
                if(strcmp($cell, '-1')==0)
                        $cell = NULL;
                //nuevo convertido
                
                $sql = "UPDATE nuevo_convertido SET id_celula =:cel, id_red=:red, fecha_conversion=:conv, ".
                        "id_lugar=:lug, peticion=:pet , dia=:day, hora=:hour WHERE id=:code";
                $smt_con = $em->getConnection()->prepare($sql);
                if(!$smt_con->execute(array(':cel'=>$cell,':red'=>$red,':lug'=>$lugar,':code'=>$id,
                    ':conv'=>$fech_con , ':pet'=>$desc, ':day'=>$dia, ':hour'=>$hora)))
                {
                        
                }
                /*if(strcmp($red,'-1')==0 || strcmp($cell, '-1')==0)
                {
                    $sql = "UPDATE nuevo_convertido SET id_celula =:cel, id_red=:red,  id_lugar=:lug , peticion=:pet WHERE id=:code";
                    $smt_con = $em->getConnection()->prepare($sql);
                    if(!$smt_con->execute(array(':cel'=>NULL,':red'=>NULL,':lug'=>$lugar,':code'=>$id, ':pet'=>$desc)))
                    {
                        
                    }
                    
                }
                else {
                    $sql = "UPDATE nuevo_convertido SET id_celula =:cel, id_red=:red,  id_lugar=:lug, peticion=:pet WHERE id=:code";
                    $smt_con = $em->getConnection()->prepare($sql);
                    if(!$smt_con->execute(array(':cel'=>$cell,':red'=>$red,':lug'=>$lugar,':code'=>$id, ':pet'=>$desc)))
                    {
                        
                    }
                }*/
                
                //miembro
                //añadido para miembro
                $sql = "UPDATE miembro SET id_celula =:cel, id_red=:red, fecha_obtencion =:conv WHERE id=:code";
                $smt_con = $em->getConnection()->prepare($sql);
                if(!$smt_con->execute(array(':cel'=>$cell,':red'=>$red,':code'=>$id, ':conv'=>$fech_con)))
                {
                        
                }
                /*
                if(strcmp($red,'-1')==0 || strcmp($cell, '-1')==0)
                {
                    $sql = "UPDATE miembro SET id_celula=:cel, id_red=:red  WHERE id=:code";
                    $smt_con = $em->getConnection()->prepare($sql);
                    if(!$smt_con->execute(array(':cel'=>NULL,':red'=>NULL,':code'=>$id)))
                    {
                        
                    }
                    
                }
                else {
                    //if(strcmp($cel,'-1')==0 && strcmp($red,'-1')==0)
                    //{
                        $sql = "UPDATE miembro SET id_celula =:cel, id_red=:red WHERE id=:code";
                        $smt_con = $em->getConnection()->prepare($sql);
                        if(!$smt_con->execute(array(':cel'=>$cell,':red'=>$red,':code'=>$id)))
                        {
                        
                        }
                    //}
                }
                 * 
                 */
                //redes sociales
                //facebook
                if(strlen($face)>0)
                {
                    $sql = "UPDATE red_social SET enlace =:link WHERE id_persona=:code AND tipo='facebook'";
                    $smt_con = $em->getConnection()->prepare($sql);
                    if(!$smt_con->execute(array(':code'=>$id,':link'=>$face)))
                    {
                        
                    }
                }
                //twitter
                if(strlen($twitt)>0)
                {
                    $sql = "UPDATE red_social SET enlace =:link WHERE id_persona=:code AND tipo='twitter'";
                    $smt_con = $em->getConnection()->prepare($sql);
                    if(!$smt_con->execute(array(':code'=>$id,':link'=>$face)))
                    {
                        
                    }
                }
                
            }
            
            $em->commit();
            $em->clear();
            
            $return=array("responseCode"=>200,  "greeting"=>'OK');
            } catch (Exception $exc) {
                $em->rollback();
                 $return=array("responseCode"=>400, "greeting"=>"Bad"); 
                 throw $exc;
            }
        }
        else
        $return=array("responseCode"=>400, "greeting"=>"Bad");     

        $return=json_encode($return);//jscon encode the array
        
        $em->clear();
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
       
    }
 
    public function eliminarmiembroAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        try{
            $em->beginTransaction();
            
            $sql = "select delete_persona_miembro(:idx)"; //lo cambie por delete_persona en psql
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$id));
            
            $em->commit();
             $return=array("responseCode"=>200, "greeting"=>"Good");  
        }
        catch(Exception $e)
        {
             $return=array("responseCode"=>400, "greeting"=>"Bad");  
             $em->rollback();
             $em->close();
             
             echo $e->getTraceAsString();
        }

        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
      
    }
    
    public function modificarganadorAction()
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
            }      
        
        return $this->render('AEGanarBundle:Default:ganador.html.twig', array('red'=>$red));
       }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');

    }
    
    public function modificarganadorupAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
      
        $datos = array();

        parse_str($name,$datos);
     
        $red = NULL;
        $tipo = NULL;
        $lider = NULL;
        $id = NULL;
       
       $em = $this->getDoctrine()->getEntityManager();   
       $red = $datos['red_lista'];
       $lider = $datos['lideres_lista'];

       if($name!=NULL && strval($red)!='-1' && strval($lider)!='-1'){
                
           

            $em->beginTransaction();
            try
            {
                $tipo = $datos['tipo_lista'];
                $id = $datos['nuevo'];
                
                $sql = "select update_ganador_persona(:red, :padre,:id)";
                $smt = $em->getConnection()->prepare($sql);
                $smt->execute(array(':red'=>$red,':padre'=>$lider,':id'=>$id));
                
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
       }
       else 
       {
          $return = array("responseCode"=>400, "greeting"=>"Bad");

       }
                     
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
        
    }

}
