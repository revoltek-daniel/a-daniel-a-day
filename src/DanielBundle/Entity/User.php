<?php

namespace DanielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    protected $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * @var string
     */
    protected $plainPassword = '';

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $street;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $zip;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $country;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var bool
     */
    protected $isActive = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->role = 'ROLE_USER';
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get Password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get City.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return User
     */
    public function setCity(string $city): User
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get Street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return User
     */
    public function setStreet(string $street): User
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get Zip.
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     *
     * @return User
     */
    public function setZip(string $zip): User
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get Country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return User
     */
    public function setCountry(string $country): User
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getisActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): User
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get PlainPassword.
     *
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole(string $role): User
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [$this->role];
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
}
