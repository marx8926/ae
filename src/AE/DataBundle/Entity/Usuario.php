<?php

namespace AE\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use \Serializable;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="usuario_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Rol", inversedBy="idUsuario")
     * @ORM\JoinTable(name="many_usuario_has_many_rol",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_rol", referencedColumnName="id")
     *   }
     * )
     */
    protected $idRol;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona",cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_persona", referencedColumnName="id")
     * })
     */
    protected $idPersona;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idRol = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt = md5(time());
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
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
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
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($pass)
    {
        
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $passw = $encoder->encodePassword($pass, $this->getSalt());
      
        $this->password = $passw;
    
        return $this;
    }

    /**
     * Get password
     *
     * 
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

      /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Usuario
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add idRol
     *
     * @param \AE\DataBundle\Entity\Rol $idRol
     * @return Usuario
     */
    public function addIdRol(\AE\DataBundle\Entity\Rol $idRol)
    {
        $this->idRol[] = $idRol;
    
        return $this;
    }

    /**
     * Remove idRol
     *
     * @param \AE\DataBundle\Entity\Rol $idRol
     */
    public function removeIdRol(\AE\DataBundle\Entity\Rol $idRol)
    {
        $this->idRol->removeElement($idRol);
    }

    /**
     * Get idRol
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set idPersona
     *
     * @param \AE\DataBundle\Entity\Persona $idPersona
     * @return Usuario
     */
    public function setIdPersona(\AE\DataBundle\Entity\Persona $idPersona = null)
    {
        $this->idPersona = $idPersona;
    
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return \AE\DataBundle\Entity\Persona 
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return $this->getIdRol()->toArray();
    }

    public function getUsername() {
        return $this->getNombre();
    }

    public function serialize() {
                return serialize(array($this->id,  $this->password, $this->nombre));

    }

    public function unserialize($serialized) {
        list(
$this->id,
$this->password,
$this->nombre
) = unserialize($serialized);
    }

   /*
    public function serialize() {
        return serialize(array($this->id));
    }

    public function unserialize($serialized) {
         list(
            $this->id
        ) = unserialize($serialized);
      
    }
    * 
    */
}