<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Kalina Muchowicz <https://github.com/Kalinka99>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users implements UserInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     */
    private $email;

    /**
     * @var mixed
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     */
    private $password;

    /**
     * @var Articles|null
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="users")
     */
    private $articles;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $about;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $contact;

    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Gets the user id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the user email.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the user email.
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * Gets the user roles.
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Sets the user roles.
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Gets the user password.
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Sets the user password.
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * If you store any temporary, sensitive data on the user, clear it here.
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // $this->plainPassword = null;
    }

    /**
     * Gets the collection of articles.
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Adds an article by the user.
     * @param Articles $article
     * @return $this
     */
    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUsers($this);
        }

        return $this;
    }

    /**
     * Removes the article user before removing an article.
     * @param Articles $article
     * @return $this
     */
    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            if ($article->getUsers() === $this) {
                $article->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * Gets the content of the about section.
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * Sets the content of the about section.
     * @param string $about
     * @return $this
     */
    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Gets the content of the contact section.
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->contact;
    }

    /**
     * Sets the content of the contact section.
     * @param string $contact
     * @return $this
     */
    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Converts to string for no errors.
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return Users::class;
    }
}
