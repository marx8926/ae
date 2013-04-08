<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matric
 *
 * @ORM\Table(name="matric")
 * @ORM\Entity
 */
class Matric
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="matric_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @var \Estudiante
     *
     * @ORM\ManyToOne(targetEntity="Estudiante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_persona_estudiante", referencedColumnName="id")
     * })
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Matric
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Matric
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
     * Set idPersonaEstudiante
     *
     * @param \AE\DataBundle\Entity\Estudiante $idPersonaEstudiante
     * @return Matric
     */
    public function setIdPersonaEstudiante(\AE\DataBundle\Entity\Estudiante $idPersonaEstudiante = null)
    {
        $this->idPersonaEstudiante = $idPersonaEstudiante;
    
        return $this;
    }

    /**
     * Get idPersonaEstudiante
     *
     * @return \AE\DataBundle\Entity\Estudiante 
     */
    public function getIdPersonaEstudiante()
    {
        return $this->idPersonaEstudiante;
    }

    /**
     * Set idCursoImpartido
     *
     * @param \AE\DataBundle\Entity\CursoImpartido $idCursoImpartido
     * @return Matric
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