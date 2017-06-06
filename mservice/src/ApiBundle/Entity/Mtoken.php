<?php

namespace ApiBundle\Entity;

/**
 * Mtoken
 */
class Mtoken
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $tokenExpiredTime;

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
     * Set token
     *
     * @param string $token
     *
     * @return Mtoken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Mtoken
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Mtoken
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set tokenExpiredTime
     *
     * @param \DateTime $tokenExpiredTime
     *
     * @return Mtoken
     */
    public function setTokenExpiredTime($tokenExpiredTime)
    {
        $this->tokenExpiredTime = $tokenExpiredTime;

        return $this;
    }

    /**
     * Get tokenExpiredTime
     *
     * @return \DateTime
     */
    public function getTokenExpiredTime()
    {
        return $this->tokenExpiredTime;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mtoken
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
     * @return Mtoken
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

