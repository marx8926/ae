<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estudiante
 *
 * @ORM\Table(name="estudiante")
 * @ORM\Entity
 */
class Estudiante
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CriterioEvaluacion", inversedBy="idPersonaEstudiante")
     * @ORM\JoinTable(name="evaluacion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_persona_estudiante", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_criterio_evaluacion", referencedColumnName="id")
     *   }
     * )
     */
    private $idCriterioEvaluacion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ClaseCurso", inversedBy="idPersonaEstudiante")
     * @ORM\JoinTable(name="asistencia_clase_curso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_persona_estudiante", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_clase_curso", referencedColumnName="id")
     *   }
     * )
     */
    private $idClaseCurso;

    /**
     * @var \Persona
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCriterioEvaluacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idClaseCurso = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Estudiante
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Estudiante
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Estudiante
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Add idCriterioEvaluacion
     *
     * @param \AE\DataBundle\Entity\CriterioEvaluacion $idCriterioEvaluacion
     * @return Estudiante
     */
    public function addIdCriterioEvaluacion(\AE\DataBundle\Entity\CriterioEvaluacion $idCriterioEvaluacion)
    {
        $this->idCriterioEvaluacion[] = $idCriterioEvaluacion;
    
        return $this;
    }

    /**
     * Remove idCriterioEvaluacion
     *
     * @param \AE\DataBundle\Entity\CriterioEvaluacion $idCriterioEvaluacion
     */
    public function removeIdCriterioEvaluacion(\AE\DataBundle\Entity\CriterioEvaluacion $idCriterioEvaluacion)
    {
        $this->idCriterioEvaluacion->removeElement($idCriterioEvaluacion);
    }

    /**
     * Get idCriterioEvaluacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdCriterioEvaluacion()
    {
        return $this->idCriterioEvaluacion;
    }

    /**
     * Add idClaseCurso
     *
     * @param \AE\DataBundle\Entity\ClaseCurso $idClaseCurso
     * @return Estudiante
     */
    public function addIdClaseCurso(\AE\DataBundle\Entity\ClaseCurso $idClaseCurso)
    {
        $this->idClaseCurso[] = $idClaseCurso;
    
        return $this;
    }

    /**
     * Remove idClaseCurso
     *
     * @param \AE\DataBundle\Entity\ClaseCurso $idClaseCurso
     */
    public function removeIdClaseCurso(\AE\DataBundle\Entity\ClaseCurso $idClaseCurso)
    {
        $this->idClaseCurso->removeElement($idClaseCurso);
    }

    /**
     * Get idClaseCurso
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdClaseCurso()
    {
        return $this->idClaseCurso;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Estudiante
     */
    public function setId(\AE\DataBundle\Entity\Persona $id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return \AE\DataBundle\Entity\Persona 
     */
    public function getId()
    {
        return $this->id;
    }
}