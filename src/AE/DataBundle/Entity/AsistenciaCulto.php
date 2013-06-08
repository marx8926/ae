<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsistenciaCulto
 *
 * @ORM\Table(name="asistencia_culto")
 * @ORM\Entity
 */
class AsistenciaCulto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="asistencia_culto_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="culto", type="date", nullable=false)
     */
    private $culto;

    /**
     * @var integer
     *
     * @ORM\Column(name="asistentes", type="integer", nullable=true)
     */
    private $asistentes;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set culto
     *
     * @param \DateTime $culto
     * @return AsistenciaCulto
     */
    public function setCulto($culto)
    {
        $this->culto = $culto;
    
        return $this;
    }

    /**
     * Get culto
     *
     * @return \DateTime 
     */
    public function getCulto()
    {
        return $this->culto;
    }

    /**
     * Set asistentes
     *
     * @param integer $asistentes
     * @return AsistenciaCulto
     */
    public function setAsistentes($asistentes)
    {
        $this->asistentes = $asistentes;
    
        return $this;
    }

    /**
     * Get asistentes
     *
     * @return integer 
     */
    public function getAsistentes()
    {
        return $this->asistentes;
    }

      /**
     * Set idRed
     *
     * @param \AE\DataBundle\Entity\Red $idRed
     * @return AsistenciaCulto
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
   
}