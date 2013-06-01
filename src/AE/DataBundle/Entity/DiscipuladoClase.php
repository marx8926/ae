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
     * @var integer
     *
     * @ORM\Column(name="id_miembro", type="bigint", nullable=false)
     */
    private $idMiembro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

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
     * @var \DateTime
     *
     * @ORM\Column(name="dictado", type="date", nullable=true)
     */
    private $dictado;

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
     * @var \ClaseCell
     *
     * @ORM\ManyToOne(targetEntity="ClaseCell")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_clase_celula", referencedColumnName="id")
     * })
     */
    private $idClaseCelula;



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
     * Set idMiembro
     *
     * @param integer $idMiembro
     * @return DiscipuladoClase
     */
    public function setIdMiembro($idMiembro)
    {
        $this->idMiembro = $idMiembro;
    
        return $this;
    }

    /**
     * Get idMiembro
     *
     * @return integer 
     */
    public function getIdMiembro()
    {
        return $this->idMiembro;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return DiscipuladoClase
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
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
     * Set dictado
     *
     * @param \DateTime $dictado
     * @return DiscipuladoClase
     */
    public function setDictado($dictado)
    {
        $this->dictado = $dictado;
    
        return $this;
    }

    /**
     * Get dictado
     *
     * @return \DateTime 
     */
    public function getDictado()
    {
        return $this->dictado;
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

    /**
     * Set idClaseCelula
     *
     * @param \AE\DataBundle\Entity\ClaseCell $idClaseCelula
     * @return DiscipuladoClase
     */
    public function setIdClaseCelula(\AE\DataBundle\Entity\ClaseCell $idClaseCelula = null)
    {
        $this->idClaseCelula = $idClaseCelula;
    
        return $this;
    }

    /**
     * Get idClaseCelula
     *
     * @return \AE\DataBundle\Entity\ClaseCell 
     */
    public function getIdClaseCelula()
    {
        return $this->idClaseCelula;
    }
}