<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descartado
 *
 * @ORM\Table(name="descartado")
 * @ORM\Entity
 */
class Descartado
{
    /**
     * @var string
     *
     * @ORM\Column(name="cometario", type="text", nullable=true)
     */
    private $cometario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_descarte", type="date", nullable=false)
     */
    private $fechaDescarte;

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
     * Set cometario
     *
     * @param string $cometario
     * @return Descartado
     */
    public function setCometario($cometario)
    {
        $this->cometario = $cometario;
    
        return $this;
    }

    /**
     * Get cometario
     *
     * @return string 
     */
    public function getCometario()
    {
        return $this->cometario;
    }

    /**
     * Set fechaDescarte
     *
     * @param \DateTime $fechaDescarte
     * @return Descartado
     */
    public function setFechaDescarte($fechaDescarte)
    {
        $this->fechaDescarte = $fechaDescarte;
    
        return $this;
    }

    /**
     * Get fechaDescarte
     *
     * @return \DateTime 
     */
    public function getFechaDescarte()
    {
        return $this->fechaDescarte;
    }

    /**
     * Set id
     *
     * @param \AE\DataBundle\Entity\Persona $id
     * @return Descartado
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