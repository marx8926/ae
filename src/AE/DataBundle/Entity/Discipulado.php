<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discipulado
 *
 * @ORM\Table(name="discipulado")
 * @ORM\Entity
 */
class Discipulado
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
     * @var integer
     *
     * @ORM\Column(name="id_celula", type="bigint", nullable=true)
     */
    private $idCelula;

    /**
     * @var string
     *
     * @ORM\Column(name="id_red", type="string", length=10, nullable=true)
     */
    private $idRed;

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
     * Set aptoConsolidar
     *
     * @param boolean $aptoConsolidar
     * @return Discipulado
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
     * @return Discipulado
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
     * @return Discipulado
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
     * Set idCelula
     *
     * @param integer $idCelula
     * @return Discipulado
     */
    public function setIdCelula($idCelula)
    {
        $this->idCelula = $idCelula;
    
        return $this;
    }

    /**
     * Get idCelula
     *
     * @return integer 
     */
    public function getIdCelula()
    {
        return $this->idCelula;
    }

    /**
     * Set idRed
     *
     * @param string $idRed
     * @return Discipulado
     */
    public function setIdRed($idRed)
    {
        $this->idRed = $idRed;
    
        return $this;
    }

    /**
     * Get idRed
     *
     * @return string 
     */
    public function getIdRed()
    {
        return $this->idRed;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Discipulado
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