<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CriterioEvaluacion
 *
 * @ORM\Table(name="criterio_evaluacion")
 * @ORM\Entity
 */
class CriterioEvaluacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="criterio_evaluacion_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Estudiante", mappedBy="idCriterioEvaluacion")
     */
    private $idPersonaEstudiante;

    /**
     * @var \CursoImpartido
     *
     * @ORM\ManyToOne(targetEntity="CursoImpartido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_curso_impartido", referencedColumnName="id")
     * })
     */
    private $idCursoImpartido;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idPersonaEstudiante = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add idPersonaEstudiante
     *
     * @param \AE\DataBundle\Entity\Estudiante $idPersonaEstudiante
     * @return CriterioEvaluacion
     */
    public function addIdPersonaEstudiante(\AE\DataBundle\Entity\Estudiante $idPersonaEstudiante)
    {
        $this->idPersonaEstudiante[] = $idPersonaEstudiante;
    
        return $this;
    }

    /**
     * Remove idPersonaEstudiante
     *
     * @param \AE\DataBundle\Entity\Estudiante $idPersonaEstudiante
     */
    public function removeIdPersonaEstudiante(\AE\DataBundle\Entity\Estudiante $idPersonaEstudiante)
    {
        $this->idPersonaEstudiante->removeElement($idPersonaEstudiante);
    }

    /**
     * Get idPersonaEstudiante
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdPersonaEstudiante()
    {
        return $this->idPersonaEstudiante;
    }

    /**
     * Set idCursoImpartido
     *
     * @param \AE\DataBundle\Entity\CursoImpartido $idCursoImpartido
     * @return CriterioEvaluacion
     */
    public function setIdCursoImpartido(\AE\DataBundle\Entity\CursoImpartido $idCursoImpartido = null)
    {
        $this->idCursoImpartido = $idCursoImpartido;
    
        return $this;
    }

    /**
     * Get idCursoImpartido
     *
     * @return \AE\DataBundle\Entity\CursoImpartido 
     */
    public function getIdCursoImpartido()
    {
        return $this->idCursoImpartido;
    }
}