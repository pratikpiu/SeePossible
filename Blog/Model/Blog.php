<?php

namespace SeePossible\Blog\Model;

use SeePossible\Blog\Api\Data\BlogInterface;
use Magento\Framework\Model\AbstractModel;
use SeePossible\Blog\Model\ResourceModel;

/**
 * Class Blog
 * @package SeePossible\Blog\Model
 */
class Blog extends AbstractModel implements BlogInterface
{
    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 2;

    /**
     * Model construct that should be used for object initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Blog::class);
    }

    /**
     * Prepare statuses.
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }


    /**
     * Get ID
     *
     * @return int
     */
    public function getPostId()
    {
        return $this->getData(self::POST_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get Discription
     *
     * @return string
     */
    public function getDiscription()
    {
        return $this->getData(self::DISCRIPTION);
    }

    /**
     * Get Is Active
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Get Create At
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get Updated At
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Get Id
     *
     * @param $postId
     * @return Blog
     */
    public function setPostId($postId)
    {
        return $this->setData(self::POST_ID, $postId);
    }

    /**
     * Set Title
     *
     * @param $title
     * @return Blog
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set Discription
     *
     * @param $discription
     * @return Blog
     */
    public function setDiscription($discription)
    {
        return $this->setData(self::DISCRIPTION, $discription);
    }

    /**
     * Set Is Active
     *
     * @param $isActive
     * @return Blog
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set Created At
     *
     * @param string $createAt
     * @return $this
     */
    public function setCreatedAt($createAt)
    {
        return $this->setData(self::CREATED_AT, $createAt);
    }

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
