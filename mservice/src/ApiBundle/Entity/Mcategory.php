<?php

namespace ApiBundle\Entity;

/**
 * Mcategory
 */
class Mcategory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $categoryZh;

    /**
     * @var string
     */
    private $categoryEn;

    /**
     * @var string
     */
    private $categoryFr;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var boolean
     */
    private $isDefaut;

    /**
     * @var integer
     */
    private $score;

    /**
     * @var string
     */
    private $internalId;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryZh
     *
     * @param string $categoryZh
     *
     * @return Mcategory
     */
    public function setCategoryZh($categoryZh)
    {
        $this->categoryZh = $categoryZh;

        return $this;
    }

    /**
     * Get categoryZh
     *
     * @return string
     */
    public function getCategoryZh()
    {
        return $this->categoryZh;
    }

    /**
     * Set categoryEn
     *
     * @param string $categoryEn
     *
     * @return Mcategory
     */
    public function setCategoryEn($categoryEn)
    {
        $this->categoryEn = $categoryEn;

        return $this;
    }

    /**
     * Get categoryEn
     *
     * @return string
     */
    public function getCategoryEn()
    {
        return $this->categoryEn;
    }

    /**
     * Set categoryFr
     *
     * @param string $categoryFr
     *
     * @return Mcategory
     */
    public function setCategoryFr($categoryFr)
    {
        $this->categoryFr = $categoryFr;

        return $this;
    }

    /**
     * Get categoryFr
     *
     * @return string
     */
    public function getCategoryFr()
    {
        return $this->categoryFr;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Mcategory
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDefaut
     *
     * @param boolean $isDefaut
     *
     * @return Mcategory
     */
    public function setIsDefaut($isDefaut)
    {
        $this->isDefaut = $isDefaut;

        return $this;
    }

    /**
     * Get isDefaut
     *
     * @return boolean
     */
    public function getIsDefaut()
    {
        return $this->isDefaut;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Mcategory
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set internalId
     *
     * @param string $internalId
     *
     * @return Mcategory
     */
    public function setInternalId($internalId)
    {
        $this->internalId = $internalId;

        return $this;
    }

    /**
     * Get internalId
     *
     * @return string
     */
    public function getInternalId()
    {
        return $this->internalId;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Mcategory
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mcategory
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Mcategory
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}