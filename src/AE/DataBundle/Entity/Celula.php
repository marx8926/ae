<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Celula
 *
 * @ORM\Table(name="celula")
 * @ORM\Entity
 */
class Celula
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="celula_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="smallint", nullable=false)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="familia", type="string", length=100, nullable=false)
     */
    private $familia;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;
    
     /**
     * @var string
     *
     * @ORM\Column(name="dia", type="string", length=25, nullable=true)
     */
    private $dia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;


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
     * @var \Red
     *
     * @ORM\ManyToOne(targetEntity="Red",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_red", referencedColumnName="id")
     * })
     */
    private $idRed;

    /**
     * @var \Misionero
     *
     * @ORM\ManyToOne(targetEntity="Misionero",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_misionero", referencedColumnName="id")
     * })
     */
    private $idMisionero;

    /**
     * @var \PastorEjecutivo
     *
     * @ORM\ManyToOne(targetEntity="PastorEjecutivo",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pastor_ejecutivo", referencedColumnName="id")
     * })
     */
    private $idPastorEjecutivo;

    /**
     * @var \LiderRed
     *
     * @ORM\ManyToOne(targetEntity="LiderRed",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lider_red", referencedColumnName="id")
     * })
     */
    private $idLiderRed;
    
    /**
     * @var \Lider
     * 
     *  @ORM\ManyToOne(targetEntity="Lider",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lider", referencedColumnName="id")
     * })
     */

     private $idLider;
             
             
      /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Celula
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Celula
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
     * Set familia
     *
     * @param string $familia
     * @return Celula
     */
    public function setFamilia($familia)
    {
        $this->familia = $familia;
    
        return $this;
    }

    /**
     * Get familia
     *
     * @return string 
     */
    public function getFamilia()
    {
        return $this->familia;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Celula
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set idUbicacion
     *
     * @param \AE\DataBundle\Entity\Ubicacion $idUbicacion
     * @return Celula
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
     * Set idRed
     *
     * @param \AE\DataBundle\Entity\Red $idRed
     * @return Celula
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

    /**
     * Set idMisionero
     *
     * @param \AE\DataBundle\Entity\Misionero $idMisionero
     * @return Celula
     */
    public function setIdMisionero(\AE\DataBundle\Entity\Misionero $idMisionero = null)
    {
        $this->idMisionero = $idMisionero;
    
        return $this;
    }

    /**
     * Get idMisionero
     *
     * @return \AE\DataBundle\Entity\Misionero 
     */
    public function getIdMisionero()
    {
        return $this->idMisionero;
    }

    /**
     * Set idPastorEjecutivo
     *
     * @param \AE\DataBundle\Entity\PastorEjecutivo $idPastorEjecutivo
     * @return Celula
     */
    public function setIdPastorEjecutivo(\AE\DataBundle\Entity\PastorEjecutivo $idPastorEjecutivo = null)
    {
        $this->idPastorEjecutivo = $idPastorEjecutivo;
    
        return $this;
    }

    /**
     * Get idPastorEjecutivo
     *
     * @return \AE\DataBundle\Entity\PastorEjecutivo 
     */
    public function getIdPastorEjecutivo()
    {
        return $this->idPastorEjecutivo;
    }

    /**
     * Set idLiderRed
     *
     * @param \AE\DataBundle\Entity\LiderRed $idLiderRed
     * @return Celula
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
     * Set idLider
     *
     * @param \AE\DataBundle\Entity\Lider $idLider
     * @return Celula
     */
    public function setIdLider(\AE\DataBundle\Entity\LiderRed $idLider = null)
    {
        $this->idLider = $idLider;
    
        return $this;
    }

    /**
     * Get idLider
     *
     * @return \AE\DataBundle\Entity\Lider
     */
    public function getIdLider()
    {
        return $this->idLider;
    }
    
     /**
     * Set dia
     *
     * @param string $dia
     * @return Celula
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
     * @return Celula
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
}