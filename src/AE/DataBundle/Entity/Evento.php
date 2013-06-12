<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity
 */
class Evento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="evento_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ini", type="date", nullable=true)
     */
    private $fechaIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var \Ubicacion
     *
     * @ORM\ManyToOne(targetEntity="Ubicacion",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ubicacion", referencedColumnName="id")
     * })
     */
    private $idUbicacion;
    
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */

    private $tipo;
    
    
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
     * Set tipo
     *
     * @param integer $tipo
     * @return Evento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Evento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Evento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fecha_ini
     *
     * @param \DateTime $fecha_ini
     * @return Evento
     */
    public function setfechaIni($fechaIni)
    {
        $this->fechaIni = $fechaIni;
    
        return $this;
    }

    /**
     * Get fechaini
     *
     * @return \DateTime 
     */
    public function getfechaIni()
    {
        return $this->fechaIni;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return Evento
     */
    public function setfechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime 
     */
    public function getfechaFin()
    {
        return $this->fechaFin;
    }
    
    /**
     * Set idUbicacion
     *
     * @param \AE\DataBundle\Entity\Ubicacion $idUbicacion
     * @return Evento
     */
    public function setIdUbicacion(\AE\DataBundle\Entity\Ubicacion $idUbicacion = null)
    {
    	$this->idUbicacion = $idUbicacion;
    
    	return $this;
    }
    
    /**
     * Get idUbicacion
     *
     * @return \AE\DataBundle\Entity\Ubicacion
     */
    public function getIdUbicacion()
    {
    	return $this->idUbicacion;
    }
}