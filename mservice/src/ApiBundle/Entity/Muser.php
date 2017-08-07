<?php

namespace ApiBundle\Entity;

/**
 * Muser
 */
class Muser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephone;
    
    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $wechat;

    /**
     * @var string
     */
    private $facebook;

    /**
     * @var string
     */
    private $instagram;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @var integer
     */
    private $postNumber;

    /**
     * @var integer
     */
    private $countryId;

    /**
     * @var integer
     */
    private $locationId;

    /**
     * @var string
     */
    private $skinColor;

    /**
     * @var integer
     */
    private $weight;

    /**
     * @var integer
     */
    private $height;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var integer
     */
    private $hourPrice;

    /**
     * @var string
     */
    private $hourPriceUnit;

    /**
     * @var integer
     */
    private $nightPrice;

    /**
     * @var string
     */
    private $nightPriceUnit;

    /**
     * @var string
     */
    private $shopAddress;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $translatedDescription;

    /**
     * @var boolean
     */
    private $isActive = true;

    /**
     * @var boolean
     */
    private $isDeleted = false;

    /**
     * @var boolean
     */
    private $isPremium = false;

    /**
     * @var boolean
     */
    private $isSingle = true;

    /**
     * @var boolean
     */
    private $isShop = false;

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
     * @var boolean
     */
    private $isAdmin = false;

    /**
     * @var boolean
     */
    private $isTest = false;  

    /**
     * @var boolean
     */
    private $isSynchronizedByCache;

    /**
     * @var boolean
     */
    private $isSynchronizedBySearch;

    /**
     * @var boolean
     */
    private $isFromOtherWeb;

    /**
     * @var string
     */
    private $otherWeb;

    /**
     * @var integer
     */
    private $viewNumber;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $internalToken;

    /**
     * @var string
     */
    private $externalToken;

    /**
     * @var string
     */
    private $internalId;

    /**
     * @var \DateTime
     */
    private $topTime;

    /**
     * @var \DateTime
     */
    private $lastSynchronizedFromOtherWebTime;

    /**
     * @var \DateTime
     */
    private $paymentExpiredTime;

    /**
     * @var string
     */
    private $allowedIp;

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
     * Set username
     *
     * @param string $username
     *
     * @return Muser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Muser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Muser
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set wechat
     *
     * @param string $wechat
     *
     * @return Muser
     */
    public function setWechat($wechat)
    {
        $this->wechat = $wechat;

        return $this;
    }

    /**
     * Get wechat
     *
     * @return string
     */
    public function getWechat()
    {
        return $this->wechat;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Muser
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     *
     * @return Muser
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Muser
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     *
     * @return Muser
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Muser
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Muser
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postNumber
     *
     * @param integer $postNumber
     *
     * @return Muser
     */
    public function setPostNumber($postNumber)
    {
        $this->postNumber = $postNumber;

        return $this;
    }

    /**
     * Get postNumber
     *
     * @return integer
     */
    public function getPostNumber()
    {
        return $this->postNumber;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     *
     * @return Muser
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return Muser
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set skinColor
     *
     * @param string $skinColor
     *
     * @return Muser
     */
    public function setSkinColor($skinColor)
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    /**
     * Get skinColor
     *
     * @return string
     */
    public function getSkinColor()
    {
        return $this->skinColor;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Muser
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Muser
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Muser
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set hourPrice
     *
     * @param integer $hourPrice
     *
     * @return Muser
     */
    public function setHourPrice($hourPrice)
    {
        $this->hourPrice = $hourPrice;

        return $this;
    }

    /**
     * Get hourPrice
     *
     * @return integer
     */
    public function getHourPrice()
    {
        return $this->hourPrice;
    }

    /**
     * Set hourPriceUnit
     *
     * @param string $hourPriceUnit
     *
     * @return Muser
     */
    public function setHourPriceUnit($hourPriceUnit)
    {
        $this->hourPriceUnit = $hourPriceUnit;

        return $this;
    }

    /**
     * Get hourPriceUnit
     *
     * @return string
     */
    public function getHourPriceUnit()
    {
        return $this->hourPriceUnit;
    }

    /**
     * Set nightPrice
     *
     * @param integer $nightPrice
     *
     * @return Muser
     */
    public function setNightPrice($nightPrice)
    {
        $this->nightPrice = $nightPrice;

        return $this;
    }

    /**
     * Get nightPrice
     *
     * @return integer
     */
    public function getNightPrice()
    {
        return $this->nightPrice;
    }

    /**
     * Set nightPriceUnit
     *
     * @param string $nightPriceUnit
     *
     * @return Muser
     */
    public function setNightPriceUnit($nightPriceUnit)
    {
        $this->nightPriceUnit = $nightPriceUnit;

        return $this;
    }

    /**
     * Get nightPriceUnit
     *
     * @return string
     */
    public function getNightPriceUnit()
    {
        return $this->nightPriceUnit;
    }

    /**
     * Set shopAddress
     *
     * @param string $shopAddress
     *
     * @return Muser
     */
    public function setShopAddress($shopAddress)
    {
        $this->shopAddress = $shopAddress;

        return $this;
    }

    /**
     * Get shopAddress
     *
     * @return string
     */
    public function getShopAddress()
    {
        return $this->shopAddress;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Muser
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set translatedDescription
     *
     * @param string $translatedDescription
     *
     * @return Muser
     */
    public function setTranslatedDescription($translatedDescription)
    {
        $this->translatedDescription = $translatedDescription;

        return $this;
    }

    /**
     * Get translatedDescription
     *
     * @return string
     */
    public function getTranslatedDescription()
    {
        return $this->translatedDescription;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Muser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Muser
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
     * Set isPremium
     *
     * @param boolean $isPremium
     *
     * @return Muser
     */
    public function setIsPremium($isPremium)
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    /**
     * Get isPremium
     *
     * @return boolean
     */
    public function getIsPremium()
    {
        return $this->isPremium;
    }

    /**
     * Set isSingle
     *
     * @param boolean $isSingle
     *
     * @return Muser
     */
    public function setIsSingle($isSingle)
    {
        $this->isSingle = $isSingle;

        return $this;
    }

    /**
     * Get isSingle
     *
     * @return boolean
     */
    public function getIsSingle()
    {
        return $this->isSingle;
    }

    /**
     * Set isShop
     *
     * @param boolean $isShop
     *
     * @return Muser
     */
    public function setIsShop($isShop)
    {
        $this->isShop = $isShop;

        return $this;
    }

    /**
     * Get isShop
     *
     * @return boolean
     */
    public function getIsShop()
    {
        return $this->isShop;
    }

    /**
     * Set isZh
     *
     * @param boolean $isZh
     *
     * @return Muser
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
     * @return Muser
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
     * @return Muser
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
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Muser
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set isSynchronizedByCache
     *
     * @param boolean $isSynchronizedByCache
     *
     * @return Muser
     */
    public function setIsSynchronizedByCache($isSynchronizedByCache)
    {
        $this->isSynchronizedByCache = $isSynchronizedByCache;

        return $this;
    }

    /**
     * Get isSynchronizedByCache
     *
     * @return boolean
     */
    public function getIsSynchronizedByCache()
    {
        return $this->isSynchronizedByCache;
    }

    /**
     * Set isSynchronizedBySearch
     *
     * @param boolean $isSynchronizedBySearch
     *
     * @return Muser
     */
    public function setIsSynchronizedBySearch($isSynchronizedBySearch)
    {
        $this->isSynchronizedBySearch = $isSynchronizedBySearch;

        return $this;
    }

    /**
     * Get isSynchronizedBySearch
     *
     * @return boolean
     */
    public function getIsSynchronizedBySearch()
    {
        return $this->isSynchronizedBySearch;
    }

    /**
     * Set isFromOtherWeb
     *
     * @param boolean $isFromOtherWeb
     *
     * @return Muser
     */
    public function setIsFromOtherWeb($isFromOtherWeb)
    {
        $this->isFromOtherWeb = $isFromOtherWeb;

        return $this;
    }

    /**
     * Get isFromOtherWeb
     *
     * @return boolean
     */
    public function getIsFromOtherWeb()
    {
        return $this->isFromOtherWeb;
    }

    /**
     * Set otherWeb
     *
     * @param string $otherWeb
     *
     * @return Muser
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
     * Set viewNumber
     *
     * @param integer $viewNumber
     *
     * @return Muser
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Muser
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
     * Set token
     *
     * @param string $token
     *
     * @return Muser
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
     * Set internalToken
     *
     * @param string $internalToken
     *
     * @return Muser
     */
    public function setInternalToken($internalToken)
    {
        $this->internalToken = $internalToken;

        return $this;
    }

    /**
     * Get internalToken
     *
     * @return string
     */
    public function getInternalToken()
    {
        return $this->internalToken;
    }

    /**
     * Set externalToken
     *
     * @param string $externalToken
     *
     * @return Muser
     */
    public function setExternalToken($externalToken)
    {
        $this->externalToken = $externalToken;

        return $this;
    }

    /**
     * Get externalToken
     *
     * @return string
     */
    public function getExternalToken()
    {
        return $this->externalToken;
    }

    /**
     * Set internalId
     *
     * @param string $internalId
     *
     * @return Muser
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
     * Set topTime
     *
     * @param \DateTime $topTime
     *
     * @return Muser
     */
    public function setTopTime($topTime)
    {
        $this->topTime = $topTime;

        return $this;
    }

    /**
     * Get topTime
     *
     * @return \DateTime
     */
    public function getTopTime()
    {
        return $this->topTime;
    }

    /**
     * Set lastSynchronizedFromOtherWebTime
     *
     * @param \DateTime $lastSynchronizedFromOtherWebTime
     *
     * @return Muser
     */
    public function setLastSynchronizedFromOtherWebTime($lastSynchronizedFromOtherWebTime)
    {
        $this->lastSynchronizedFromOtherWebTime = $lastSynchronizedFromOtherWebTime;

        return $this;
    }

    /**
     * Get lastSynchronizedFromOtherWebTime
     *
     * @return \DateTime
     */
    public function getLastSynchronizedFromOtherWebTime()
    {
        return $this->lastSynchronizedFromOtherWebTime;
    }

    /**
     * Set paymentExpiredTime
     *
     * @param \DateTime $paymentExpiredTime
     *
     * @return Muser
     */
    public function setPaymentExpiredTime($paymentExpiredTime)
    {
        $this->paymentExpiredTime = $paymentExpiredTime;

        return $this;
    }

    /**
     * Get paymentExpiredTime
     *
     * @return \DateTime
     */
    public function getPaymentExpiredTime()
    {
        return $this->paymentExpiredTime;
    }

    /**
     * Set allowedIp
     *
     * @param string $allowedIp
     *
     * @return Muser
     */
    public function setAllowedIp($allowedIp)
    {
        $this->allowedIp = $allowedIp;

        return $this;
    }

    /**
     * Get allowedIp
     *
     * @return string
     */
    public function getAllowedIp()
    {
        return $this->allowedIp;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Muser
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
     * @return Muser
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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Muser
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set isTest
     *
     * @param boolean $isTest
     *
     * @return Muser
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;

        return $this;
    }

    /**
     * Get isTest
     *
     * @return boolean
     */
    public function getIsTest()
    {
        return $this->isTest;
    }
    /**
     * @var string
     */
    private $shopName;

    /**
     * @var string
     */
    private $otherWebReference;

    /**
     * @var int
     */
    private $draftId;

    /**
     * @var \ApiBundle\Entity\Mcountry
     */
    private $mcountry;

    /**
     * @var \ApiBundle\Entity\Mlocation
     */
    private $mlocation;


    /**
     * Set shopName
     *
     * @param string $shopName
     *
     * @return Muser
     */
    public function setShopName($shopName)
    {
        $this->shopName = $shopName;

        return $this;
    }

    /**
     * Get shopName
     *
     * @return string
     */
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * Set otherWebReference
     *
     * @param string $otherWebReference
     *
     * @return Muser
     */
    public function setOtherWebReference($otherWebReference)
    {
        $this->otherWebReference = $otherWebReference;

        return $this;
    }

    /**
     * Get otherWebReference
     *
     * @return string
     */
    public function getOtherWebReference()
    {
        return $this->otherWebReference;
    }

    /**
     * Set draftId
     *
     * @param $draftId
     *
     * @return Muser
     */
    public function setDraftId($draftId)
    {
        $this->draftId = $draftId;

        return $this;
    }

    /**
     * Get draftId
     *
     * @return \int
     */
    public function getDraftId()
    {
        return $this->draftId;
    }

    /**
     * Set mcountry
     *
     * @param \ApiBundle\Entity\Mcountry $mcountry
     *
     * @return Muser
     */
    public function setMcountry(\ApiBundle\Entity\Mcountry $mcountry = null)
    {
        $this->mcountry = $mcountry;

        return $this;
    }

    /**
     * Get mcountry
     *
     * @return \ApiBundle\Entity\Mcountry
     */
    public function getMcountry()
    {
        return $this->mcountry;
    }

    /**
     * Set mlocation
     *
     * @param \ApiBundle\Entity\Mlocation $mlocation
     *
     * @return Muser
     */
    public function setMlocation(\ApiBundle\Entity\Mlocation $mlocation = null)
    {
        $this->mlocation = $mlocation;

        return $this;
    }

    /**
     * Get mlocation
     *
     * @return \ApiBundle\Entity\Mlocation
     */
    public function getMlocation()
    {
        return $this->mlocation;
    }
    /**
     * @var \ApiBundle\Entity\Mdraft
     */
    private $draft;


    /**
     * Set draft
     *
     * @param \ApiBundle\Entity\Mdraft $draft
     *
     * @return Muser
     */
    public function setDraft(\ApiBundle\Entity\Mdraft $draft = null)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return \ApiBundle\Entity\Mdraft
     */
    public function getDraft()
    {
        return $this->draft;
    }
}
