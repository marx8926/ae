<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Informe
 *
 * @ORM\Table(name="informe")
 * @ORM\Entity
 */
class Informe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="informe_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_lider_red_receptor", type="string", nullable=true)
     */
    private $idLiderRedReceptor;

    /**
     * @var string
     *
     * @ORM\Column(name="id_pastor_asociado_receptor", type="string", nullable=true)
     */
    private $idPastorAsociadoReceptor;

    /**
     * @var string
     *
     * @ORM\Column(name="id_encargado_receptor", type="string", nullable=true)
     */
    private $idEncargadoReceptor;

    /**
     * @var string
     *
     * @ORM\Column(name="id_misionero_receptor", type="string", nullable=true)
     */
    private $idMisioneroReceptor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var \LiderRed
     *
     * @ORM\ManyToOne(targetEntity="LiderRed")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lider_red", referencedColumnName="id")
     * })
     */
    private $idLiderRed;

    /**
     * @var \PastorAsociado
     *
     * @ORM\ManyToOne(targetEntity="PastorAsociado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pastor_asociado", referencedColumnName="id")
     * })
     */
    private $idPastorAsociado;

    /**
     * @var \Misionero
     *
     * @ORM\ManyToOne(targetEntity="Misionero")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_misionero", referencedColumnName="id")
     * })
     */
    private $idMisionero;

    /**
     * @var \Encargado
     *
     * @ORM\ManyToOne(targetEntity="Encargado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_encargado", referencedColumnName="code")
     * })
     */
    private $idEncargado;

    /**
     * @var \Lider
     *
     * @ORM\ManyToOne(targetEntity="Lider")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lider", referencedColumnName="id")
     * })
     */
    private $idLider;

    /**
     * @var \PastorEjecutivo
     *
     * @ORM\ManyToOne(targetEntity="PastorEjecutivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pastor_ejecutivo", referencedColumnName="id")
     * })
     */
    private $idPastorEjecutivo;



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
     * Set idLiderRedReceptor
     *
     * @param string $idLiderRedReceptor
     * @return Informe
     */
    public function setIdLiderRedReceptor($idLiderRedReceptor)
    {
        $this->idLiderRedReceptor = $idLiderRedReceptor;
    
        return $this;
    }

    /**
     * Get idLiderRedReceptor
     *
     * @return string 
     */
    public function getIdLiderRedReceptor()
    {
        return $this->idLiderRedReceptor;
    }

    /**
     * Set idPastorAsociadoReceptor
     *
     * @param string $idPastorAsociadoReceptor
     * @return Informe
     */
    public function setIdPastorAsociadoReceptor($idPastorAsociadoReceptor)
    {
        $this->idPastorAsociadoReceptor = $idPastorAsociadoReceptor;
    
        return $this;
    }

    /**
     * Get idPastorAsociadoReceptor
     *
     * @return string 
     */
    public function getIdPastorAsociadoReceptor()
    {
        return $this->idPastorAsociadoReceptor;
    }

    /**
     * Set idEncargadoReceptor
     *
     * @param string $idEncargadoReceptor
     * @return Informe
     */
    public function setIdEncargadoReceptor($idEncargadoReceptor)
    {
        $this->idEncargadoReceptor = $idEncargadoReceptor;
    
        return $this;
    }

    /**
     * Get idEncargadoReceptor
     *
     * @return string 
     */
    public function getIdEncargadoReceptor()
    {
        return $this->idEncargadoReceptor;
    }

    /**
     * Set idMisioneroReceptor
     *
     * @param string $idMisioneroReceptor
     * @return Informe
     */
    public function setIdMisioneroReceptor($idMisioneroReceptor)
    {
        $this->idMisioneroReceptor = $idMisioneroReceptor;
    
        return $this;
    }

    /**
     * Get idMisioneroReceptor
     *
     * @return string 
     */
    public function getIdMisioneroReceptor()
    {
        return $this->idMisioneroReceptor;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Informe
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
     * Set idLiderRed
     *
     * @param \AE\DataBundle\Entity\LiderRed $idLiderRed
     * @return Informe
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
     * @return Informe
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
     * Set idMisionero
     *
     * @param \AE\DataBundle\Entity\Misionero $idMisionero
     * @return Informe
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
     * Set idEncargado
     *
     * @param \AE\DataBundle\Entity\Encargado $idEncargado
     * @return Informe
     */
    public function setIdEncargado(\AE\DataBundle\Entity\Encargado $idEncargado = null)
    {
        $this->idEncargado = $idEncargado;
    
        return $this;
    }

    /**
     * Get idEncargado
     *
     * @return \AE\DataBundle\Entity\Encargado 
     */
    public function getIdEncargado()
    {
        return $this->idEncargado;
    }

    /**
     * Set idLider
     *
     * @param \AE\DataBundle\Entity\Lider $idLider
     * @return Informe
     */
    public function setIdLider(\AE\DataBundle\Entity\Lider $idLider = null)
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
     * Set idPastorEjecutivo
     *
     * @param \AE\DataBundle\Entity\PastorEjecutivo $idPastorEjecutivo
     * @return Informe
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
}