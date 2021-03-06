<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Article[]
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="author")
     */
    private $articleList;

    /**
     * @var Comment[]
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="author")
     */
    private $commentList;

    public function __construct()
    {
        parent::__construct();
        $this->articleList = new ArrayCollection();
        // your own logic
    }

    /**
     * @return array|Collection|\Traversable
     */
    public function getArticleList(): ?\Traversable
    {
        return $this->articleList;
    }

    /**
     * @return array|Collection|\Traversable
     */
    public function getCommentList(): ?\Traversable
    {
        return $this->commentList;
    }

}