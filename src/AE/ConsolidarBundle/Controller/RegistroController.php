<?php

namespace AE\ConsolidarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AE\DataBundle\Entity\Consolidador;


class RegistroController extends Controller
{
    //registrar asignacion red
    
    public function registroAction()
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
                
               
              if($securityContext->isGranted('ROLE_CONSOLIDAR'))
                    $red = NULL;
              
              return $this->render('AEConsolidarBundle:Default:registro.html.twig',array('red'=>$red));
         }
         else return $this->render('AEGanarBundle:Default:sinacceso.html.twig');
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

