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
	
	public function RegistroPersonalAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$this->getDoctrine()->getEntityManager()->beginTransaction();
		try {
			//miembro
			$sql = "UPDATE miembro SET  apto_consolidar= :apto WHERE id= :codigo";
	
			$smt = $em->getConnection()->prepare($sql);
			if(!$smt->execute(array(':apto'=>TRUE, ':codigo'=>$id)))
			{
				$return=array("responseCode"=>400,  "greeting"=>'Bad');
			}
	
			//chequear si es consolidador
			$sql = "select *from consolidador where id=:codigo";
			$all = array();
			$smt = $em->getConnection()->prepare($sql);
			if(!$smt->execute(array(':codigo'=>$id)))
			{
				$return=array("responseCode"=>400,  "greeting"=>'Bad');
			}
			else $all = $smt->fetchAll();
	
			if(count($all)>0)
			{
				$sql = "UPDATE consolidador SET  activo= true WHERE id= :codigo";
				$smt = $em->getConnection()->prepare($sql);
				 
				if(!$smt->execute(array(':codigo'=>$id)))
				{
					$return=array("responseCode"=>400,  "greeting"=>'Bad');
				}
				 
			}
			else
			{
				$pers = $em->getRepository('AEDataBundle:Persona');
				$persona = $pers->findOneBy(array('id'=>$id));
				 
				$docente = new Docente();
				$docente->setActivo(true);
				$docente->setFechaInicio(new \DateTime());
				 
				$em->persist($docente);
				$em->flush();
				 
			}
	
			 
			$this->getDoctrine()->getEntityManager()->commit();
		} catch (Exception $exc) {
			$this->getDoctrine()->getEntityManager()->rollback();
			$this->getDoctrine()->getEntityManager()->close();
	
			throw $exc;
	
		}
		 
		return $this->render('AEConsolidarBundle:Default:registro.html.twig');
	
	}
}
