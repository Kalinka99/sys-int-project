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

use App\Repository\ArticlesRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     */
    private $title;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     * )
     */
    private $mainText;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var Users|null
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @var Categories|null
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $categories;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="articles")
     */
    private $comments;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="articles")
     */
    private $tags;


    /**
     * Articles constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Returns id of an article.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Gets title of an article.
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * Sets the title of an article.
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    /**
     * Gets the main text of an article.
     * @return string|null
     */
    public function getMainText(): ?string
    {
        return $this->mainText;
    }


    /**
     * Sets the main text of an article.
     * @param string $mainText
     * @return $this
     */
    public function setMainText(string $mainText): self
    {
        $this->mainText = $mainText;

        return $this;
    }

    /**
     * Gets the date and hour of creating an article.
     * @return DateTimeInterface|null
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }


    /**
     * Sets the date and hour of creating an article.
     * @param DateTimeInterface $created
     * @return $this
     */
    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the user - author of an article.
     * @return Users|null
     */
    public function getUsers(): ?Users
    {
        return $this->users;
    }

    /**
     * Sets the user - author of an article.
     * @param Users|null $users
     * @return $this
     */
    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Gets the category of the article.
     * @return Categories|null
     */
    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    /**
     * Sets the category for an article.
     * @param Categories|null $categories
     * @return $this
     */
    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Gets the comments for the article.
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Adds a comment to the article.
     * @param Comments $comment
     * @return $this
     */
    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticles($this);
        }

        return $this;
    }

    /**
     * Removes a comment for an article.
     * @param Comments $comment
     * @return $this
     */
    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getArticles() === $this) {
                $comment->setArticles(null);
            }
        }

        return $this;
    }

    /**
     * Converts to string so that no errors occur.
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return Articles::class;
    }

    /**
     * Gets tags for an article.
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Adds tags to an article.
     * @param Tags $tag
     * @return $this
     */
    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Removes tags from an article.
     * @param Tags $tag
     * @return $this
     */
    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

}
