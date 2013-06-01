<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NuevoConvertido
 *
 * @ORM\Table(name="nuevo_convertido")
 * @ORM\Entity
 */
class NuevoConvertido
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_conversion", type="date", nullable=false)
     */
    private $fechaConversion;

    /**
     * @var string
     *
     * @ORM\Column(name="peticion", type="text", nullable=false)
     */
    private $peticion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="consolidado", type="boolean", nullable=true)
     */
    private $consolidado;

    /**
     * @var integer
     *
     * @ORM\Column(name="ganador", type="bigint", nullable=true)
     */
    private $ganador;

    /**
     * @var string
     *
     * @ORM\Column(name="dia", type="string", length=10, nullable=true)
     */
    private $dia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @var \Celula
     *
     * @ORM\ManyToOne(targetEntity="Celula")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_celula", referencedColumnName="id")
     * })
     */
    private $idCelula;

    /**
     * @var \Lugar
     *
     * @ORM\ManyToOne(targetEntity="Lugar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lugar", referencedColumnName="id")
     * })
     */
    private $idLugar;

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
     * @var \Red
     *
     * @ORM\ManyToOne(targetEntity="Red")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_red", referencedColumnName="id")
     * })
     */
    private $idRed;



    /**
     * Set fechaConversion
     *
     * @param \DateTime $fechaConversion
     * @return NuevoConvertido
     */
    public function setFechaConversion($fechaConversion)
    {
        $this->fechaConversion = $fechaConversion;
    
        return $this;
    }

    /**
     * Get fechaConversion
     *
     * @return \DateTime 
     */
    public function getFechaConversion()
    {
        return $this->fechaConversion;
    }

    /**
     * Set peticion
     *
     * @param string $peticion
     * @return NuevoConvertido
     */
    public function setPeticion($peticion)
    {
        $this->peticion = $peticion;
    
        return $this;
    }

    /**
     * Get peticion
     *
     * @return string 
     */
    public function getPeticion()
    {
        return $this->peticion;
    }

    /**
     * Set consolidado
     *
     * @param boolean $consolidado
     * @return NuevoConvertido
     */
    public function setConsolidado($consolidado)
    {
        $this->consolidado = $consolidado;
    
        return $this;
    }

    /**
     * Get consolidado
     *
     * @return boolean 
     */
    public function getConsolidado()
    {
        return $this->consolidado;
    }

    /**
     * Set ganador
     *
     * @param integer $ganador
     * @return NuevoConvertido
     */
    public function setGanador($ganador)
    {
        $this->ganador = $ganador;
    
        return $this;
    }

    /**
     * Get ganador
     *
     * @return integer 
     */
    public function getGanador()
    {
        return $this->ganador;
    }

    /**
     * Set dia
     *
     * @param string $dia
     * @return NuevoConvertido
     */
    public function setDia($dia)
    {
        $this->dia = $dia;
    
        return $this;
    }

    /**
     * Get dia
     *
     * @return string 
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     * @return NuevoConvertido
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    
        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set idCelula
     *
     * @param \AE\DataBundle\Entity\Celula $idCelula
     * @return NuevoConvertido
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
     * Set idLugar
     *
     * @param \AE\DataBundle\Entity\Lugar $idLugar
     * @return NuevoConvertido
     */
    public function setIdLugar(\AE\DataBundle\Entity\Lugar $idLugar = null)
    {
        $this->idLugar = $idLugar;
    
        return $this;
    }

    /**
     * Get idLugar
     *
     * @return \AE\DataBundle\Entity\Lugar 
     */
    public function getIdLugar()
    {
        return $this->idLugar;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return NuevoConvertido
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
     * Set idRed
     *
     * @param \AE\DataBundle\Entity\Red $idRed
     * @return NuevoConvertido
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