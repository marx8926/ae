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
            
            
            
            
            $em = $this->getDoctrine()->getEntityManager();         
            
            //   $em->persist($user);
            //$em->flush();
            
             
           
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
                
                
                //iglesia
               // $iglesias = $em->getRepository('AEDataBundle:Iglesia');
                //$iglesia = $iglesias->findOneBy(array('id'=>$id_iglesia));
                
                
                //Red
                $redes = $em->getRepository('AEDataBundle:Red');
                $red = $redes->findOneBy(array('id'=>$id_red));
                
                
                //celula
                $celula = new Celula();
                $celula->setFamilia($familia);
                $celula->setIdRed($red);
                $celula->setIdUbicacion($ubicacion);
                $celula->setTelefono($telefono);
                $celula->setTipo($tipocell);
                $celula->setActivo(TRUE);
                $celula->setFechaCreacion(new \DateTime());
                
                $em->persist($celula);
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
    public function asistencia_celulaAction($id)
    {
        return $this->render('AEEnviarBundle:Default:asistencia_celula.html.twig',array('id'=>$id));
    }
}
