<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsistenciaClaseCurso
 *
 * @ORM\Table(name="asistencia_clase_curso")
 * @ORM\Entity
 */
class AsistenciaClaseCurso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="asistencia_clase_curso_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="nota", type="float", nullable=false)
     */
    private $nota;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asistencia", type="boolean", nullable=false)
     */
    private $asistencia;

    /**
     * @var \ClaseCurso
     *
     * @ORM\ManyToOne(targetEntity="ClaseCurso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_clase_curso", referencedColumnName="id")
     * })
     */
    private $idClaseCurso;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nota
     *
     * @param float $nota
     * @return AsistenciaClaseCurso
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return float 
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set asistencia
     *
     * @param boolean $asistencia
     * @return AsistenciaClaseCurso
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;
    
        return $this;
    }

    /**
     * Get asistencia
     *
     * @return boolean 
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * Set idClaseCurso
     *
     * @param \AE\DataBundle\Entity\ClaseCurso $idClaseCurso
     * @return AsistenciaClaseCurso
     */
    public function setIdClaseCurso(\AE\DataBundle\Entity\ClaseCurso $idClaseCurso = null)
    {
        $this->idClaseCurso = $idClaseCurso;
    
        return $this;
    }

    /**
     * Get idClaseCurso
     *
     * @return \AE\DataBundle\Entity\ClaseCurso 
     */
    public function getIdClaseCurso()
    {
        return $this->idClaseCurso;
    }

    /**
     * Set idPersonaEstudiante
     *
     * @param \AE\DataBundle\Entity\Estudiante $idPersonaEstudiante
     * @return AsistenciaClaseCurso
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
}