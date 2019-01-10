<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    const DEFAULT_AVATAR = 'avatar.png';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 3,
     *     max = 20,
     *     minMessage = "Username must be at least {{ limit }} characters long",
     *     maxMessage = "Username cannot be longer than {{ limit }} characters"
     * )
     *
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\d]+$/",
     *     message="Username can be letters and digits only"
     * )
     *
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     * )
     *
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z\d]+$/",
     *     message="Password can have only letters and digits"
     * )
     *
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var Role[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *    joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *    inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var ArrayCollection|Platform[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Platform", mappedBy="user")
     */
    private $platforms;

    /**
     * @var Platform
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Platform")
     */
    private $currentPlatform;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->platforms = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        $email = null !== $this->email ? $this->email : 'N/A';

        return $email;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return null !== $this->avatar ? $this->avatar : self::DEFAULT_AVATAR;
    }

    /**
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles.map(function (Role $role) {
                return $role->getName();
            });
    }

    public function addRole(Role $role)
    {
        $this->roles->add($role);
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Platform[]|ArrayCollection
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }

    /**
     * @param Platform[]|ArrayCollection $platforms
     * @return User
     */
    public function addPlatform(Platform $platform)
    {
        $this->platforms->add($platform);

        return $this;
    }

    /**
     * @return Platform
     */
    public function getCurrentPlatform(): Platform
    {
        return $this->currentPlatform;
    }

    /**
     * @param Platform $currentPlatform
     *
     * @return User
     */
    public function setCurrentPlatform(Platform $currentPlatform)
    {
        $this->currentPlatform = $currentPlatform;

        return $this;
    }
}

