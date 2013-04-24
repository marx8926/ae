<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AE\DataBundle\Entity\Ubigeo;
use AE\DataBundle\Entity\Ubicacion;
use AE\DataBundle\Entity\Celula;
use AE\DataBundle\Entity\Red;
use AE\DataBundle\Entity\RedSocial;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Usuario;
use AE\DataBundle\Entity\Rol;
use AE\DataBundle\Entity\Miembro;
use AE\DataBundle\Entity\NuevoConvertido;
use AE\DataBundle\Entity\Lugar;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
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
        return $this->render('AEGanarBundle:Default:index.html.twig', array('name' => $name));
    }
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
 
    public function busquedaAction()
    {
        return $this->render('AEGanarBundle:Default:busqueda.html.twig');
    }
    
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
      
            $return=array("responseCode"=>200,  "greeting"=>'OK');
              
           
            $this->getDoctrine()->getEntityManager()->beginTransaction();
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

    
    public function vistaAction($id)
    {
        $nombre = NULL;
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
        
        $ubigeo = array();
        $redes = array();
        $convertido = array();
        
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
            
            $departamento = $ubigeo['departamento'];
            $provincia = $ubigeo['provincia'];
            $distrito = $ubigeo['distrito'];
            
            //nuevo convertido
            
            $sql = "select * from get_convertido(:id)";
             $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$id));
 
            $convertido = $smt->fetch();
            $red = $convertido['id_red'];
            $celula = $convertido['id_celula'];
            $conversion = $convertido['fecha_conversion'];
            
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
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       
        return $this->render('AEGanarBundle:Default:vista.html.twig',array('id'=>$id,'nombre'=>$nombre,
            'apellidos'=>$apellidos,'estado_civil'=>$estado_civil,'edad'=>$edad, 'telefono'=>$telefono,'celular'=>$celular,
            'fecha_nacimiento'=>$fecha_nacimiento,'email'=>$email,'website'=>$website,'sexo'=>$sexo,'id_ubicacion'=>$id_ubicacion,
            'direccion'=>$direccion,'referencia'=>$referencia,'latitud'=>$latitud,'longitud'=>$longitud,'id_ubigeo'=>$id_ubigeo,
            'departamento'=>$departamento,'provincia'=>$provincia,'distrito'=>$distrito,
            'red'=>$red,'celula'=>$celula,'lider'=>$lider_ap.' '.$lider_nom,'cons'=>$cons_ap.' '.$cons_nom,
            'conversion'=>$conversion));
    }
    
    public function printAction()
    {
        $html = $this->renderView('AEGanarBundle:Default:vista.html.twig', array('id'  => '33'));
        
        return new Response(
    $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
    200,
    array(
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="file.pdf"'
    )
);
        
        
    }

    
    public function fileAction()
    {
        return $this->render('AEGanarBundle:Default:file.html.twig');
    }
    
    public function editAction()
    {
        $request = $this->getRequest();

    $editId = $this->getRequest()->get('editId');
        if (!preg_match('/^\d+$/', $editId))
        {
         $editId = sprintf('%09d', mt_rand(0, 1999999999));
     if ($posting->getId())
        {
            $this->get('punk_ave.file_uploader')->syncFiles(
                array('from_folder' => 'attachments/' . $posting->getId(), 
                'to_folder' => 'tmp/attachments/' . $editId,
              'create_to_folder' => true));
    }
    }

    }
    public function uploadAction()
    {
        
    }
    //vista de modificar
    public  function modificarAction($id)
    {
        
        $nombre = NULL;
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
            
        } catch (Exception $exc) {
            $em->rollback();
            $em->close();
            throw $exc;
        }

       
        return $this->render('AEGanarBundle:Default:modificar.html.twig',array('id'=>$id,'nombre'=>$nombre,
            'apellidos'=>$apellidos,'estado_civil'=>$estado_civil,'edad'=>$edad, 'telefono'=>$telefono,'celular'=>$celular,
            'fecha_nacimiento'=>$fecha_nacimiento,'email'=>$email,'website'=>$website,'sexo'=>$sexo,'id_ubicacion'=>$id_ubicacion,
            'direccion'=>$direccion,'referencia'=>$referencia,'latitud'=>$latitud,'longitud'=>$longitud,'id_ubigeo'=>$id_ubigeo,
            'departamento'=>$departamento,'provincia'=>$provincia,'distrito'=>$distrito,
            'red'=>$red,'celula'=>$celula,'lider'=>$lider_ap.' '.$lider_nom,'cons'=>$cons_ap.' '.$cons_nom,
            'conversion'=>$conversion,'peticion'=>$peticion,'lugar'=>$lugar));
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
            $naci = $datos['inputFechaNacimiento'];
            $edad = $datos['inputEdad'];
            $tel = $datos['inputTelefono'];
            $cel = $datos['inputCelular'];
            $dir = $datos['inputDireccion'];
            $ref = $datos['inputReferencia'];
            $dep = $datos['departamento_lista'];
            $prov = $datos['provincia_lista'];
            $dist = $datos['distrito_lista'];
            $lat = $datos['latitud'];
            $lng = $datos['longitud'];
            $red = $datos['red_lista'];
            $cell = $datos['celula_lista'];
            $fech_con = $datos['inputFechaConversion'];
            $lugar = $datos['lugar'];
            $face = $datos['inputFacebook'];
            $twitt = $datos['inputTwitter'];
            $web = $datos['inputWebpage'];
            $desc = $datos['inputDescripcion'];
            

            $em = $this->getDoctrine()->getEntityManager();         

            try {
                
            

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            
            //actualizar persona
                
            $sql = "UPDATE persona SET  nombre = :nombre ,  apellidos = :apellido, estado_civil = :civil, edad = :edad, telefono = :tel, celular = :cel, fecha_nacimiento = :nac , email = :email, website = :website, sexo = :sexo WHERE id = :codigo";
             
            $smt = $em->getConnection()->prepare($sql);
            if($smt->execute(array(':nombre'=>$nombre, ':apellido'=>$apellido,':codigo'=>$id,':civil'=>$civil, ':edad'=>$edad,':tel'=>$tel, ':cel'=>$cel, ':nac'=>$naci, ':email'=>$email, ':website'=>$web, ':sexo'=>$sexo)))
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
                        $con = $this->getDoctrine()->getManager()->getRepository('AEDataBundle:Rol');          
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
                
                //nuevo convertido
                if(strcmp($red,'-1')==0)
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
                }
                
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
            
            $this->getDoctrine()->getEntityManager()->commit();

            $return=array("responseCode"=>200,  "greeting"=>'OK');
            } catch (Exception $exc) {
                $this->getDoctrine()->getEntityManager()->rollback();
                 $return=array("responseCode"=>400, "greeting"=>"Bad");     
            }
        }
        else
        $return=array("responseCode"=>400, "greeting"=>"Bad");     

        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
       
    }


    //vista de asignar miembro
    public function asignarAction()
    {
        return $this->render('AEGanarBundle:Default:asignar_red.html.twig');
    }
    
    //actualizar asignar miembro
    public function asignarupdateAction()
    {
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $id = $request->request->get('id');

        $datos = array();

        parse_str($name,$datos);
        
        if($name!=NULL){
            
            $red   = $datos['red_lista'];
            
             $em = $this->getDoctrine()->getEntityManager();         

            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                if($red != '-1')
                {
                    $celula = $datos['celula_lista'];
                
                    //nuevo convertido
                    $sql = "UPDATE nuevo_convertido SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                
                    //miembro
                    $sql = "UPDATE miembro SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>$celula,':red'=>$red, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                }
                else{
                
                     $sql = "UPDATE nuevo_convertido SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>NULL,':red'=>NULL, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                
                    //miembro
                    $sql = "UPDATE miembro SET  id_celula= :celula,  id_red= :red WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':celula'=>NULL,':red'=>NULL, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                }
               
                
           
                 $this->getDoctrine()->getEntityManager()->commit();
                 
                 $return=array("responseCode"=>200,  "greeting"=>'OK');

        
            }catch(Exception $e)
            {
               $this->getDoctrine()->getEntityManager()->rollback();
               $this->getDoctrine()->getEntityManager()->close();
                
               $return=array("responseCode"=>400, "greeting"=>"Bad");

               throw $e;
               
            }
            
        }
        else $return=array("responseCode"=>400, "greeting"=>"Bad");     

        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
      
    }
    
    public function eliminarmiembroAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        try{
            $em->beginTransaction();
            
            $sql = "select delete_persona(:idx)";
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
    
}
