<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ManyEncargadoHasManyAreaVision
 *
 * @ORM\Table(name="many_encargado_has_many_area_vision")
 * @ORM\Entity
 */
class ManyEncargadoHasManyAreaVision
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="many_encargado_has_many_area_vision_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_encargado", type="bigint", nullable=false)
     */
    private $idEncargado;

    /**
     * @var \AreaVision
     *
     * @ORM\ManyToOne(targetEntity="AreaVision")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_area_vision", referencedColumnName="id")
     * })
     */
    private $idAreaVision;



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
     * Set idEncargado
     *
     * @param integer $idEncargado
     * @return ManyEncargadoHasManyAreaVision
     */
    public function setIdEncargado($idEncargado)
    {
        $this->idEncargado = $idEncargado;
    
        return $this;
    }

    /**
     * Get idEncargado
     *
     * @return integer 
     */
    public function getIdEncargado()
    {
        return $this->idEncargado;
    }

    /**
     * Set idAreaVision
     *
     * @param \AE\DataBundle\Entity\AreaVision $idAreaVision
     * @return ManyEncargadoHasManyAreaVision
     */
    public function setIdAreaVision(\AE\DataBundle\Entity\AreaVision $idAreaVision = null)
    {
        $this->idAreaVision = $idAreaVision;
    
        return $this;
    }

    /**
     * Get idAreaVision
     *
     * @return \AE\DataBundle\Entity\AreaVision 
     */
    public function getIdAreaVision()
    {
        return $this->idAreaVision;
    }
}