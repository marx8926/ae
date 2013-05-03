<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AE\DataBundle\Entity\Persona;
use AE\DataBundle\Entity\Miembro;


class ModificarController extends Controller
{
   public function modificarAction()
   {
         return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
   }
   
   
     public function modificar_activarAction($id)
     {
         
         $em = $this->getDoctrine()->getEntityManager();         

         $em->beginTransaction();
         try {
                 //miembro
                    $sql = "select apto_miembro_consolidador(:codigo, :apto)";
                    
                   // UPDATE miembro SET  apto_consolidar= true WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id,':apto'=>TRUE)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                  
                    //chequear si es consolidador
                    $consolidador_q = $em->getRepository('AEDataBundle:Consolidador');
                    $consolidador_f = $consolidador_q->find(array('id'=>$id));
   
                    if(count($consolidador_f)>0)
                    {
                         $sql = "select apto_consolidador(:codigo,:apto)";
                         //    UPDATE consolidador SET  activo=true WHERE id= :codigo";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id,':apto'=>TRUE)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }                           
                    }
 
              $em->commit();
              
            } catch (Exception $exc) {
                $em->rollback();
                $em->close();
                
                throw $exc;

            }
        return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
     }

     public function modificar_desactivarAction($id)
     {
           $em = $this->getDoctrine()->getEntityManager();         

         $em->beginTransaction();
         try {
                 //miembro
                    $sql = "UPDATE miembro SET  apto_consolidar= true WHERE id= :codigo";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                  
                    //chequear si es consolidador
                    $consolidador_q = $em->getRepository('AEDataBundle:Consolidador');
                    $consolidador_f = $consolidador_q->find(array('id'=>$id));
   
                    if(count($consolidador_f)>0)
                    {
                         $sql = "UPDATE consolidador SET  activo=false WHERE id= :codigo";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }                           
                    }
 
              $em->commit();
              
            } catch (Exception $exc) {
                $em->rollback();
                $em->close();
                
                throw $exc;

            }
        return $this->render('AEConsolidarBundle:Default:modificar.html.twig');
     }
     
     
}

