<?php

namespace SeePossible\Blog\Model\BlogLink\Link;

use SeePossible\Blog\Api\BlogLinkRepositoryInterface;
use SeePossible\Blog\Model\ResourceModel\Link;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class SaveHandler
 * @package SeePossible\Blog\Model\BlogLink\Link
 */
class SaveHandler
{
    /**
     * @var BlogLinkRepositoryInterface
     */
    protected $blogLinkRepository;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Link
     */
    protected $linkResource;

    /**
     * SaveHandler constructor.
     * @param MetadataPool $metadataPool
     * @param Link $linkResource
     * @param BlogLinkRepositoryInterface $blogLinkRepository
     */
    public function __construct(
        MetadataPool $metadataPool,
        Link $linkResource,
        BlogLinkRepositoryInterface $blogLinkRepository
    )
    {
        $this->metadataPool = $metadataPool;
        $this->linkResource = $linkResource;
        $this->blogLinkRepository = $blogLinkRepository;
    }

    /**
     * @param $entityType
     * @param $product
     * @return mixed
     * @throws \Exception
     */
    public function execute($entityType, $product)
    {
        $parentId = $product->getData($this->metadataPool->getMetadata($entityType)->getLinkField());
        if ($this->linkResource->hasBlogPostLinks($parentId)) {
            $blogPostResult = $this->linkResource->getBlogPostLinks($parentId);
            foreach($blogPostResult as $blogPostResult_key => $blogPostResult_value){
                $this->linkResource->deleteBlogPostLink($blogPostResult_value['link_id']);
            }
        }

        if (count($this->blogLinkRepository->getList($product)) > 0) {
            foreach ($this->blogLinkRepository->getList($product) as $blogPostvalue) {
                $this->blogLinkRepository->save($blogPostvalue);
            }
        }
        return $product;
    }
}
