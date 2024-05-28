<?php

namespace SeePossible\Blog\Model;

use SeePossible\Blog\Api\Data\BlogInterface;
use SeePossible\Blog\Api\Data\BlogInterfaceFactory;
use SeePossible\Blog\Api\BlogRepositoryInterface;
use SeePossible\Blog\Model\ResourceModel\Blog as BlogResource;
use SeePossible\Blog\Model\ResourceModel\Post\CollectionFactory as BlogCollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class BlogRepository
 * @package SeePossible\Blog\Model
 */
class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @var ResourceModel\Blog
     */
    protected $blogResource;
    /**
     * @var BlogInterfaceFactory
     */
    protected $blogInterfaceFactory;
    /**
     * @var BlogCollectionFactory
     */
    protected $blogCollectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * BlogRepository constructor.
     * @param BlogResource $blogResource
     * @param BlogInterfaceFactory $blogInterfaceFactory
     * @param BlogCollectionFactory $blogCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        BlogResource $blogResource,
        BlogInterfaceFactory $blogInterfaceFactory,
        BlogCollectionFactory $blogCollectionFactory,
        CollectionProcessorInterface $collectionProcessor
    )
    {
        $this->blogResource = $blogResource;
        $this->blogInterfaceFactory = $blogInterfaceFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save Blog.
     *
     * @param BlogInterface $blog
     * @return BlogInterface
     * @throws CouldNotSaveException
     */
    public function save(BlogInterface $blog)
    {
        try {
            $this->blogResource->save($blog);
            return $blog;
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('Couldn\'t save post: "%1"', $e->getMessage()),
                $e
            );
        }
    }

    /**
     * Get Blog Post
     *
     * @param $id
     * @return \SeePossible\Blog\Model\Blog
     * @throws NoSuchEntityException
     */
    public function get($id)
    {
        /** @var Blog $blog */
        $blog = $this->blogInterfaceFactory->create();
        $this->blogResource->load($blog, $id);
        if (!$id) {
            throw new NoSuchEntityException(__('The Post with the "%1" ID doesn\'t exist.', $id));
        }
        return $blog;
    }

    /**
     * Delete Blog.
     *
     * @param BlogInterface $blog
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(BlogInterface $blog)
    {
        try {
            $this->blogResource->delete($blog);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Post: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Blog by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->get($id));
    }
}
