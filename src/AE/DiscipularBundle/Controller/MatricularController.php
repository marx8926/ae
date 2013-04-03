<?php
namespace AE\DiscipularBundle\Controller;

use Doctrine\Tests\Models\DirectoryTree\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\TransactionRequiredException;
use AE\DataBundle\Entity\Docente;
use AE\DataBundle\Entity\Persona;

class MatricularController extends Controller {
	public function indexAction()
	{
		$request = $this->get('request');
		$idCurso = $request->request->get('datos');
				
		return $this->render('AEDiscipularBundle:Default:matricular.html.twig',array(
            'idCurso' => $idCurso
        ));
	}

}
