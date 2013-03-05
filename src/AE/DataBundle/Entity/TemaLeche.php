<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TemaLeche
 *
 * @ORM\Table(name="tema_leche")
 * @ORM\Entity
 */
class TemaLeche
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tema_leche_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=true)
     */
    private $titulo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Consolida", mappedBy="idTemaLeche")
     */
    private $idConsolida;

    /**
     * @var \LecheEspiritual
     *
     * @ORM\ManyToOne(targetEntity="LecheEspiritual")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_leche_espiritual", referencedColumnName="id")
     * })
     */
    private $idLecheEspiritual;

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
     * Set titulo
     *
     * @param string $titulo
     * @return TemaLeche
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Add idConsolida
     *
     * @param \AE\DataBundle\Entity\Consolida $idConsolida
     * @return TemaLeche
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

    /**
     * Set idLecheEspiritual
     *
     * @param \AE\DataBundle\Entity\LecheEspiritual $idLecheEspiritual
     * @return TemaLeche
     */
    public function setIdLecheEspiritual(\AE\DataBundle\Entity\LecheEspiritual $idLecheEspiritual = null)
    {
        $this->idLecheEspiritual = $idLecheEspiritual;
    
        return $this;
    }

    /**
     * Get idLecheEspiritual
     *
     * @return \AE\DataBundle\Entity\LecheEspiritual 
     */
    public function getIdLecheEspiritual()
    {
        return $this->idLecheEspiritual;
    }
}