<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaseCurso
 *
 * @ORM\Table(name="clase_curso")
 * @ORM\Entity
 */
class ClaseCurso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="clase_curso_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="tema", type="integer", nullable=true)
     */
    private $tema;
    
     /**
     * @var float
     *
     * @ORM\Column(name="ofrenda", type="float", nullable=true)
     */
    private $ofrenda;

    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_dicto", type="date", nullable=true)
     */
    private $fechaDicto;

    /**
     * @var \CursoImpartido
     *
     * @ORM\ManyToOne(targetEntity="CursoImpartido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_curso_impartido", referencedColumnName="id")
     * })
     */
    private $idCursoImpartido;
   

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
     * Set tema
     *
     * @param integer $tema
     * @return ClaseCurso
     */
    public function setTema($tema)
    {
        $this->tema = $tema;
    
        return $this;
    }

    /**
     * Get tema
     *
     * @return integer 
     */
    public function getTema()
    {
        return $this->tema;
    }
    
     /**
     * Set ofrenda
     *
     * @param float $ofrenda
     * @return ClaseCurso
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
     * @return ClaseCurso
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
     * Set idCursoImpartido
     *
     * @param \AE\DataBundle\Entity\CursoImpartido $idCursoImpartido
     * @return ClaseCurso
     */
    public function setIdCursoImpartido(\AE\DataBundle\Entity\CursoImpartido $idCursoImpartido = null)
    {
        $this->idCursoImpartido = $idCursoImpartido;
    
        return $this;
    }

    /**
     * Get idCursoImpartido
     *
     * @return \AE\DataBundle\Entity\CursoImpartido 
     */
    public function getIdCursoImpartido()
    {
        return $this->idCursoImpartido;
    }
}