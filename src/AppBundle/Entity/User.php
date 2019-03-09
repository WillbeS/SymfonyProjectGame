<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 *
 */
class User implements UserInterface, \Serializable
{
    const DEFAULT_AVATAR = 'avatar.jpg';

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
     * @Assert\NotBlank(groups={"registration"})
     *
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     *     groups={"registration"}
     * )
     *
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z\d]+$/",
     *     message="Password can have only letters and digits",
     *     groups={"registration"}
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
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     *
     * @Assert\Length(
     *      min = 5,
     *      max = 1000,
     *      minMessage = "Min length: {{ limit }} characters",
     *      maxMessage = "Max length: {{ limit }} characters"
     * )
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_joined", type="datetime")
     */
    private $dateJoined;

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

    /**
     * @Assert\Image(
     *     minWidth = 10,
     *     maxWidth = 600,
     *     minHeight = 10,
     *     maxHeight = 600,
     *     detectCorrupted = true,
     *     corruptedMessage = "This image is corrupted. Please try to upload it again."
     * )
     */
    private $file;

    /**
     * @var int
     */
    private $newMessagesCount; // todo delete (should get this with the global methods)



    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->platforms = new ArrayCollection();
        $this->dateJoined = new \DateTime('now');
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
        return $this->email;
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
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return User
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateJoined(): \DateTime
    {
        return $this->dateJoined;
    }

    /**
     * @param \DateTime $dateJoined
     *
     * @return User
     */
    public function setDateJoined(\DateTime $dateJoined)
    {
        $this->dateJoined = $dateJoined;

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
        return array_map(function (Role $role) {
            return $role->getName();
        }, $this->roles->toArray());
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

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return User
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function isPrivate()
    {
        return true;
    }

    /**
     * @return int
     */
    public function getNewMessagesCount(): ?int
    {
        return $this->newMessagesCount;
    }

    /**
     * @param int $newMessagesCount
     *
     * @return User
     */
    public function setNewMessagesCount(int $newMessagesCount)
    {
        $this->newMessagesCount = $newMessagesCount;

        return $this;
    }
}

