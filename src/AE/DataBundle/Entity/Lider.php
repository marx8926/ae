<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lider
 *
 * @ORM\Table(name="lider")
 * @ORM\Entity
 */
class Lider
{
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

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
     * Set fechaObtencion
     *
     * @param \DateTime $fechaObtencion
     * @return Lider
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
     * @return Lider
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
     * @return Lider
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
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Lider
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