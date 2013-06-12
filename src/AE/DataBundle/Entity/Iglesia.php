<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Iglesia
 *
 * @ORM\Table(name="iglesia")
 * @ORM\Entity
 */
class Iglesia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="iglesia_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AreaVision", inversedBy="idIglesia")
     * @ORM\JoinTable(name="iglesia_area",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_iglesia", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_area_vision", referencedColumnName="id")
     *   }
     * )
     */
    private $idAreaVision;

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
     * Constructor
     */
    public function __construct()
    {
        $this->idAreaVision = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Iglesia
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
     * Set telefono
     *
     * @param string $telefono
     * @return Iglesia
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
     * Add idAreaVision
     *
     * @param \AE\DataBundle\Entity\AreaVision $idAreaVision
     * @return Iglesia
     */
    public function addIdAreaVision(\AE\DataBundle\Entity\AreaVision $idAreaVision)
    {
        $this->idAreaVision[] = $idAreaVision;
    
        return $this;
    }

    /**
     * Remove idAreaVision
     *
     * @param \AE\DataBundle\Entity\AreaVision $idAreaVision
     */
    public function removeIdAreaVision(\AE\DataBundle\Entity\AreaVision $idAreaVision)
    {
        $this->idAreaVision->removeElement($idAreaVision);
    }

    /**
     * Get idAreaVision
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdAreaVision()
    {
        return $this->idAreaVision;
    }

    /**
     * Set idUbicacion
     *
     * @param \AE\DataBundle\Entity\Ubicacion $idUbicacion
     * @return Iglesia
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