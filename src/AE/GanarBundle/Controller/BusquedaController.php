<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedaController extends Controller
{

    public function busquedaAction()
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
            
            $red = $req['red'];
        }
        
        return $this->render('AEGanarBundle:Default:busqueda.html.twig',
                array('red'=>$red));
        }
        else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
        
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
        $peticion = NULL;
        $dni = NULL;
        $ocupacion = NULL;
        
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
            $dni = $redes['dni'];
            $ocupacion = $redes['ocupacion'];
            
            
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
            $peticion = $convertido['peticion'];
            
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

       
        return $this->render('AEGanarBundle:Default:vista.html.twig',array('id'=>$id,'nombre'=>$nombre,
            'apellidos'=>$apellidos,'estado_civil'=>$estado_civil,'edad'=>$edad, 'telefono'=>$telefono,'celular'=>$celular,
            'fecha_nacimiento'=>$fecha_nacimiento,'email'=>$email,'website'=>$website,'sexo'=>$sexo,'id_ubicacion'=>$id_ubicacion,
            'direccion'=>$direccion,'referencia'=>$referencia,'latitud'=>$latitud,'longitud'=>$longitud,'id_ubigeo'=>$id_ubigeo,
            'departamento'=>$departamento,'provincia'=>$provincia,'distrito'=>$distrito,
            'red'=>$red,'celula'=>$celula,'lider'=>$lider_ap.' '.$lider_nom,'cons'=>$cons_ap.' '.$cons_nom,
            'conversion'=>$conversion,'peticion'=>$peticion,
            'dni'=>$dni, 'ocupacion'=>$ocupacion));
    }
    
}
