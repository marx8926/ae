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

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEEnviarBundle:Default:index.html.twig', array('name' => $name));
    }
    public function celulaAction()
    {
        return $this->render('AEEnviarBundle:Default:regcelula.html.twig');

    }
    public function addcelulaAction()
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
            
            $tipo = $datos['tip_red'];
            $id   = $datos['ids'];
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->beginTransaction();
            
            try {
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
                
                $sql = "select insert_celula(:tip, :fam, :tel , :ubi, :red,:caso,:idx)";
                
                $smt = $em->getConnection()->prepare($sql);
                
                switch (intval($tipo)) {
                    case 0: //lider
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>2,':idx'=>$id));

                        break;
                    case 1: //misionero
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>1,':idx'=>$id));
                        break;
                    
                    case 2:
                      //pastor ejecutivo
                        $smt->execute(array(':tip'=>$tipocell,':fam'=>$familia,':tel'=>$telefono,
                                ':ubi'=>$ubicacion->getId(),':red'=>$id_red,':caso'=>0,':idx'=>$id));                        
                        break;

                    default:
                        break;
                }
                
              
                
                $em->commit();
                
            } catch (Exception $exc) {
                $em->rollback();
                $em->close();
                throw $exc;
            }



            $return=array("responseCode"=>200, "greeting"=>$datos);  
        }
         else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }
    
    public function temacelulaAction()
    {
        return $this->render('AEEnviarBundle:Default:tema_celula.html.twig');
    }
    
    public function temacelula_updatedAction()
    {
        $request = $this->get('request');
        
        $name=$request->request->get('formName');

        $datos = array();

        parse_str($name,$datos);
        
        $titulo = NULL;
        $author = NULL;
        $descripcion = NULL;
        $file = NULL;
        $tipo = NULL;

       if($name!=NULL){
                   
            $titulo = $datos['titulo'];
            $author = $datos['author'];
            $descripcion = $datos['descripcion'];
            $file = $datos['filename0'];
            $tipo = $datos['tipo'];
       
            $em = $this->getDoctrine()->getEntityManager();         
      
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                $celula = new TemaCelula();
                $celula->setAutor($author);
                $celula->setDescripcion($descripcion);
                $celula->setTitutlo($titulo);
                $celula->setTipo($tipo);
                $celula->setFecha(new \DateTime());
                
                $em->persist($celula);
                $em->flush();
                
		$uploadFileName = date("Y-m-d-H-i-s-").$file;
		$url = "uploads/".$uploadFileName;
                       
                $archivo = new Archivo();
                $archivo->setNombre($uploadFileName);
                $archivo->setFecha(new \DateTime());
                $archivo->setDireccion($url);
                $archivo->setIdTemaCelula($celula);
                
                $em->persist($archivo);
                $em->flush();
                
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
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
    
    }

    public function lista_clasesAction()
    {
        return $this->render('AEEnviarBundle:Default:lista_clases.html.twig');
    }
    public function lista_clases_descargaAction()
    {
       $request = $this->get('request');
        
       $val=$request->request->get('formName');
    
       if($val!=NULL){
                   
            $id = $val[0];
             $em = $this->getDoctrine()->getEntityManager();
      
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            {
                
                $sql = 'select * from  ruta_celula(:ruta) AS ("direccion" TEXT)';
                $smt = $em->getConnection()->prepare($sql);
                
                if(!$smt->execute(array(':ruta'=>$id)))
                {
                  $this->getDoctrine()->getEntityManager()->rollback();
                  $this->getDoctrine()->getEntityManager()->close();
                  $return=array("responseCode"=>400, "greeting"=>"Bad");  
                  
                }
                else
                {                   
                    $dato = $smt->fetch();
                    $this->getDoctrine()->getEntityManager()->commit();
  
                    $return=array("responseCode"=>200,  "greeting"=>$dato['direccion']);
                }
          
            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");

                     
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>"Bad");     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
 
    }

    public function crear_class_cellAction()
    {
        return $this->render('AEEnviarBundle:Default:crearclase_celula.html.twig');
    }
  
    public function crear_class_cell_updateAction()
    {
        $request = $this->get('request');
        
        $form=$request->request->get('formName');
        $fila = $request->request->get('data');
        
        $datos = array();

        parse_str($form,$datos);
       
        /*
        $return=array("responseCode"=>200, "greeting"=>$fila);     
       
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
        */
        
       if($form!=NULL){
                   
            $id = $fila[0];
            
            $dia = $datos['dia'];
            $de = $datos['de'];
            $hasta = $datos['hasta'];
            $red = $datos['redes'];
            
            $rest = NULL;
            
            $em = $this->getDoctrine()->getEntityManager();
      
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            try
            { 
                
                $horario = new Horario();
                $horario->setDia($dia);
                
                $dat1 = new \DateTime();
                $dat1->setTime(intval($de),0,0);
                
                $horario->setHoraInicio($dat1);
                
                $dat2 = new \DateTime();
                $dat2->setTime(intval($hasta),0, 0);
                
                $horario->setHoraFin($dat2);
                
                $em->persist($horario);
                $em->flush();
                
                $tipo = $fila[4];
                
               
                if(strcmp($tipo, "Evangelistica")==0)
                {
                    $sql = "select *from celula_evangelistica";
                    $smt = $em->getConnection()->prepare($sql);
                    
                    $smt->execute();
                        
                    if(strcmp($red,'ALL')==0)
                    {
                        
                        $cell_evang = $smt->fetchAll();
                        
                        $n = count($cell_evang);
                       
                       for($i=0; $i < $n; $i++)
                       {                          
                          $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce)";
                          
                          $smt = $em->getConnection()->prepare($sql);
                          
                          $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$cell_evang[$i]['id'],':tce'=>$id));
                          
                       }
   
                    }
                    else
                    {
                        
                        $sql1 = "select *from celula where id_red= :red and tipo=0";
                        
                        $smt1 = $em->getConnection()->prepare($sql1);
                        
                        $smt1->execute(array(':red'=>$red));
                        
                        $celula = $smt1->fetch();
                                            
                        $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce)";
                          
                        $smt = $em->getConnection()->prepare($sql);
                          
                        $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$celula['id'],':tce'=>$id));
                          
                    }
                     
                }
                else
                {
                    $sql = "select *from celula_discipulado";
                    $smt = $em->getConnection()->prepare($sql);
                    
                    $smt->execute();
                        
                    if(strcmp($red,'ALL')==0)
                    {
                        
                        $cell_evang = $smt->fetchAll();
                        
                        $n = count($cell_evang);
                       
                       for($i=0; $i < $n; $i++)
                       {                          
                          $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce)";
                          
                          $smt = $em->getConnection()->prepare($sql);
                          
                          $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$cell_evang[$i]['id'],':tce'=>$id));
                          
                       }
                           
                    }
                    else
                    {
                        
                        $sql1 = "select *from celula where id_red= :red and tipo=1";
                        
                        $smt1 = $em->getConnection()->prepare($sql1);
                        
                        $smt1->execute(array(':red'=>$red));
                        
                        $celula = $smt1->fetch();
                                            
                        $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce)";
                          
                        $smt = $em->getConnection()->prepare($sql);
                          
                        $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$celula['id'],':tce'=>$id));
                          
                    }
                }
               $this->getDoctrine()->getEntityManager()->commit();
  
               $return=array("responseCode"=>200,  "greeting"=>'good');

            }catch(Exception $e)
            {
                     $this->getDoctrine()->getEntityManager()->rollback();
                     $this->getDoctrine()->getEntityManager()->close();
                     $return=array("responseCode"=>400, "greeting"=>"Bad");
    
               throw $e;
            }
        }
        else {
            $return=array("responseCode"=>400, "greeting"=>'good');     
        }
               
        $return=json_encode($return);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type             
            
    }
    public function asistencia_celulaAction()
    {
         $request = $this->get('request');
        
        $celula=$request->request->get('celulaid');
        $red = $request->request->get('data');
        $tipo = $request->request->get('tip_cell');
        $titulo = $request->request->get('titulocell');
        $dia = $request->request->get('dia_cell');
        $horario = $request->request->get('horario_cell');
        $dicto = $request->request->get('dictado_cell');
        $id = $request->request->get('asiste');
        $ofrenda = 0;
        $ofrendaT = NULL;
        
        $em = $this->getDoctrine()->getEntityManager();
        try {
            $em->beginTransaction();
            $sql = "select ofrenda from clase_cell where id=:idx";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$id));
            $ofrendaT = $smt->fetch();
            $em->commit();
            
        } catch (Exception $exc) {
            throw $exc;
        }





        return $this->render('AEEnviarBundle:Default:asistencia_celula.html.twig',array('id'=>$id, 'celula'=>$celula,
            'red'=>$red,'tipo'=>intval($tipo)==0?'Evangelistica':'Discipulado', 'titulo'=>$titulo,
            'dia'=>$dia.'-'.$horario, 'dicto'=>$dicto, 'ofrenda'=>($ofrendaT==NULL)?0:$ofrendaT['ofrenda']));
    }
    
    public function asistencia_celula_updateAction()
    {
        $request = $this->get('request');
        
        $form=$request->request->get('formName');
        
       
        
        $datos = array();

        parse_str($form,$datos);   
      
        
        
        $clase = $datos['claseid'];
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $ofrenda = $datos['ofrenda']; // ofrenda
            
        $n = $datos['numfilas'];
        
        try{
            $em->beginTransaction();  
            for($i=0; $i<$n ;$i++)
            {
            
                $var = "check".strval($i);
                if(strpos($form, $var)!==false)
                {
                   $id = $datos['miembro'.strval($i)];
                    
                    $sql = "select update_clase_cell_miembro(:miembro,:clase,:band)";
                        
                    $smt = $em->getConnection()->prepare($sql);
                    $smt->execute(array(':miembro'=>$id,':clase'=>$clase,':band'=>TRUE));      
                    
                   
                }
            }
                
            $sql= "select update_clase_cell(:id,:monto)";
                
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':id'=>$clase,':monto'=>$ofrenda));
              
            $em->commit();
            
            $ret=array("responseCode"=>200, "greeting"=>'ok'); 
         }             
            catch(Exception  $e)
            {
                $this->getDoctrine()->getEntityManager()->rollback();
                $this->getDoctrine()->getEntityManager()->close();
                $ret=array("responseCode"=>400, "greeting"=>"Bad");
                
                throw $e;
            } 
       
        $return=json_encode($ret);//jscon encode the array
        
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
        
    }

    public function asistenciaAction()
    {
        return $this->render('AEEnviarBundle:Default:asistencia.html.twig');
    }
    public function redAction()
    {
    	return $this->render('AEEnviarBundle:Default:red.html.twig');
    }
    public function celulasbyredAction()
    {
    	return $this->render('AEEnviarBundle:Default:celulasbyred.html.twig');
    }
    
    public function busqueda_celulaAction()
    {
        return $this->render('AEEnviarBundle:Default:busqueda_celula.html.twig');
    }
    
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
    
    public function eliminarCelulaAction()
    {
        $request    = $this->get('request');
        
        $celula     = $request->request->get('idcelula');
        
        $em = $this->getDoctrine()->getEntityManager();
        try
        {
            $em->beginTransaction();
            $sql = "select delete_celula(:idx)";
            $smt = $em->getConnection()->prepare($sql);
            $smt->execute(array(':idx'=>$celula));
            
            $em->commit();
        }
        catch(Exception $e)
        {
            $em->rollback();
            $em->close();
            
            throw $e;
        }
        return $this->render('AEEnviarBundle:Default:busqueda_celula.html.twig');
    }
    
   
}
