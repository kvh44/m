<?php

namespace ApiBundle\Entity;

/**
 * Mphoto
 */
class Mphoto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $photoType;

    /**
     * @var integer
     */
    private $postId;

    /**
     * @var string
     */
    private $photoOrigin;

    /**
     * @var string
     */
    private $photoMedium;

    /**
     * @var string
     */
    private $photoSmall;
    
    /**
     * @var string
     */
    private $photoIcon;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $viewNumber;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var integer
     */
    private $deletedByUserId;

    /**
     * @var string
     */
    private $internalId;

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
     * Get user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set photoType
     *
     * @param string $photoType
     *
     * @return Mphoto
     */
    public function setPhotoType($photoType)
    {
        $this->photoType = $photoType;

        return $this;
    }

    /**
     * Get photoType
     *
     * @return string
     */
    public function getPhotoType()
    {
        return $this->photoType;
    }

    /**
     * Set postId
     *
     * @param integer $postId
     *
     * @return Mphoto
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set photoOrigin
     *
     * @param string $photoOrigin
     *
     * @return Mphoto
     */
    public function setPhotoOrigin($photoOrigin)
    {
        $this->photoOrigin = $photoOrigin;

        return $this;
    }

    /**
     * Get photoOrigin
     *
     * @return string
     */
    public function getPhotoOrigin()
    {
        return $this->photoOrigin;
    }

    /**
     * Set photoMedium
     *
     * @param string $photoMedium
     *
     * @return Mphoto
     */
    public function setPhotoMedium($photoMedium)
    {
        $this->photoMedium = $photoMedium;

        return $this;
    }

    /**
     * Get photoMedium
     *
     * @return string
     */
    public function getPhotoMedium()
    {
        return $this->photoMedium;
    }

    /**
     * Set photoSmall
     *
     * @param string $photoSmall
     *
     * @return Mphoto
     */
    public function setPhotoSmall($photoSmall)
    {
        $this->photoSmall = $photoSmall;

        return $this;
    }

    /**
     * Get photoSmall
     *
     * @return string
     */
    public function getPhotoSmall()
    {
        return $this->photoSmall;
    }
    
    /**
     * Set photoIcon
     *
     * @param string $photoIcon
     *
     * @return Mphoto
     */
    public function setPhotoIcon($photoIcon)
    {
        $this->photoIcon = $photoIcon;

        return $this;
    }
    
    /**
     * Get photoIcon
     *
     * @return string
     */
    public function getPhotoIcon()
    {
        return $this->photoIcon;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Mphoto
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set viewNumber
     *
     * @param integer $viewNumber
     *
     * @return Mphoto
     */
    public function setViewNumber($viewNumber)
    {
        $this->viewNumber = $viewNumber;

        return $this;
    }

    /**
     * Get viewNumber
     *
     * @return integer
     */
    public function getViewNumber()
    {
        return $this->viewNumber;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Mphoto
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
     * @return Mphoto
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
     * Set internalId
     *
     * @param string $internalId
     *
     * @return Mphoto
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mphoto
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
     * @return Mphoto
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
     * @return Mphoto
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
