<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consolida
 *
 * @ORM\Table(name="consolida")
 * @ORM\Entity
 */
class Consolida
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="consolida_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pausa", type="boolean", nullable=false)
     */
    private $pausa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_pausa", type="date", nullable=true)
     */
    private $fechaPausa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reanudacion", type="date", nullable=true)
     */
    private $fechaReanudacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="termino", type="boolean", nullable=true)
     */
    private $termino;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TemaLeche", inversedBy="idConsolida")
     * @ORM\JoinTable(name="many_consolidacion_has_many_tema_leche",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_consolida", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_tema_leche", referencedColumnName="id")
     *   }
     * )
     */
    private $idTemaLeche;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Herramienta", mappedBy="idConsolida")
     */
    private $idHerramienta;

    /**
     * @var \NuevoConvertido
     *
     * @ORM\ManyToOne(targetEntity="NuevoConvertido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nuevo_convertido", referencedColumnName="id")
     * })
     */
    private $idNuevoConvertido;

    /**
     * @var \Miembro
     *
     * @ORM\ManyToOne(targetEntity="Miembro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_miembro", referencedColumnName="id")
     * })
     */
    private $idMiembro;

    /**
     * @var \Consolidador
     *
     * @ORM\ManyToOne(targetEntity="Consolidador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_consolidador", referencedColumnName="id")
     * })
     */
    private $idConsolidador;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idTemaLeche = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idHerramienta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Consolida
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
     * @return Consolida
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
     * Set pausa
     *
     * @param boolean $pausa
     * @return Consolida
     */
    public function setPausa($pausa)
    {
        $this->pausa = $pausa;
    
        return $this;
    }

    /**
     * Get pausa
     *
     * @return boolean 
     */
    public function getPausa()
    {
        return $this->pausa;
    }

    /**
     * Set fechaPausa
     *
     * @param \DateTime $fechaPausa
     * @return Consolida
     */
    public function setFechaPausa($fechaPausa)
    {
        $this->fechaPausa = $fechaPausa;
    
        return $this;
    }

    /**
     * Get fechaPausa
     *
     * @return \DateTime 
     */
    public function getFechaPausa()
    {
        return $this->fechaPausa;
    }

    /**
     * Set fechaReanudacion
     *
     * @param \DateTime $fechaReanudacion
     * @return Consolida
     */
    public function setFechaReanudacion($fechaReanudacion)
    {
        $this->fechaReanudacion = $fechaReanudacion;
    
        return $this;
    }

    /**
     * Get fechaReanudacion
     *
     * @return \DateTime 
     */
    public function getFechaReanudacion()
    {
        return $this->fechaReanudacion;
    }

    /**
     * Set termino
     *
     * @param boolean $termino
     * @return Consolida
     */
    public function setTermino($termino)
    {
        $this->termino = $termino;
    
        return $this;
    }

    /**
     * Get termino
     *
     * @return boolean 
     */
    public function getTermino()
    {
        return $this->termino;
    }

    /**
     * Add idTemaLeche
     *
     * @param \AE\DataBundle\Entity\TemaLeche $idTemaLeche
     * @return Consolida
     */
    public function addIdTemaLeche(\AE\DataBundle\Entity\TemaLeche $idTemaLeche)
    {
        $this->idTemaLeche[] = $idTemaLeche;
    
        return $this;
    }

    /**
     * Remove idTemaLeche
     *
     * @param \AE\DataBundle\Entity\TemaLeche $idTemaLeche
     */
    public function removeIdTemaLeche(\AE\DataBundle\Entity\TemaLeche $idTemaLeche)
    {
        $this->idTemaLeche->removeElement($idTemaLeche);
    }

    /**
     * Get idTemaLeche
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdTemaLeche()
    {
        return $this->idTemaLeche;
    }

    /**
     * Add idHerramienta
     *
     * @param \AE\DataBundle\Entity\Herramienta $idHerramienta
     * @return Consolida
     */
    public function addIdHerramienta(\AE\DataBundle\Entity\Herramienta $idHerramienta)
    {
        $this->idHerramienta[] = $idHerramienta;
    
        return $this;
    }

    /**
     * Remove idHerramienta
     *
     * @param \AE\DataBundle\Entity\Herramienta $idHerramienta
     */
    public function removeIdHerramienta(\AE\DataBundle\Entity\Herramienta $idHerramienta)
    {
        $this->idHerramienta->removeElement($idHerramienta);
    }

    /**
     * Get idHerramienta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdHerramienta()
    {
        return $this->idHerramienta;
    }

    /**
     * Set idNuevoConvertido
     *
     * @param \AE\DataBundle\Entity\NuevoConvertido $idNuevoConvertido
     * @return Consolida
     */
    public function setIdNuevoConvertido(\AE\DataBundle\Entity\NuevoConvertido $idNuevoConvertido = null)
    {
        $this->idNuevoConvertido = $idNuevoConvertido;
    
        return $this;
    }

    /**
     * Get idNuevoConvertido
     *
     * @return \AE\DataBundle\Entity\NuevoConvertido 
     */
    public function getIdNuevoConvertido()
    {
        return $this->idNuevoConvertido;
    }

    /**
     * Set idMiembro
     *
     * @param \AE\DataBundle\Entity\Miembro $idMiembro
     * @return Consolida
     */
    public function setIdMiembro(\AE\DataBundle\Entity\Miembro $idMiembro = null)
    {
        $this->idMiembro = $idMiembro;
    
        return $this;
    }

    /**
     * Get idMiembro
     *
     * @return \AE\DataBundle\Entity\Miembro 
     */
    public function getIdMiembro()
    {
        return $this->idMiembro;
    }

    /**
     * Set idConsolidador
     *
     * @param \AE\DataBundle\Entity\Consolidador $idConsolidador
     * @return Consolida
     */
    public function setIdConsolidador(\AE\DataBundle\Entity\Consolidador $idConsolidador = null)
    {
        $this->idConsolidador = $idConsolidador;
    
        return $this;
    }

    /**
     * Get idConsolidador
     *
     * @return \AE\DataBundle\Entity\Consolidador 
     */
    public function getIdConsolidador()
    {
        return $this->idConsolidador;
    }
}