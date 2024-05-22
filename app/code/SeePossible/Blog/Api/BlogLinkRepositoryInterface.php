<?php

namespace SeePossible\Blog\Api;

use SeePossible\Blog\Api\Data\BlogLinkInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface BlogRepositoryInterface
 * @package SeePossible\Blog\Api
 */
interface BlogLinkRepositoryInterface
{
    /**
     * @param ProductInterface $product
     * @return mixed
     */
    public function getList(ProductInterface $product);

    /**
     * @param BlogLinkInterface $entity
     * @return mixed
     */
    public function save(BlogLinkInterface $entity);

    /**
     * @param BlogLinkInterface $entity
     * @return mixed
     */
    public function delete(BlogLinkInterface $entity);

    /**
     * @param ProductInterface $product
     * @return mixed
     */
    public function getPostByProduct(ProductInterface $product);
}
