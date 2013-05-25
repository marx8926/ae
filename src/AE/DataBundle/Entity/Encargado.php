<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Encargado
 *
 * @ORM\Table(name="encargado")
 * @ORM\Entity
 */
class Encargado
{
    
     /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="persona_id_seq", allocationSize=1, initialValue=1)
     */
    protected $codigo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_obtencion", type="date", nullable=false)
     */
    private $fechaObtencion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;
    
      /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */
    private $tipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AreaVision", inversedBy="idEncargado")
     * @ORM\JoinTable(name="many_encargado_has_many_area_vision",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_encargado", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_area_vision", referencedColumnName="id")
     *   }
     * )
     */
    private $idAreaVision;

    /**
     * @var \Persona
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Persona" ,cascade={"persist", "merge", "remove"})
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
        $this->idAreaVision = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
     /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fechaObtencion
     *
     * @param \DateTime $fechaObtencion
     * @return Encargado
     */
    public function setFechaObtencion($fechaObtencion)
    {
        $this->fechaObtencion = $fechaObtencion;
    
        return $this;
    }

    /**
     * Get fechaObtencion
     *
     * @return \DateTime 
     */
    public function getFechaObtencion()
    {
        return $this->fechaObtencion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Encargado
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
     * Set tipo
     *
     * @param integer $tipo
     * @return Encargado
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Encargado
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
     * Add idAreaVision
     *
     * @param \AE\DataBundle\Entity\AreaVision $idAreaVision
     * @return Encargado
     */
    public function addIdAreaVision(\AE\DataBundle\Entity\AreaVision $idAreaVision)
    {
        $this->idAreaVision[] = $idAreaVision;
    
        return $this;
    }

    /**
     * Remove idAreaVision
     *
     * @param \AE\DataBundle\Entity\AreaVision $idAreaVision
     */
    public function removeIdAreaVision(\AE\DataBundle\Entity\AreaVision $idAreaVision)
    {
        $this->idAreaVision->removeElement($idAreaVision);
    }

    /**
     * Get idAreaVision
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdAreaVision()
    {
        return $this->idAreaVision;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Encargado
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