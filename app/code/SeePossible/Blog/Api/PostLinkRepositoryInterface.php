<?php

namespace SeePossible\Blog\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use SeePossible\Blog\Api\Data\PostLinkInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface PostLinkRepositoryInterface
 * @package SeePossible\Blog\Api
 */
interface PostLinkRepositoryInterface
{
    /**
     * Get post links list
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \SeePossible\Blog\Api\Data\PostLinkInterface[]
     * @since 101.0.0
     */
    public function getList(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Retrieve PostLink.
     *
     * @param int $linkId
     * @return PostLinkInterface
     * @throws NoSuchEntityException
     */
    public function get($linkId);

    /**
     * @param PostLinkInterface $entity
     * @return mixed
     */
    public function save(PostLinkInterface $entity);

    /**
     * @param PostLinkInterface $entity
     * @return mixed
     */
    public function delete(PostLinkInterface $entity);

}
