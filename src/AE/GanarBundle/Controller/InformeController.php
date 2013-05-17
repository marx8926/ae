<?php

namespace AE\GanarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InformeController extends Controller
{
	function InformeGanarFechasAction(){
		return $this->render('AEGanarBundle:Default:informeganarfechas.html.twig');
	}
        
        public function InformeSemanalAction()
        {
            return $this->render('AEGanarBundle:Default:informesemanal.html.twig');
        }
        
        public function InformeConvertidosAction()
        {
            return $this->render('AEGanarBundle:Default:informeconvertidos.html.twig');
        }

}
