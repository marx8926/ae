<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaseCurso
 *
 * @ORM\Table(name="clase_curso")
 * @ORM\Entity
 */
class ClaseCurso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="clase_curso_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_dicto", type="date", nullable=false)
     */
    private $fechaDicto;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Estudiante", mappedBy="idClaseCurso")
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
     * Set fechaDicto
     *
     * @param \DateTime $fechaDicto
     * @return ClaseCurso
     */
    public function setFechaDicto($fechaDicto)
    {
        $this->fechaDicto = $fechaDicto;
    
        return $this;
    }

    /**
     * Get fechaDicto
     *
     * @return \DateTime 
     */
    public function getFechaDicto()
    {
        return $this->fechaDicto;
    }

    /**
     * Add idPersonaEstudiante
     *
     * @param \AE\DataBundle\Entity\Estudiante $idPersonaEstudiante
     * @return ClaseCurso
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
     * @return ClaseCurso
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