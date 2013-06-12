<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaseCell
 *
 * @ORM\Table(name="clase_cell")
 * @ORM\Entity
 */
class ClaseCell
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="clase_cell_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_dicto", type="date", nullable=true)
     */
    private $fechaDicto;
    
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_asignado", type="date", nullable=true)
     */
    private $fechaAsignado;
    
     /**
     * @var float
     *
     * @ORM\Column(name="ofrenda", type="float", nullable=true)
     */
    private $ofrenda;
    
    
     /**
     * @var integer
     *
     * @ORM\Column(name="invitados", type="integer", nullable=true)
     */
    private $invitados;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Miembro", inversedBy="idClaseCell")
     * @ORM\JoinTable(name="many_clase_celula_has_many_miembro",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_clase_cell", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_miembro", referencedColumnName="id")
     *   }
     * )
     */
    private $idMiembro;

    /**
     * @var \Horario
     *
     * @ORM\ManyToOne(targetEntity="Horario",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_horario", referencedColumnName="id")
     * })
     */
    private $idHorario;

    /**
     * @var \Celula
     *
     * @ORM\ManyToOne(targetEntity="Celula",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_celula", referencedColumnName="id")
     * })
     */
    private $idCelula;

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
     * Constructor
     */
    public function __construct()
    {
        $this->idMiembro = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ofrenda
     *
     * @param float $ofrenda
     * @return ClaseCell
     */
    public function setOfrenda($ofrenda)
    {
        $this->ofrenda = $ofrenda;
    
        return $this;
    }

    /**
     * Get ofrenda
     *
     * @return float 
     */
    public function getOfrenda()
    {
        return $this->ofrenda;
    }

    /**
     * Set fechaDicto
     *
     * @param \DateTime $fechaDicto
     * @return ClaseCell
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
     * Set fechaDicto
     *
     * @param \DateTime $fechaAsignado
     * @return ClaseCell
     */
    public function setFechaAsignado($fechaAsignado)
    {
        $this->fechaAsignado = $fechaAsignado;
    
        return $this;
    }

    /**
     * Get fechaDicto
     *
     * @return \DateTime 
     */
    public function getFechaAsignado()
    {
        return $this->fechaAsignado;
    }

    /**
     * Add idMiembro
     *
     * @param \AE\DataBundle\Entity\Miembro $idMiembro
     * @return ClaseCell
     */
    public function addIdMiembro(\AE\DataBundle\Entity\Miembro $idMiembro)
    {
        $this->idMiembro[] = $idMiembro;
    
        return $this;
    }

    /**
     * Remove idMiembro
     *
     * @param \AE\DataBundle\Entity\Miembro $idMiembro
     */
    public function removeIdMiembro(\AE\DataBundle\Entity\Miembro $idMiembro)
    {
        $this->idMiembro->removeElement($idMiembro);
    }

    /**
     * Get idMiembro
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdMiembro()
    {
        return $this->idMiembro;
    }

    /**
     * Set idHorario
     *
     * @param \AE\DataBundle\Entity\Horario $idHorario
     * @return ClaseCell
     */
    public function setIdHorario(\AE\DataBundle\Entity\Horario $idHorario = null)
    {
        $this->idHorario = $idHorario;
    
        return $this;
    }

    /**
     * Get idHorario
     *
     * @return \AE\DataBundle\Entity\Horario 
     */
    public function getIdHorario()
    {
        return $this->idHorario;
    }

    /**
     * Set idCelula
     *
     * @param \AE\DataBundle\Entity\Celula $idCelula
     * @return ClaseCell
     */
    public function setIdCelula(\AE\DataBundle\Entity\Celula $idCelula = null)
    {
        $this->idCelula = $idCelula;
    
        return $this;
    }

    /**
     * Get idCelula
     *
     * @return \AE\DataBundle\Entity\Celula 
     */
    public function getIdCelula()
    {
        return $this->idCelula;
    }

    /**
     * Set idTemaCelula
     *
     * @param \AE\DataBundle\Entity\TemaCelula $idTemaCelula
     * @return ClaseCell
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
     * Set invitados
     *
     * @param integer $invitados
     * @return ClaseCell
     */
    public function setInvitados($invitados)
    {
        $this->invitados = $invitados;
    
        return $this;
    }

    /**
     * Get invitados
     *
     * @return integer 
     */
    public function getInvitados()
    {
        return $this->invitados;
    }

    
}