<?php

namespace SeePossible\Blog\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SeePossible\Blog\Api\PostLinkRepositoryInterface;
use SeePossible\Blog\Api\Data\PostLinkInterface;
use SeePossible\Blog\Api\Data\PostLinkInterfaceFactory;
use SeePossible\Blog\Model\ResourceModel\PostLink\CollectionFactory;
use SeePossible\Blog\Model\ResourceModel\PostLink as ResourcePostLink;
use Exception;

/**
 * Class PostLinkRepository
 * @package SeePossible\Blog\Model\PostLink
 */
class PostLinkRepository implements PostLinkRepositoryInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var PostLinkInterfaceFactory
     */
    protected $postLinkInterfaceFactory;

    /**
     * @var CollectionFactory
     */
    protected $postLinkCollectionFactory;

    /**
     * @var ResourcePostLink
     */
    protected $postLinkResource;


    /**
     * @param ProductRepositoryInterface $productRepository
     * @param PostLinkInterfaceFactory $blogLinkInterfaceFactory
     * @param RequestInterface $request
     * @param CollectionFactory $postLinkCollectionFactory
     * @param ResourcePostLink $postLinkResource
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PostLinkInterfaceFactory $postLinkInterfaceFactory,
        RequestInterface $request,
        CollectionFactory $postLinkCollectionFactory,
        ResourcePostLink $postLinkResource
    ) {
        $this->productRepository = $productRepository;
        $this->postLinkInterfaceFactory = $postLinkInterfaceFactory;
        $this->request   = $request;
        $this->postLinkCollectionFactory = $postLinkCollectionFactory;
        $this->postLinkResource = $postLinkResource;
    }


    /**
     * Save Post Link Data
     *
     * @param PostLinkInterface $postLinkData
     * @return mixed|ResourcePostLink
     * @throws CouldNotSaveException
     */
    public function save(PostLinkInterface $postLinkData)
    {
        try {
            $postLink = $this->postLinkResource->save($postLinkData);
            return $postLink;
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('Couldn\'t link post with product: "%1"', $e->getMessage()),
                $e
            );
        }
    }

    /**
     * get PostLink Data
     *
     * @param $linkId
     * @return PostLinkInterface
     * @throws NoSuchEntityException
     */
    public function get($linkId)
    {
        /** @var PostLink $blog */
        $postLinkData = $this->postLinkInterfaceFactory->create();
        $this->postLinkResource->load($blog, $linkId);
        if (!$linkId) {
            throw new NoSuchEntityException(__('The Post with the "%1" ID doesn\'t exist.', $linkId));
        }
        return $postLinkData;
    }


    public function getList(ProductInterface $product)
    {
        if (!$product->getId()) {
            return $product->getProductLinks();
        }
        $collection = $this->postLinkCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $product->getId());
        return $collection->getItems();
    }

    /**
     * Remove Post From Product
     *
     * @param PostLinkInterface $postLinkData
     * @return true
     * @throws CouldNotDeleteException
     */
    public function delete(PostLinkInterface $postLinkData)
    {
        try {
            $this->postLinkResource->delete($postLinkData);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not remove link post from product: %1',
                $exception->getMessage()
            ));
        }
        return true;

    }
}
