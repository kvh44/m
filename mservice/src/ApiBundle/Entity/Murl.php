<?php

namespace ApiBundle\Entity;

/**
 * Murl
 */
class Murl
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $internalId;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $url;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var boolean
     */
    private $isDefaut;

    /**
     * @var boolean
     */
    private $isNormal;

    /**
     * @var integer
     */
    private $score;

    /**
     * @var boolean
     */
    private $isZh;

    /**
     * @var boolean
     */
    private $isEn;

    /**
     * @var boolean
     */
    private $isFr;

    /**
     * @var \DateTime
     */
    private $firstRead;

    /**
     * @var \DateTime
     */
    private $lastRead;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \ApiBundle\Entity\Mcategory
     */
    private $category;


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
     * Set internalId
     *
     * @param string $internalId
     *
     * @return Murl
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
     * Set domain
     *
     * @param string $domain
     *
     * @return Murl
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Murl
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Murl
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
     * @return Murl
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
     * Set isNormal
     *
     * @param boolean $isNormal
     *
     * @return Murl
     */
    public function setIsNormal($isNormal)
    {
        $this->isNormal = $isNormal;

        return $this;
    }

    /**
     * Get isNormal
     *
     * @return boolean
     */
    public function getIsNormal()
    {
        return $this->isNormal;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Murl
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
     * Set isZh
     *
     * @param boolean $isZh
     *
     * @return Murl
     */
    public function setIsZh($isZh)
    {
        $this->isZh = $isZh;

        return $this;
    }

    /**
     * Get isZh
     *
     * @return boolean
     */
    public function getIsZh()
    {
        return $this->isZh;
    }

    /**
     * Set isEn
     *
     * @param boolean $isEn
     *
     * @return Murl
     */
    public function setIsEn($isEn)
    {
        $this->isEn = $isEn;

        return $this;
    }

    /**
     * Get isEn
     *
     * @return boolean
     */
    public function getIsEn()
    {
        return $this->isEn;
    }

    /**
     * Set isFr
     *
     * @param boolean $isFr
     *
     * @return Murl
     */
    public function setIsFr($isFr)
    {
        $this->isFr = $isFr;

        return $this;
    }

    /**
     * Get isFr
     *
     * @return boolean
     */
    public function getIsFr()
    {
        return $this->isFr;
    }

    /**
     * Set firstRead
     *
     * @param \DateTime $firstRead
     *
     * @return Murl
     */
    public function setFirstRead($firstRead)
    {
        $this->firstRead = $firstRead;

        return $this;
    }

    /**
     * Get firstRead
     *
     * @return \DateTime
     */
    public function getFirstRead()
    {
        return $this->firstRead;
    }

    /**
     * Set lastRead
     *
     * @param \DateTime $lastRead
     *
     * @return Murl
     */
    public function setLastRead($lastRead)
    {
        $this->lastRead = $lastRead;

        return $this;
    }

    /**
     * Get lastRead
     *
     * @return \DateTime
     */
    public function getLastRead()
    {
        return $this->lastRead;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Murl
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
     * @return Murl
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

    /**
     * Set category
     *
     * @param \ApiBundle\Entity\Mcategory $category
     *
     * @return Murl
     */
    public function setCategory(\ApiBundle\Entity\Mcategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ApiBundle\Entity\Mcategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
