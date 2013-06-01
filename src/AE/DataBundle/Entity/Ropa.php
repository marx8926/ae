<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ropa
 *
 * @ORM\Table(name="ropa")
 * @ORM\Entity
 */
class Ropa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ropa_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=15, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="talla", type="string", length=10, nullable=true)
     */
    private $talla;

    /**
     * @var \Miembro
     *
     * @ORM\ManyToOne(targetEntity="Miembro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="miembro", referencedColumnName="id")
     * })
     */
    private $miembro;



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
     * @return Ropa
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
     * Set talla
     *
     * @param string $talla
     * @return Ropa
     */
    public function setTalla($talla)
    {
        $this->talla = $talla;
    
        return $this;
    }

    /**
     * Get talla
     *
     * @return string 
     */
    public function getTalla()
    {
        return $this->talla;
    }

    /**
     * Set miembro
     *
     * @param \AE\DataBundle\Entity\Miembro $miembro
     * @return Ropa
     */
    public function setMiembro(\AE\DataBundle\Entity\Miembro $miembro = null)
    {
        $this->miembro = $miembro;
    
        return $this;
    }

    /**
     * Get miembro
     *
     * @return \AE\DataBundle\Entity\Miembro 
     */
    public function getMiembro()
    {
        return $this->miembro;
    }
}