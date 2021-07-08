<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="categories")
     */
    private $articles;

    /**
     * Categories constructor.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Gets id of a category.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of a category.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of a category.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets collection of articles.
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Adds a new article to the category.
     * @param Articles $article
     * @return $this
     */
    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategories($this);
        }

        return $this;
    }

    /**
     * Sets category to null before removing an article.
     * @param Articles $article
     * @return $this
     */
    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            if ($article->getCategories() === $this) {
                $article->setCategories(null);
            }
        }

        return $this;
    }

    /**
     * Converts to string so that no error occurs.
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return Categories::class;
    }
}
