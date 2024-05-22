<?php

namespace SeePossible\Blog\Api;

use SeePossible\Blog\Api\Data\BlogInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface BlogRepositoryInterface
 * @package SeePossible\Blog\Api
 */
interface BlogRepositoryInterface
{
    /**
     * @param BlogInterface $blog
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(BlogInterface $blog);

    /**
     * Retrieve Post.
     *
     * @param int $id
     * @param null $storeId
     * @return BlogInterface
     * @throws NoSuchEntityException
     */
    public function get($id);

    /**
     * Delete Blog.
     *
     * @param BlogInterface $blog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(BlogInterface $blog);

    /**
     * Delete blog by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
    */
    public function deleteById($id);
}
