<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Herramienta
 *
 * @ORM\Table(name="herramienta")
 * @ORM\Entity
 */
class Herramienta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="herramienta_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tiempo_optimo", type="string", nullable=false)
     */
    private $tiempoOptimo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Consolida", inversedBy="idHerramienta" ,cascade={"persist", "merge", "remove"})
     * @ORM\JoinTable(name="many_herramienta_has_many_consolidacion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_herramienta", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_consolida", referencedColumnName="id")
     *   }
     * )
     */
    private $idConsolida;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idConsolida = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Herramienta
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
     * Set tiempoOptimo
     *
     * @param string $tiempoOptimo
     * @return Herramienta
     */
    public function setTiempoOptimo($tiempoOptimo)
    {
        $this->tiempoOptimo = $tiempoOptimo;
    
        return $this;
    }

    /**
     * Get tiempoOptimo
     *
     * @return string 
     */
    public function getTiempoOptimo()
    {
        return $this->tiempoOptimo;
    }

    /**
     * Add idConsolida
     *
     * @param \AE\DataBundle\Entity\Consolida $idConsolida
     * @return Herramienta
     */
    public function addIdConsolida(\AE\DataBundle\Entity\Consolida $idConsolida)
    {
        $this->idConsolida[] = $idConsolida;
    
        return $this;
    }

    /**
     * Remove idConsolida
     *
     * @param \AE\DataBundle\Entity\Consolida $idConsolida
     */
    public function removeIdConsolida(\AE\DataBundle\Entity\Consolida $idConsolida)
    {
        $this->idConsolida->removeElement($idConsolida);
    }

    /**
     * Get idConsolida
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdConsolida()
    {
        return $this->idConsolida;
    }
}