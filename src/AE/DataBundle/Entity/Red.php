<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Red
 *
 * @ORM\Table(name="red")
 * @ORM\Entity
 */
class Red
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string",length=10, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="smallint", nullable=false)
     */
    protected $tipo;

    /**
     * @var \Ubicacion
     *
     * @ORM\ManyToOne(targetEntity="Ubicacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ubicacion", referencedColumnName="id")
     * })
     */
    protected $idUbicacion;

    /**
     * @var \Iglesia
     *
     * @ORM\ManyToOne(targetEntity="Iglesia",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_iglesia", referencedColumnName="id")
     * })
     */
    protected $idIglesia;

    /**
     * @var \LiderRed
     *
     * @ORM\ManyToOne(targetEntity="LiderRed",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lider_red", referencedColumnName="id")
     * })
     */
    protected $idLiderRed;

    /**
     * @var \PastorAsociado
     *
     * @ORM\ManyToOne(targetEntity="PastorAsociado",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pastor_asociado", referencedColumnName="id")
     * })
     */
    protected $idPastorAsociado;


    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="date", nullable=true)
     */
    protected $inicio;
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    protected $activo;
    
    /**
     * @var \PastorEjecutivo
     *
     * @ORM\ManyToOne(targetEntity="PastorEjecutivo"),cascade={"persist", "merge", "remove"}
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pastor", referencedColumnName="id")
     * })
     */
    private $pastor;
    
    /**
     * Get inicio
     * @return boolean
     */
    public function getActivo()
    {
        return $this->getActivo();
    }
    /**
     * Set activo
     * @param boolean $activo
     * @return Red
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
        return $this;
    }

    /**
     * Get inicio
     * 
     * @return \DateTime
     */
    
    public function getInicio()
    {
        return $this->inicio;
    }
    
    /**
     * Set inicio
     * 
     * @param \DateTime $inicio
     * @return Red
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id
     * @param string id
     * @return Red
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Red
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

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
     * Set idUbicacion
     *
     * @param \AE\DataBundle\Entity\Ubicacion $idUbicacion
     * @return Red
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

    /**
     * Set idIglesia
     *
     * @param \AE\DataBundle\Entity\Iglesia $idIglesia
     * @return Red
     */
    public function setIdIglesia(\AE\DataBundle\Entity\Iglesia $idIglesia = null)
    {
        $this->idIglesia = $idIglesia;
    
        return $this;
    }

    /**
     * Get idIglesia
     *
     * @return \AE\DataBundle\Entity\Iglesia 
     */
    public function getIdIglesia()
    {
        return $this->idIglesia;
    }

    /**
     * Set idLiderRed
     *
     * @param \AE\DataBundle\Entity\LiderRed $idLiderRed
     * @return Red
     */
    public function setIdLiderRed(\AE\DataBundle\Entity\LiderRed $idLiderRed = null)
    {
        $this->idLiderRed = $idLiderRed;
    
        return $this;
    }

    /**
     * Get idLiderRed
     *
     * @return \AE\DataBundle\Entity\LiderRed 
     */
    public function getIdLiderRed()
    {
        return $this->idLiderRed;
    }

    /**
     * Set idPastorAsociado
     *
     * @param \AE\DataBundle\Entity\PastorAsociado $idPastorAsociado
     * @return Red
     */
    public function setIdPastorAsociado(\AE\DataBundle\Entity\PastorAsociado $idPastorAsociado = null)
    {
        $this->idPastorAsociado = $idPastorAsociado;
    
        return $this;
    }

    /**
     * Get idPastorAsociado
     *
     * @return \AE\DataBundle\Entity\PastorAsociado 
     */
    public function getIdPastorAsociado()
    {
        return $this->idPastorAsociado;
    }
    
    /**
     * Set pastor
     *
     * @param \AE\DataBundle\Entity\PastorEjecutivo $pastor
     * @return Red
     */
    public function setPastor(\AE\DataBundle\Entity\PastorEjecutivo $pastor = null)
    {
        $this->pastor = $pastor;
    
        return $this;
    }

    /**
     * Get pastor
     *
     * @return \AE\DataBundle\Entity\PastorEjecutivo 
     */
    public function getPastor()
    {
        return $this->pastor;
    }
}