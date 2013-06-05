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
     * @var \LiderRed
     *
     * @ORM\ManyToOne(targetEntity="LiderRed")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="liderred", referencedColumnName="id")
     * })
     */
    private $liderred;



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
     * Set liderred
     *
     * @param \AE\DataBundle\Entity\LiderRed $liderred
     * @return AsistenciaCulto
     */
    public function setLiderred(\AE\DataBundle\Entity\LiderRed $liderred = null)
    {
        $this->liderred = $liderred;
    
        return $this;
    }

    /**
     * Get liderred
     *
     * @return \AE\DataBundle\Entity\LiderRed 
     */
    public function getLiderred()
    {
        return $this->liderred;
    }
}