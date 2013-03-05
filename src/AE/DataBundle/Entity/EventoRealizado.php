<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoRealizado
 *
 * @ORM\Table(name="evento_realizado")
 * @ORM\Entity
 */
class EventoRealizado
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="evento_realizado_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=false)
     */
    private $fechaFin;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_persona", referencedColumnName="id")
     * })
     */
    private $idPersona;

    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * })
     */
    private $idEvento;

    /**
     * @var \Ubicacion
     *
     * @ORM\ManyToOne(targetEntity="Ubicacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ubicacion", referencedColumnName="id")
     * })
     */
    private $idUbicacion;



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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return EventoRealizado
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return EventoRealizado
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
     * Set idPersona
     *
     * @param \AE\DataBundle\Entity\Persona $idPersona
     * @return EventoRealizado
     */
    public function setIdPersona(\AE\DataBundle\Entity\Persona $idPersona = null)
    {
        $this->idPersona = $idPersona;
    
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return \AE\DataBundle\Entity\Persona 
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set idEvento
     *
     * @param \AE\DataBundle\Entity\Evento $idEvento
     * @return EventoRealizado
     */
    public function setIdEvento(\AE\DataBundle\Entity\Evento $idEvento = null)
    {
        $this->idEvento = $idEvento;
    
        return $this;
    }

    /**
     * Get idEvento
     *
     * @return \AE\DataBundle\Entity\Evento 
     */
    public function getIdEvento()
    {
        return $this->idEvento;
    }

    /**
     * Set idUbicacion
     *
     * @param \AE\DataBundle\Entity\Ubicacion $idUbicacion
     * @return EventoRealizado
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