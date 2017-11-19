<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/11/2017
 * Time: 11:25
 */

namespace AppBundle\Component\Helper;


class Pagination
{

    /** @var string|null */
    public $previousPageUrl;

    /** @var string|null */
    public $nextPageUrl;

    /** @var string|null */
    public $previousPageWording;

    /** @var string|null */
    public $nextPageWording;

    /**
     * @return null|string
     */
    public function getPreviousPageUrl(): ?string
    {
        return $this->previousPageUrl;
    }

    /**
     * @param null|string $previousPageUrl
     * @return Pagination
     */
    public function setPreviousPageUrl($previousPageUrl): self
    {
        $this->previousPageUrl = $previousPageUrl;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNextPageUrl(): ?string
    {
        return $this->nextPageUrl;
    }

    /**
     * @param null|string $nextPageUrl
     * @return Pagination
     */
    public function setNextPageUrl($nextPageUrl): self
    {
        $this->nextPageUrl = $nextPageUrl;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPreviousPageWording(): ?string
    {
        return $this->previousPageWording;
    }

    /**
     * @param null|string $previousPageWording
     * @return Pagination
     */
    public function setPreviousPageWording($previousPageWording): self
    {
        $this->previousPageWording = $previousPageWording;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNextPageWording(): ?string
    {
        return $this->nextPageWording;
    }

    /**
     * @param null|string $nextPageWording
     * @return Pagination
     */
    public function setNextPageWording($nextPageWording): self
    {
        $this->nextPageWording = $nextPageWording;
        return $this;
    }


}