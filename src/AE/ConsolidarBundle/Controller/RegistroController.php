<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AE\DataBundle\Entity\Consolidador;


class RegistroController extends Controller
{
    //registrar asignacion red
    
    public function registroAction()
    {
        return $this->render('AEConsolidarBundle:Default:registro.html.twig');
    }
    
      
    public function registroupdateAction($id)
    {
           $em = $this->getDoctrine()->getEntityManager();         

            $em->beginTransaction();
            try {
                //miembro
                /*
                    $sql = "select apto_miembro_consolidador(:codigo,:apto)";

                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':apto'=>TRUE, ':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         $em->rollback();
                         $em->close();
                    }
                  */  
                    //chequear si es consolidador
                    
                    $consolidador_q = $em->getRepository('AEDataBundle:Consolidador');
                    $consolidador_f = $consolidador_q->find(array('id'=>$id));
                    
                    /*$sql = "select *from consolidador where id=:codigo";
                    $all = array();
                    $smt = $em->getConnection()->prepare($sql);
                    if(!$smt->execute(array(':codigo'=>$id)))
                    {
                         $return=array("responseCode"=>400,  "greeting"=>'Bad');
                    }
                    else $all = $smt->fetchAll();
                     * 
                     */
                        
                    if(count($consolidador_f)>0)
                    {
                         $sql = "select activar_consolidador(:codigo, :act)";
                         $smt = $em->getConnection()->prepare($sql);
                         
                         if(!$smt->execute(array(':codigo'=>$id,':act'=>TRUE)))
                         {
                            $return=array("responseCode"=>400,  "greeting"=>'Bad');
                         }
                         
                    }
                    else
                    {
                         $pers = $em->getRepository('AEDataBundle:Persona');
                         $persona = $pers->findOneBy(array('id'=>$id));
                         
                         $consolidador = new Consolidador();
                         $consolidador->setActivo(TRUE);
                         $consolidador->setFechaObtencion(new \DateTime());
                         $consolidador->setId($persona);
                         
                         $em->persist($consolidador);
                         $em->flush();
                         
                    }
                        
                   
                    $em->commit();
                    $em->clear();
                    
            } catch (Exception $exc) {
                $em->rollback();
                $em->close();
                
                throw $exc;

            }
     
         return $this->render('AEConsolidarBundle:Default:registro.html.twig');

     }
}

