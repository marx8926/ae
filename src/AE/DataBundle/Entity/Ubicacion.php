<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ubicacion
 *
 * @ORM\Table(name="ubicacion")
 * @ORM\Entity
 */
class Ubicacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ubicacion_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=150, nullable=false)
     */
    private $direccion;

    /**
     * @var float
     *
     * @ORM\Column(name="latitud", type="float", nullable=false)
     */
    private $latitud;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud", type="float", nullable=false)
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="text", nullable=true)
     */
    private $referencia;

    /**
     * @var \Ubigeo
     *
     * @ORM\ManyToOne(targetEntity="Ubigeo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ubigeo", referencedColumnName="id")
     * })
     */
    private $idUbigeo;



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
     * Set direccion
     *
     * @param string $direccion
     * @return Ubicacion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set latitud
     *
     * @param float $latitud
     * @return Ubicacion
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    
        return $this;
    }

    /**
     * Get latitud
     *
     * @return float 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param float $longitud
     * @return Ubicacion
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    
        return $this;
    }

    /**
     * Get longitud
     *
     * @return float 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return Ubicacion
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    
        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set idUbigeo
     *
     * @param \AE\DataBundle\Entity\Ubigeo $idUbigeo
     * @return Ubicacion
     */
    public function setIdUbigeo(\AE\DataBundle\Entity\Ubigeo $idUbigeo = null)
    {
        $this->idUbigeo = $idUbigeo;
    
        return $this;
    }

    /**
     * Get idUbigeo
     *
     * @return \AE\DataBundle\Entity\Ubigeo 
     */
    public function getIdUbigeo()
    {
        return $this->idUbigeo;
    }
}