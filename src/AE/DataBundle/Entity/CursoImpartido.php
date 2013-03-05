<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoImpartido
 *
 * @ORM\Table(name="curso_impartido")
 * @ORM\Entity
 */
class CursoImpartido
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="curso_impartido_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var \Curso
     *
     * @ORM\ManyToOne(targetEntity="Curso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_curso", referencedColumnName="id")
     * })
     */
    private $idCurso;

    /**
     * @var \Docente
     *
     * @ORM\ManyToOne(targetEntity="Docente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_persona_docente", referencedColumnName="id_persona")
     * })
     */
    private $idPersonaDocente;

    /**
     * @var \Local
     *
     * @ORM\ManyToOne(targetEntity="Local")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_local", referencedColumnName="id")
     * })
     */
    private $idLocal;

    /**
     * @var \Horario
     *
     * @ORM\ManyToOne(targetEntity="Horario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_horario", referencedColumnName="id")
     * })
     */
    private $idHorario;



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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return CursoImpartido
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set idCurso
     *
     * @param \AE\DataBundle\Entity\Curso $idCurso
     * @return CursoImpartido
     */
    public function setIdCurso(\AE\DataBundle\Entity\Curso $idCurso = null)
    {
        $this->idCurso = $idCurso;
    
        return $this;
    }

    /**
     * Get idCurso
     *
     * @return \AE\DataBundle\Entity\Curso 
     */
    public function getIdCurso()
    {
        return $this->idCurso;
    }

    /**
     * Set idPersonaDocente
     *
     * @param \AE\DataBundle\Entity\Docente $idPersonaDocente
     * @return CursoImpartido
     */
    public function setIdPersonaDocente(\AE\DataBundle\Entity\Docente $idPersonaDocente = null)
    {
        $this->idPersonaDocente = $idPersonaDocente;
    
        return $this;
    }

    /**
     * Get idPersonaDocente
     *
     * @return \AE\DataBundle\Entity\Docente 
     */
    public function getIdPersonaDocente()
    {
        return $this->idPersonaDocente;
    }

    /**
     * Set idLocal
     *
     * @param \AE\DataBundle\Entity\Local $idLocal
     * @return CursoImpartido
     */
    public function setIdLocal(\AE\DataBundle\Entity\Local $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \AE\DataBundle\Entity\Local 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idHorario
     *
     * @param \AE\DataBundle\Entity\Horario $idHorario
     * @return CursoImpartido
     */
    public function setIdHorario(\AE\DataBundle\Entity\Horario $idHorario = null)
    {
        $this->idHorario = $idHorario;
    
        return $this;
    }

    /**
     * Get idHorario
     *
     * @return \AE\DataBundle\Entity\Horario 
     */
    public function getIdHorario()
    {
        return $this->idHorario;
    }
}