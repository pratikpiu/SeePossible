<?php

namespace SeePossible\Blog\Model\BlogLink;

use Magento\Framework\Model\AbstractModel;
use SeePossible\Blog\Api\Data\BlogLinkInterface;

/**
 * @codeCoverageIgnore
 */
class Link extends AbstractModel implements BlogLinkInterface
{
    const LINK_ID = 'link_id';
    const PRODUCT_ID = 'product_id';
    const LINKED_BLOG_POST_ID = 'linked_blog_post_id';
    const POSITION = 'position';

    /**
     * @return mixed
     */
    public function getLinkId()
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @return mixed
     */
    public function getLinkedPostId()
    {
        return $this->getData(self::LINKED_BLOG_POST_ID);
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * @param $linkId
     * @return mixed
     */
    public function setLinkId($linkId)
    {
        return $this->setData(self::LINK_ID, $linkId);
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @param $linkedPostId
     * @return mixed
     */
    public function setLinkedPostId($linkedPostId)
    {
        return $this->setData(self::LINKED_BLOG_POST_ID, $linkedPostId);
    }

    /**
     * @param $position
     * @return mixed|Link
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }
}
