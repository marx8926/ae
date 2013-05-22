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

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ClaseController extends Controller
{
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
        //cambiar a procedimientos almacenados        
        $request = $this->get('request');
        
        $form=$request->request->get('formName');
        $fila = $request->request->get('data');
        
        $datos = array();

        $vat = NULL;
        
        parse_str($form,$datos);
                       
       if($form!=NULL){
                   
            $id = $fila[0];
            
            $dia = $datos['dia'];
            $de = $datos['de'];
            $hasta = $datos['hasta'];
            $red = $datos['redes'];
            
            $rest = NULL;
            
            $em = $this->getDoctrine()->getEntityManager();
      
            $em->beginTransaction();
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
                        $em->clear();
                        
                        $n = count($cell_evang);
                       
                       for($i=0; $i < $n; $i++)
                       {                          
                          $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce) returning id";
                          
                          $smt = $em->getConnection()->prepare($sql);
                          
                          $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$cell_evang[$i]['id'],':tce'=>$id));
                          
                         $vat = $smt->fetch();
                         $em->clear();
                         
                         $sql = "select insert_clase_cell_celulas(:celula,:clase)";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         $smt->execute(array(':celula'=>$cell_evang[$i]['id'],':clase'=>$vat['id']));                         
                     
                       }
   
                    }
                    else
                    {
                        
                        $sql1 = "select *from celula where id_red= :red and tipo=0";
                        
                        $smt1 = $em->getConnection()->prepare($sql1);
                        
                        $smt1->execute(array(':red'=>$red));
                        
                        $celula = $smt1->fetch();
                        $em->clear();
                                            
                        $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce) returning id";
                          
                        $smt = $em->getConnection()->prepare($sql);
                          
                        $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$celula['id'],':tce'=>$id));
                         
                        $vat = $smt->fetch();
                        $em->clear();
                        
                        $sql = "select insert_clase_cell_celulas(:celula,:clase)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':celula'=>$celula['id'],':clase'=>$vat['id']));
                        
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
                        $em->clear();
                        
                        $n = count($cell_evang);
                       
                       for($i=0; $i < $n; $i++)
                       {                          
                          $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce) returning id";
                          
                          $smt = $em->getConnection()->prepare($sql);
                          
                          $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$cell_evang[$i]['id'],':tce'=>$id));
                          
                           $vat = $smt->fetch();
                          $em->clear();
                          
                          $sql = "select insert_clase_cell_celulas(:celula,:clase)";
                          $smt = $em->getConnection()->prepare($sql);
                          $smt->execute(array(':celula'=>$cell_evang[$i]['id'],':clase'=>$vat['id']));

                       }
                           
                    }
                    else
                    {
                        
                        $sql1 = "select *from celula where id_red= :red and tipo=1";
                        
                        $smt1 = $em->getConnection()->prepare($sql1);
                        
                        $smt1->execute(array(':red'=>$red));
                        
                        $celula = $smt1->fetch();
                                            
                        $sql = "INSERT INTO clase_cell(ofrenda, fecha_dicto,id_horario,id_celula, id_tema_celula) VALUES (:ofr, :fd, :ih, :cell, :tce) returning id";
                          
                        $smt = $em->getConnection()->prepare($sql);
                          
                        $smt->execute(array(':ofr'=>0,':fd'=>NULL,':ih'=>$horario->getId(), ':cell'=>$celula['id'],':tce'=>$id));
                          
                         
                        $vat = $smt->fetch();
                        $sql = "select insert_clase_cell_celulas(:celula,:clase)";
                        $smt = $em->getConnection()->prepare($sql);
                        $smt->execute(array(':celula'=>$celula['id'],':clase'=>$vat['id']));
                    }
                }
               $em->commit();
  
               $return=array("responseCode"=>200,  "greeting"=>'good');

            }catch(Exception $e)
            {
                     $em->rollback();
                     $em->close();
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
    
}


