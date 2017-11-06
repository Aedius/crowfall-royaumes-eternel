<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @Vich\Uploadable
 */
class Article
{
    /**
     * @var \Doctrine\Common\Collections\Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="articleList")
     * @ORM\JoinTable(
     *  name="article_category",
     *  joinColumns={
     *      @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $categoryList;
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titre;
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)*
     * @Assert\NotBlank()
     */
    private $slug;
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $image;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = false;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishedAt", type="datetime", nullable=true)
     * @Assert\Type("\DateTime")
     */
    private $publishedAt;
    /**
     * @var Version
     *
     * @ORM\ManyToOne(targetEntity="Version", inversedBy="articleList")
     */
    private $version;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articleList")
     * @Assert\NotBlank()
     */
    private $author;

    /**
     * @var bool
     *
     * @ORM\Column(name="home", type="boolean")
     */
    private $home = true;


    public function __construct()
    {
        $this->categoryList = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isHome(): bool
    {
        return $this->home;
    }

    /**
     * @param bool $home
     */
    public function setHome(bool $home): void
    {
        $this->home = $home;
    }

    /**
     * @return Version
     */
    public function getVersion(): ?version
    {
        return $this->version;
    }

    /**
     * @param Version $version
     *
     * @throws \InvalidArgumentException
     */
    public function setVersion($version): void
    {
        if ($version === null) {
            if ($this->version !== null) {
                $this->version->getArticleList()->removeElement($this);
            }
            $this->version = null;
        } else {
            if (!$version instanceof Version) {
                throw new \InvalidArgumentException('$version must be null or instance of Version');
            }
            if ($this->version !== null) {
                $this->version->getArticleList()->removeElement($this);
            }
            $this->version = $version;
            $version->getArticleList()->add($this);
        }
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug():?string
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug(string $slug): Article
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent():?string
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content): Article
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Article
     */
    public function setImageFile(File $image = null): Article
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Article
     */
    public function setImage($image): Article
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished(): bool
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Article
     */
    public function setPublished($published): Article
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Article
     */
    public function setPublishedAt($publishedAt): Article
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        $titre = $this->getTitre();
        return is_string($titre) ? $titre : '';
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre(string $titre): Article
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @throws \InvalidArgumentException
     */
    public function setAuthor($author): void
    {
        if ($author === null) {
            if ($this->author !== null) {
                $this->author->getArticleList()->removeElement($this);
            }
            $this->author = null;
        } else {
            if (!$author instanceof User) {
                throw new \InvalidArgumentException('$author must be null or instance of User');
            }
            if ($this->author !== null) {
                $this->author->getArticleList()->removeElement($this);
            }
            $this->author = $author;
            $author->getArticleList()->add($this);
        }
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Article
     */
    public function setUpdatedAt(\DateTime $updatedAt): Article
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        if ($this->categoryList->contains($category)) {
            return;
        }
        $this->categoryList->add($category);
        $category->addArticle($this);
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category): void
    {
        if (!$this->categoryList->contains($category)) {
            return;
        }
        $this->categoryList->removeElement($category);
        $category->removeArticle($this);
    }

    /**
     * @return Category[]|\Doctrine\Common\Collections\Collection
     */
    public function getCategoryList()
    {
        return $this->categoryList;
    }

    /**
     * @param Category[]|\Doctrine\Common\Collections\Collection $categoryList
     * @return Article
     */
    public function setCategoryList($categoryList)
    {
        $this->categoryList = $categoryList;
        return $this;
    }

}

