<?php

namespace ApiBundle\Entity;

/**
 * Mmessage
 */
class Mmessage
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
     * @var integer
     */
    private $categoryId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $isZh;

    /**
     * @var boolean
     */
    private $isFr;

    /**
     * @var boolean
     */
    private $isEn;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var integer
     */
    private $deletedByUserId;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \ApiBundle\Entity\Muser
     */
    private $user;


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
     * @return Mmessage
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
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Mmessage
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Mmessage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isZh
     *
     * @param boolean $isZh
     *
     * @return Mmessage
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
     * Set isFr
     *
     * @param boolean $isFr
     *
     * @return Mmessage
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
     * Set isEn
     *
     * @param boolean $isEn
     *
     * @return Mmessage
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Mmessage
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
     * Set deletedByUserId
     *
     * @param integer $deletedByUserId
     *
     * @return Mmessage
     */
    public function setDeletedByUserId($deletedByUserId)
    {
        $this->deletedByUserId = $deletedByUserId;

        return $this;
    }

    /**
     * Get deletedByUserId
     *
     * @return integer
     */
    public function getDeletedByUserId()
    {
        return $this->deletedByUserId;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mmessage
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
     * @return Mmessage
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
     * Set user
     *
     * @param \ApiBundle\Entity\Muser $user
     *
     * @return Mmessage
     */
    public function setUser(\ApiBundle\Entity\Muser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\Muser
     */
    public function getUser()
    {
        return $this->user;
    }
}

