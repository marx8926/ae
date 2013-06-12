<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Local
 *
 * @ORM\Table(name="local")
 * @ORM\Entity
 */
class Local
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="curso_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombe", type="string", length=20, nullable=false)
     */
    private $nombe;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=2, nullable=false)
     */
    private $codigo;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="smallint", nullable=false)
     */
    private $tipo;

    /**
     * Set nombe
     *
     * @param string $nombe
     * @return Local
     */
    public function setNombe($nombe)
    {
        $this->nombe = $nombe;
    
        return $this;
    }

    /**
     * Get nombe
     *
     * @return string 
     */
    public function getNombe()
    {
        return $this->nombe;
    }
    
    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Local
     */
    public function setCodigo($codigo)
    {
    	$this->codigo = $codigo;
    
    	return $this;
    }
    
    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
    	return $this->codigo;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Local
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}