<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ubigeo
 *
 * @ORM\Table(name="ubigeo")
 * @ORM\Entity
 */
class Ubigeo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ubigeo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="coddepartamento", type="smallint", nullable=true)
     */
    private $coddepartamento;

    /**
     * @var integer
     *
     * @ORM\Column(name="codprovincia", type="smallint", nullable=true)
     */
    private $codprovincia;

    /**
     * @var integer
     *
     * @ORM\Column(name="coddistrito", type="smallint", nullable=true)
     */
    private $coddistrito;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=70, nullable=true)
     */
    private $departamento;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=70, nullable=true)
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="distrito", type="string", length=70, nullable=true)
     */
    private $distrito;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="long", type="float", nullable=true)
     */
    private $long;



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
     * Set coddepartamento
     *
     * @param integer $coddepartamento
     * @return Ubigeo
     */
    public function setCoddepartamento($coddepartamento)
    {
        $this->coddepartamento = $coddepartamento;
    
        return $this;
    }

    /**
     * Get coddepartamento
     *
     * @return integer 
     */
    public function getCoddepartamento()
    {
        return $this->coddepartamento;
    }

    /**
     * Set codprovincia
     *
     * @param integer $codprovincia
     * @return Ubigeo
     */
    public function setCodprovincia($codprovincia)
    {
        $this->codprovincia = $codprovincia;
    
        return $this;
    }

    /**
     * Get codprovincia
     *
     * @return integer 
     */
    public function getCodprovincia()
    {
        return $this->codprovincia;
    }

    /**
     * Set coddistrito
     *
     * @param integer $coddistrito
     * @return Ubigeo
     */
    public function setCoddistrito($coddistrito)
    {
        $this->coddistrito = $coddistrito;
    
        return $this;
    }

    /**
     * Get coddistrito
     *
     * @return integer 
     */
    public function getCoddistrito()
    {
        return $this->coddistrito;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     * @return Ubigeo
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return string 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Ubigeo
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
    
        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set distrito
     *
     * @param string $distrito
     * @return Ubigeo
     */
    public function setDistrito($distrito)
    {
        $this->distrito = $distrito;
    
        return $this;
    }

    /**
     * Get distrito
     *
     * @return string 
     */
    public function getDistrito()
    {
        return $this->distrito;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Ubigeo
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set long
     *
     * @param float $long
     * @return Ubigeo
     */
    public function setLong($long)
    {
        $this->long = $long;
    
        return $this;
    }

    /**
     * Get long
     *
     * @return float 
     */
    public function getLong()
    {
        return $this->long;
    }
}