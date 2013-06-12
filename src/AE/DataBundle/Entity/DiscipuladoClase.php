<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscipuladoClase
 *
 * @ORM\Table(name="discipulado_clase")
 * @ORM\Entity
 */
class DiscipuladoClase
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="discipulado_clase_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_clase_cell", type="bigint", nullable=false)
     */
    private $idClaseCell;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_dicto", type="date", nullable=true)
     */
    private $fechaDicto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asistio", type="boolean", nullable=true)
     */
    private $asistio;

    /**
     * @var \Discipulado
     *
     * @ORM\ManyToOne(targetEntity="Discipulado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_discipulado", referencedColumnName="id")
     * })
     */
    private $idDiscipulado;



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
     * Set idClaseCell
     *
     * @param integer $idClaseCell
     * @return DiscipuladoClase
     */
    public function setIdClaseCell($idClaseCell)
    {
        $this->idClaseCell = $idClaseCell;
    
        return $this;
    }

    /**
     * Get idClaseCell
     *
     * @return integer 
     */
    public function getIdClaseCell()
    {
        return $this->idClaseCell;
    }

    /**
     * Set fechaDicto
     *
     * @param \DateTime $fechaDicto
     * @return DiscipuladoClase
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
     * Set asistio
     *
     * @param boolean $asistio
     * @return DiscipuladoClase
     */
    public function setAsistio($asistio)
    {
        $this->asistio = $asistio;
    
        return $this;
    }

    /**
     * Get asistio
     *
     * @return boolean 
     */
    public function getAsistio()
    {
        return $this->asistio;
    }

    /**
     * Set idDiscipulado
     *
     * @param \AE\DataBundle\Entity\Discipulado $idDiscipulado
     * @return DiscipuladoClase
     */
    public function setIdDiscipulado(\AE\DataBundle\Entity\Discipulado $idDiscipulado = null)
    {
        $this->idDiscipulado = $idDiscipulado;
    
        return $this;
    }

    /**
     * Get idDiscipulado
     *
     * @return \AE\DataBundle\Entity\Discipulado 
     */
    public function getIdDiscipulado()
    {
        return $this->idDiscipulado;
    }
}