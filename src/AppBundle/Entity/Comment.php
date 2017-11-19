<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="commentList",cascade={"persist"})
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commentList",cascade={"persist"})
     */
    private $author;


    /**
     * Get id
     *
     * @return int
     */
    public function getId():?int
    {
        return $this->id;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Comment
     */
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get article
     *
     * @return Article
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     *
     * @return Comment
     *
     * @throws \InvalidArgumentException
     */
    public function setArticle(Article $article): self
    {
        if ($article === null) {
            if ($this->article !== null) {
                $this->article->getCommentList()->removeElement($this);
            }
            $this->article = null;
        } else {
            if (!$article instanceof Article) {
                throw new \InvalidArgumentException('$article must be null or instance of Article');
            }
            if ($this->article !== null) {
                $this->article->getCommentList()->removeElement($this);
            }
            $this->article = $article;
            $article->getCommentList()->add($this);
        }
        return $this;
    }

    /**
     * Get author
     *
     * @return User|null
     */
    public function getAuthor():?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Comment
     *
     * @throws \InvalidArgumentException
     */
    public function setAuthor($author): self
    {
        if ($author === null) {
            if ($this->author !== null) {
                $this->author->getCommentList()->removeElement($this);
            }
            $this->author = null;
        } else {
            if (!$author instanceof User) {
                throw new \InvalidArgumentException('$author must be null or instance of User');
            }
            if ($this->author !== null) {
                $this->author->getCommentList()->removeElement($this);
            }
            $this->author = $author;
            $author->getCommentList()->add($this);
        }
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

}

