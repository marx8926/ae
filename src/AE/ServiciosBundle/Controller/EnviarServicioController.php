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
           $temp = " <tr>";
           
           $temp = $temp." <td> ".$todo[$i]['id']."</td>";
           $temp = $temp." <td> ".$todo[$i]['nombre']." ".$todo[$i]['apellidos']."</td>";
           
           $tipo="";
           
           switch (intval($todo[$i]['tipo'])) {
               case 0:
                   $tipo = "Mixta";
                   break;
               case 1:
                   $tipo = "Hombres";
                   break;
               case 2:
                   $tipo = "Mujeres";
                   break;
               case 3:
                   $tipo = "Hombres Jóvenes";
                   break;
               case 4:
                   $tipo = "Mujeres Jóvenes";
                   break;
               default:
                   $tipo = "";
                   
                   break;
           }
           $temp = $temp." <td> ".$tipo."</td>";
           $temp = $temp."</tr> ";
           $cadena = $cadena.$temp;
        }
        
        return new Response($cadena);
        
    }
    
    public function get_lista_celulaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
       // $sql = 
    }
}