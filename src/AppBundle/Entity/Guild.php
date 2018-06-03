<?php

namespace AppBundle\Entity;

use AppBundle\Component\Helper\StringHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Article
 *
 * @ORM\Table(name="guilde")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GuildRepository")
 * @Vich\Uploadable
 */
class Guild
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=25, unique=true)*
     * @Assert\NotBlank()
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User",cascade={"persist"})
     */
    private $guildMaster;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="guild")
     */
    private $memberList;

    public function __construct()
    {
        $this->memberList = new ArrayCollection();
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Article
     */
    public function setPublished(bool $published): Article
    {
        if ($published && !$this->getPublished()) {
            $this->setPublishedAt(new \DateTime());
        }
        $this->published = $published;

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
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Article
     */
    public function setPublishedAt(\DateTime $publishedAt): Article
    {
        $this->publishedAt = $publishedAt;

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
     * @return string
     */
    public function __toString(): string
    {
        $titre = $this->getTitre();
        return \is_string($titre) ? $titre : '';
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
        $this->setSlug(StringHelper::slugify($titre));

        return $this;
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
    public function getUpdatedAt(): ?\DateTime
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
     * @return Category[]|Collection
     */
    public function getCategoryList()
    {
        return $this->categoryList;
    }

    /**
     * @param Category[]|Collection|\Traversable $categoryList
     * @return Article
     */
    public function setCategoryList(\Traversable $categoryList): self
    {
        $this->categoryList = $categoryList;
        return $this;
    }

    /**
     * @return Category
     */
    public function getMasterCategory(): ?Category
    {
        return $this->masterCategory;
    }

    /**
     * @param Category $masterCategory
     * @return Article
     */
    public function setMasterCategory(Category $masterCategory): self
    {
        $this->masterCategory = $masterCategory;
        return $this;
    }

    /**
     * @return array|Collection|\Traversable
     */
    public function getCommentList(): ?\Traversable
    {
        return $this->commentList;
    }
}

