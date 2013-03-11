<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prerequisito
 *
 * @ORM\Table(name="prerequisito")
 * @ORM\Entity
 */
class Prerequisito
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_curso", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="prerequisito_id_curso_seq", allocationSize=1, initialValue=1)
     */
    private $idCurso;

    /**
     * @var \Curso
     *
     * @ORM\ManyToOne(targetEntity="Curso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_curso1", referencedColumnName="id")
     * })
     */
    private $idCurso1;



    /**
     * Get idCurso
     *
     * @return integer 
     */
    public function getIdCurso()
    {
        return $this->idCurso;
    }

    /**
     * Set idCurso1
     *
     * @param \AE\DataBundle\Entity\Curso $idCurso1
     * @return Prerequisito
     */
    public function setIdCurso1(\AE\DataBundle\Entity\Curso $idCurso1 = null)
    {
        $this->idCurso1 = $idCurso1;
    
        return $this;
    }

    /**
     * Get idCurso1
     *
     * @return \AE\DataBundle\Entity\Curso 
     */
    public function getIdCurso1()
    {
        return $this->idCurso1;
    }
    
    /**
     * Set idCurso2
     *
     * @param \AE\DataBundle\Entity\Curso $idCurso2
     * @return Prerequisito
     */
    public function setIdCurso2(\AE\DataBundle\Entity\Curso $idCurso2 = null)
    {
    	$this->idCurso2 = $idCurso2;
    
    	return $this;
    }
    
    /**
     * Get idCurso2
     *
     * @return \AE\DataBundle\Entity\Curso
     */
    public function getIdCurso2()
    {
    	return $this->idCurso2;
    }
    
    
}