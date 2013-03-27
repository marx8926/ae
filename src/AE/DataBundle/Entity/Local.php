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
     * @var string
     *
     * @ORM\Column(name="id", type="string", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombe", type="string", length=20, nullable=false)
     */
    private $nombe;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="smallint", nullable=false)
     */
    private $tipo;



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
     * Set id
     * 
     * @param string id
     * @return Local
     */
    public function setId($id)
    {
    	$this->id = $id;
    	return $this;
    }

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