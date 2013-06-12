<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AreaVision
 *
 * @ORM\Table(name="area_vision")
 * @ORM\Entity
 */
class AreaVision
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Iglesia", mappedBy="idAreaVision")
     */
    private $idIglesia;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Encargado", mappedBy="idAreaVision")
     */
    private $idEncargado;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idIglesia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idEncargado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return AreaVision
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
     * Add idIglesia
     *
     * @param \AE\DataBundle\Entity\Iglesia $idIglesia
     * @return AreaVision
     */
    public function addIdIglesia(\AE\DataBundle\Entity\Iglesia $idIglesia)
    {
        $this->idIglesia[] = $idIglesia;
    
        return $this;
    }

    /**
     * Remove idIglesia
     *
     * @param \AE\DataBundle\Entity\Iglesia $idIglesia
     */
    public function removeIdIglesia(\AE\DataBundle\Entity\Iglesia $idIglesia)
    {
        $this->idIglesia->removeElement($idIglesia);
    }

    /**
     * Get idIglesia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdIglesia()
    {
        return $this->idIglesia;
    }

    /**
     * Add idEncargado
     *
     * @param \AE\DataBundle\Entity\Encargado $idEncargado
     * @return AreaVision
     */
    public function addIdEncargado(\AE\DataBundle\Entity\Encargado $idEncargado)
    {
        $this->idEncargado[] = $idEncargado;
    
        return $this;
    }

    /**
     * Remove idEncargado
     *
     * @param \AE\DataBundle\Entity\Encargado $idEncargado
     */
    public function removeIdEncargado(\AE\DataBundle\Entity\Encargado $idEncargado)
    {
        $this->idEncargado->removeElement($idEncargado);
    }

    /**
     * Get idEncargado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdEncargado()
    {
        return $this->idEncargado;
    }
}