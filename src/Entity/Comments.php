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

use App\Repository\CommentsRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
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
    private $authorUsername;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     */
    private $authorEmail;

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
     * @var Articles|null
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $articles;

    /**
     * Gets id of a comment.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the username of a comment author.
     * @return string|null
     */
    public function getAuthorUsername(): ?string
    {
        return $this->authorUsername;
    }

    /**
     * Sets the comment author username.
     * @param string $authorUsername
     * @return $this
     */
    public function setAuthorUsername(string $authorUsername): self
    {
        $this->authorUsername = $authorUsername;

        return $this;
    }

    /**
     * Gets the comment author email.
     * @return string|null
     */
    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    /**
     * Sets the comment author email.
     * @param string $authorEmail
     * @return $this
     */
    public function setAuthorEmail(string $authorEmail): self
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Gets the main text of a comment.
     * @return string|null
     */
    public function getMainText(): ?string
    {
        return $this->mainText;
    }

    /**
     * Sets the main text of a comment.
     * @param string $mainText
     * @return $this
     */
    public function setMainText(string $mainText): self
    {
        $this->mainText = $mainText;

        return $this;
    }

    /**
     * Gets the date and hour of a comment creation.
     * @return DateTimeInterface|null
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Sets the date and hour of a comment creation.
     * @param DateTimeInterface $created
     * @return $this
     */
    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the articles.
     * @return Articles|null
     */
    public function getArticles(): ?Articles
    {
        return $this->articles;
    }

    /**
     * Sets the articles.
     * @param Articles|null $articles
     * @return $this
     */
    public function setArticles(?Articles $articles): self
    {
        $this->articles = $articles;

        return $this;
    }
}
