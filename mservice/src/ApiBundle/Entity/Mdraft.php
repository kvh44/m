<?php

namespace ApiBundle\Entity;

/**
 * Mdraft
 */
class Mdraft
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $otherWeb;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $linkId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $isNewContent;

    /**
     * @var boolean
     */
    private $isUpdatedContent;

    /**
     * @var boolean
     */
    private $isRead;

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
    private $slug;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set otherWeb
     *
     * @param string $otherWeb
     *
     * @return Mdraft
     */
    public function setOtherWeb($otherWeb)
    {
        $this->otherWeb = $otherWeb;

        return $this;
    }

    /**
     * Get otherWeb
     *
     * @return string
     */
    public function getOtherWeb()
    {
        return $this->otherWeb;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Mdraft
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set linkId
     *
     * @param string $linkId
     *
     * @return Mdraft
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;

        return $this;
    }

    /**
     * Get linkId
     *
     * @return string
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Mdraft
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
     * Set isNewContent
     *
     * @param boolean $isNewContent
     *
     * @return Mdraft
     */
    public function setIsNewContent($isNewContent)
    {
        $this->isNewContent = $isNewContent;

        return $this;
    }

    /**
     * Get isNewContent
     *
     * @return boolean
     */
    public function getIsNewContent()
    {
        return $this->isNewContent;
    }

    /**
     * Set isUpdatedContent
     *
     * @param boolean $isUpdatedContent
     *
     * @return Mdraft
     */
    public function setIsUpdatedContent($isUpdatedContent)
    {
        $this->isUpdatedContent = $isUpdatedContent;

        return $this;
    }

    /**
     * Get isUpdatedContent
     *
     * @return boolean
     */
    public function getIsUpdatedContent()
    {
        return $this->isUpdatedContent;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return Mdraft
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Mdraft
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
     * @return Mdraft
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Mdraft
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
     * Set internalId
     *
     * @param string $internalId
     *
     * @return Mdraft
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
     * @return Mdraft
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
     * @return Mdraft
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
