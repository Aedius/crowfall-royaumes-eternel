<?php

namespace AppBundle\Entity;

use AppBundle\Component\Helper\StringHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use \Doctrine\Common\Collections\Collection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @Vich\Uploadable
 */
class Category
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var Collection|Article[]
     *
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categoryList")
     */
    private $articleList;

    /**
     * @var Article[]
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="masterCategory")
     */
    private $masterArticleList;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->articleList = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug(string $slug): Category
    {
        $this->slug = StringHelper::slugify($slug);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)($this->getName() ?? '');
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName():?string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription():?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Category
     */
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Category
     */
    public function setUpdatedAt(\DateTime $updatedAt): Category
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param Article $article
     */
    public function addArticle(Article $article): void
    {
        if ($this->articleList->contains($article)) {
            return;
        }
        $this->articleList->add($article);
        $article->addCategory($this);
    }

    /**
     * @param Article $article
     */
    public function removeArticle(Article $article): void
    {
        if (!$this->articleList->contains($article)) {
            return;
        }
        $this->articleList->removeElement($article);
        $article->removeCategory($this);
    }

    /**
     * @return Article[]|Collection|\Traversable
     */
    public function getArticleList(): \Traversable
    {
        return $this->articleList;
    }

    /**
     * @param Article[]|Collection $articleList
     * @return Category
     */
    public function setArticleList($articleList): self
    {
        $this->articleList = $articleList;
        return $this;
    }

    /**
     * @return Article[]|Collection|\Traversable
     */
    public function getMasterArticleList(): ?\Traversable
    {
        return $this->masterArticleList;
    }

    /**
     * @param Article[]|\Traversable $masterArticleList
     * @return Category
     */
    public function setMasterArticleList(\Traversable $masterArticleList): self
    {
        $this->masterArticleList = $masterArticleList;

        return $this;
    }

}

