<?php
namespace AE\DiscipularBundle\Controller;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;

class RegistrarPersonalController extends Controller {
	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:registrarpersonal.html.twig');
	}
	
	public function RegistroPersonalAction(){
		$request = $this->get('request');
		$form=$request->request->get('formName');
		$otherdata =$request->request->get('otherdata');
		$datos = array();
		
		parse_str($otherdata,$datos);
		$return=array("responseCode"=>300, "datos"=>$datos );
		$return=json_encode($return);
		return new Response($return,200,array('Content-Type'=>'application/json'));
	
	}
}
