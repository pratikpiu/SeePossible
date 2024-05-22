<?php

namespace SeePossible\Blog\Api\Data;


/**
 * Interface BlogLinkInterface
 * @package SeePossible\Blog\Api\Data
 */
interface BlogLinkInterface
{
    /**
     * @return mixed
     */
    public function getLinkId();

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @return mixed
     */
    public function getLinkedPostId();

    /**
     * @return mixed
     */
    public function getPosition();

    /**
     * @param $linkId
     * @return mixed
     */
    public function setLinkId($linkId);

    /**
     * @param $productId
     * @return mixed
     */
    public function setProductId($productId);

    /**
     * @param $linkedPostId
     * @return mixed
     */
    public function setLinkedPostId($linkedPostId);

    /**
     * @param $position
     * @return mixed
     */
    public function setPosition($position);
}
