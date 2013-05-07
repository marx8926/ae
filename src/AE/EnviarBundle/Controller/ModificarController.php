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
use AE\DataBundle\Entity\ClaseCell;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ModificarController extends Controller
{
        public function modificar_celulaAction()
    {
        $request = $this->get('request');
        
        $form=$request->request->get('idpersona');
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $sql = "select * from ver_celula(:id)";
        
        $id=NULL;
        $tipo=NULL;
        $familia=NULL;
        $telefono=NULL;
        $activo=NULL;
        $caso=NULL;
        $ubi_id=NULL;
        $direccion=NULL;
        $referencia=NULL;
        $latitud=NULL;
        $longitud=NULL;
        $id_ubigeo=NULL;
        $red = NULL;
        $distrito = NULL;
        $departamento = NULL;
        $provincia = NULL;
        $idp = NULL;
        
        try
        {
            $em->beginTransaction();
            
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$form));
            
            $todo = $smt->fetch();
            
            
            $id     = $todo['id'];
            $tipo   = $todo['tipo'];
            $familia= $todo['familia'];
            $telefono=$todo['telefono'];
            $activo = $todo['activo'];
            $caso   = $todo['caso'];
            $ubi_id = $todo['ubi_id'];
            $red    = $todo['id_red'];
            $direccion=$todo['direccion'];
            $referencia=$todo['referencia'];
            $latitud= $todo['latitud'];
            $longitud=$todo['longitud'];
            $id_ubigeo=$todo['id_ubigeo'];
            $distrito = $todo['coddistrito'];
            $departamento = $todo['coddepartamento'];
            $provincia = $todo['codprovincia'];
            $idp = $todo['idp'];
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw  $e;
        }
        
       return $this->render('AEEnviarBundle:Default:modificarcelula.html.twig',array('id'=>$id,
            'tipo'=>$tipo, 'familia'=>$familia,'telefono'=>$telefono,'activo'=>$activo,'caso'=>$caso,
            'ubi_id'=>$ubi_id, 'direccion'=>$direccion, 'referencia'=>$referencia,'latitud'=>$latitud,
            'longitud'=>$longitud,'ubigeo'=>$id_ubigeo,'red'=>$red, 'distrito'=>$distrito,
           'provincia'=>$provincia,'departamento'=>$departamento,'celula'=>$form, 'idp'=>$idp));
        
    }
    
    public function modificarCelulaUpdateAction()
    {
                $request = $this->get('request');
        $name=$request->request->get('formName');
        
        $datos = array();

        parse_str($name,$datos);
        if($name!=NULL){
            $familia = $datos['inputFam'];
            $direccion = $datos['inputDireccion'];
            $referencia = $datos['inputReferencia'];
            $telefono = $datos['inputTel'];
            $tipocell = $datos['tipo_cell'];
            //$id_iglesia = $datos['iglesia_lista'];
            $id_red = $datos['red_lista'];
            $departamento = $datos['departamento_lista'];
            $provincia = $datos['provincia_lista'];
            $distrito = $datos['distrito_lista'];
            $latitud = $datos['latitud'];
            $longitud = $datos['longitud'];
            $celula = $datos['celula'];
            
            $tipo = $datos['tip_red'];
            $id   = $datos['ids'];
            $ubicacion = $datos['idubicacion'];
            
            $mision = NULL;
            $pastor = NULL;
            $lider  = NULL;
            $retorna = NULL;
            
            $em = $this->getDoctrine()->getEntityManager();
            
            
            try{
                $em->beginTransaction();
                
                $sql = "select update_cell(:tip, :fam, :tel,:red, :mision, :pastor, :lider, :idx, :dir,:refer,:lati,:longitu, :ubigeo, :idubi)";
                $smt = $em->getConnection()->prepare($sql);
                
                switch (intval($tipo)) {
                    case 0:

                        $lider = $id;
                        break;

                    case 1:
                        $pastor=$id;
                        break;
                    
                    case 2:
                        $mision=$id;
                        break;
                    default:
                        break;
                }
                
                $retorna = array(':tip'=>$tipocell,':fam'=>$familia, ':tel'=>$telefono,':red'=>$id_red,':mision'=>$mision,':pastor'=>$pastor,
                    ':lider'=>$lider,':idx'=>$celula, ':dir'=>$direccion, ':refer'=>$referencia,':lati'=>$latitud,':longitu'=>$longitud,
                    ':ubigeo'=>$distrito,':idubi'=>$ubicacion);
                
                $smt->execute($retorna);
                
                $em->commit();                
            }catch(Exception $e)
            {
                $em->rollback();
                $em->close();
                
                throw $e;
            }
 
            $return=array("responseCode"=>200, "greeting"=>'ok');  
        }
         else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type     
    }
    
   
}