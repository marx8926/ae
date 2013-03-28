<?php
namespace AE\DiscipularBundle\Controller;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class RegistrarEstudianteController extends Controller {
	public function indexAction()
	{
		return $this->render('AEDiscipularBundle:Default:convertirestudiante.html.twig');
	}
	
	public function RegistrarEstudianteAction(){
		
	}
}
