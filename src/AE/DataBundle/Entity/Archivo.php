<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo")
 * @ORM\Entity
 */
class Archivo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="archivo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="text", nullable=false)
     */
    private $direccion;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso", type="bigint", nullable=true)
     */
    private $peso;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=25, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=10, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="text", nullable=true)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var \TemaCelula
     *
     * @ORM\ManyToOne(targetEntity="TemaCelula",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tema_celula", referencedColumnName="id")
     * })
     */
    private $idTemaCelula;

    /**
     * @var \TemaCurso
     *
     * @ORM\ManyToOne(targetEntity="TemaCurso",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tema_curso", referencedColumnName="id")
     * })
     */
    private $idTemaCurso;

    /**
     * @var \LecheEspiritual
     *
     * @ORM\ManyToOne(targetEntity="LecheEspiritual",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_leche_espiritual", referencedColumnName="id")
     * })
     */
    private $idLecheEspiritual;



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
     * @return Archivo
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
     * Set peso
     *
     * @param integer $peso
     * @return Archivo
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    
        return $this;
    }

    /**
     * Get peso
     *
     * @return integer 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Archivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Archivo
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    
        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Archivo
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Archivo
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set idTemaCelula
     *
     * @param \AE\DataBundle\Entity\TemaCelula $idTemaCelula
     * @return Archivo
     */
    public function setIdTemaCelula(\AE\DataBundle\Entity\TemaCelula $idTemaCelula = null)
    {
        $this->idTemaCelula = $idTemaCelula;
    
        return $this;
    }

    /**
     * Get idTemaCelula
     *
     * @return \AE\DataBundle\Entity\TemaCelula 
     */
    public function getIdTemaCelula()
    {
        return $this->idTemaCelula;
    }

    /**
     * Set idTemaCurso
     *
     * @param \AE\DataBundle\Entity\TemaCurso $idTemaCurso
     * @return Archivo
     */
    public function setIdTemaCurso(\AE\DataBundle\Entity\TemaCurso $idTemaCurso = null)
    {
        $this->idTemaCurso = $idTemaCurso;
    
        return $this;
    }

    /**
     * Get idTemaCurso
     *
     * @return \AE\DataBundle\Entity\TemaCurso 
     */
    public function getIdTemaCurso()
    {
        return $this->idTemaCurso;
    }

    /**
     * Set idLecheEspiritual
     *
     * @param \AE\DataBundle\Entity\LecheEspiritual $idLecheEspiritual
     * @return Archivo
     */
    public function setIdLecheEspiritual(\AE\DataBundle\Entity\LecheEspiritual $idLecheEspiritual = null)
    {
        $this->idLecheEspiritual = $idLecheEspiritual;
    
        return $this;
    }

    /**
     * Get idLecheEspiritual
     *
     * @return \AE\DataBundle\Entity\LecheEspiritual 
     */
    public function getIdLecheEspiritual()
    {
        return $this->idLecheEspiritual;
    }
}