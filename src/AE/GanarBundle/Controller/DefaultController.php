<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AEGanarBundle:Default:index.html.twig', array('name' => $name));
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

            $em->beginTransaction();
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
            
        }
        else $return=array("responseCode"=>400, "greeting"=>"Bad");     

        $return=json_encode($return);//jscon encode the array
     
        return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type       
      
    }

    public function testAction()
    {
        return $this->render('AEGanarBundle:Default:test.html.twig');
    }
    

}
