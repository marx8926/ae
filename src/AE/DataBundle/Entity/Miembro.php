<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Miembro
 *
 * @ORM\Table(name="miembro")
 * @ORM\Entity
 */
class Miembro
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="apto_consolidar", type="boolean", nullable=false)
     */
    private $aptoConsolidar;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ClaseCell", mappedBy="idMiembro")
     */
    private $idClaseCell;

    /**
     * @var \Persona
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Persona",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @var \Celula
     *
     * @ORM\ManyToOne(targetEntity="Celula",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_celula", referencedColumnName="id")
     * })
     */
    private $idCelula;

    /**
     * @var \Red
     *
     * @ORM\ManyToOne(targetEntity="Red",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_red", referencedColumnName="id")
     * })
     */
    private $idRed;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idClaseCell = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Set aptoConsolidar
     *
     * @param boolean $aptoConsolidar
     * @return Miembro
     */
    public function setAptoConsolidar($aptoConsolidar)
    {
        $this->aptoConsolidar = $aptoConsolidar;
    
        return $this;
    }

    /**
     * Get aptoConsolidar
     *
     * @return boolean 
     */
    public function getAptoConsolidar()
    {
        return $this->aptoConsolidar;
    }

    /**
     * Set fechaObtencion
     *
     * @param \DateTime $fechaObtencion
     * @return Miembro
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
     * @return Miembro
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
     * Add idClaseCell
     *
     * @param \AE\DataBundle\Entity\ClaseCell $idClaseCell
     * @return Miembro
     */
    public function addIdClaseCell(\AE\DataBundle\Entity\ClaseCell $idClaseCell)
    {
        $this->idClaseCell[] = $idClaseCell;
    
        return $this;
    }

    /**
     * Remove idClaseCell
     *
     * @param \AE\DataBundle\Entity\ClaseCell $idClaseCell
     */
    public function removeIdClaseCell(\AE\DataBundle\Entity\ClaseCell $idClaseCell)
    {
        $this->idClaseCell->removeElement($idClaseCell);
    }

    /**
     * Get idClaseCell
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdClaseCell()
    {
        return $this->idClaseCell;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Miembro
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

    /**
     * Set idCelula
     *
     * @param \AE\DataBundle\Entity\Celula $idCelula
     * @return Miembro
     */
    public function setIdCelula(\AE\DataBundle\Entity\Celula $idCelula = null)
    {
        $this->idCelula = $idCelula;
    
        return $this;
    }

    /**
     * Get idCelula
     *
     * @return \AE\DataBundle\Entity\Celula 
     */
    public function getIdCelula()
    {
        return $this->idCelula;
    }

    /**
     * Set idRed
     *
     * @param \AE\DataBundle\Entity\Red $idRed
     * @return Miembro
     */
    public function setIdRed(\AE\DataBundle\Entity\Red $idRed = null)
    {
        $this->idRed = $idRed;
    
        return $this;
    }

    /**
     * Get idRed
     *
     * @return \AE\DataBundle\Entity\Red 
     */
    public function getIdRed()
    {
        return $this->idRed;
    }
}