<?php

namespace AE\ServiciosBundle\Controller;

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

class EnviarServicioController extends Controller
{
    public function get_lista_redAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $sql = "select * from lista_redes";
        $smt = $em->getConnection()->prepare($sql);
        $smt->execute();
        
        $todo = $smt->fetchAll();
        
        $n = count($todo);
        
        $cadena = "";
       
        for($i=0; $i<$n; $i++)
        {
           $temp = "<option value='". $todo[$i]['id']."' >";
           
           $temp = $temp." ".$todo[$i]['id']."-";
           $temp = $temp." ".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";

           $cadena = $cadena.$temp;
        }
        
        return new Response('<select>'.$cadena.'</select>');
        
    }
    
    public function get_lista_celulaAction()
    {
        
        $request = $this->get('request');
        $name=$request->request->get('formName');
        $red = $request->request->get('red');
        $tipo = $request->request->get('celula');
        
        $datos = array();

        parse_str($name,$datos);

       if($name!=NULL){
           
            $em = $this->getDoctrine()->getEntityManager();
       }
        
       
    }
    public function   get_lider_red_cellAction()
    {
       $request = $this->get('request');
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       
       try
       {
          $sql = "select *from  ver_lideres_red_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option id='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($total);
    }
  
    public function get_lider_misionero_cellAction()
    {
            $request = $this->get('request');
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       $em->beginTransaction();
       try
       {
          $sql = "select *from  ver_misionero_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option id='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
          $em->commit();
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           
           throw  $e;
       }
       
       return new Response($total);
    }
    
    public function get_lider_pastor_eje_cellAction()
    {
            $request = $this->get('request');
       $temp = $request->request->get('dato');
       
       $em = $this->getDoctrine()->getEntityManager();
       
       $em->beginTransaction();
       
       try
       {
          $sql = "select *from  ver_pastor_eje_to_celulas(:idx)";
          $smt = $em->getConnection()->prepare($sql);
          $smt->execute(array(':idx'=>$temp));
          
          $todo = $smt->fetchAll();
          $total = "";
          
          $n = count($todo);
          for($i=0; $i<$n; $i++)
          {
              $linea = "<option id='".$todo[$i]['id']."'>";
                $linea = $linea.$todo[$i]['id']."-".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</option>";
                $total = $total.$linea;
          }
       }
       catch(Exception $e)
       {
           $em->rollback();
           $em->close();
           throw  $e;
       }
       
       return new Response($total);
    }
}