<?php

namespace SeePossible\Blog\Model\BlogLink;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\App\ObjectManager;
use SeePossible\Blog\Api\BlogLinkRepositoryInterface;
use SeePossible\Blog\Api\Data\BlogLinkInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use SeePossible\Blog\Api\Data\BlogLinkInterfaceFactory;

/**
 * Class Repository
 * @package SeePossible\Blog\Model\BlogLink
 */
class Repository implements BlogLinkRepositoryInterface
{

    protected $metadataPool;


    protected $linkResource;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var BlogLinkInterfaceFactory
     */
    protected $blogLinkInterfaceFactory;

    /**
     * Repository constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param BlogLinkInterfaceFactory $blogLinkInterfaceFactory
     * @param RequestInterface $request
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        BlogLinkInterfaceFactory $blogLinkInterfaceFactory,
        RequestInterface $request
    ) {
        $this->productRepository = $productRepository;
        $this->blogLinkInterfaceFactory = $blogLinkInterfaceFactory;
        $this->request   = $request;
    }

    /**
     * @param $blogLinkInterfaceFactory $entity
     * @return bool|mixed
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save(BlogLinkInterface $entity)
    {
        $product = $this->productRepository->getById($entity->getProductId());
        $links = [];
        $data['linked_blog_post_id'] = $entity->getLinkedPostId();
        $data['position'] = $entity->getPosition();
        $links[$entity->getLinkedPostId()] = $data;
        try {
            $productData = $this->getMetadataPool()->getHydrator(ProductInterface::class)->extract($product);
            $this->getLinkResource()->saveBlogPostLinks(
                $productData[$this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField()],
                $links);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('The linked Blog Post data is invalid. Verify the data and try again.'));
        }
        return true;
    }

    /**
     * @param ProductInterface $product
     * @return array|mixed
     * @throws \Exception
     */
    public function getList(ProductInterface $product)
    {
        $output = [];
        $params = $this->request->getParam('links');
        if(!empty($params) && !empty($params['post'])){
            foreach ($params['post'] as $key => $value){
                /** @var \SeePossible\Blog\Api\Data\BlogLinkInterface $blogPostResult */
                $blogPostResult = $this->blogLinkInterfaceFactory->create();
                $blogPostResult->setProductId($product->getId())
                    ->setLinkedPostId($value['id'])
                    ->setPosition($value['position']);
                $output[] = $blogPostResult;
            }
        }else{
            if ($this->getLinkResource()->hasBlogPostLinks($product->getId())) {
                $product_blogpost = $this->getLinkResource()->getBlogPostLinks($product->getId());
                    foreach ($product_blogpost as $key => $value){
                    /** @var \SeePossible\Blog\Api\Data\BlogLinkInterface $blogPostResult */
                        $blogPostResult = $this->blogLinkInterfaceFactory->create();
                        $blogPostResult->setProductId($product->getId())
                        ->setLinkedPostId($value['linked_blog_post_id'])
                        ->setPosition($value['position']);
                    $output[] = $blogPostResult;
                }
            }
        }
        return $output;
    }

    /**
     * @param BlogLinkInterface $entity
     * @return bool|mixed
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function delete(BlogLinkInterface $entity)
    {
        $linkId = $this->getLinkResource()->getBlogPostLinkId($entity['product_id'], $entity['linked_blog_post_id']);
        if (!$linkId) {
            throw new NoSuchEntityException(
                __(
                    'Product \'%1\' is not linked to blog post \'%2\'',
                    $entity['product_id'],
                    $entity['linked_blog_post_id']
                )
            );
        }

        try {
            $this->getLinkResource()->deleteBlogPostLink($linkId);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('The linked products data is invalid. Verify the data and try again.'));
        }
        return true;
    }

    /**
     * @param ProductInterface $product
     * @return mixed|true
     * @throws \Exception
     */
    public function getPostByProduct(ProductInterface $product){
        if ($this->getLinkResource()->hasBlogPostLink($product->getId())) {
            return $this->getLinkResource()->getBlogPostByProduct($product->getId());
        }
        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getLinkResource()
    {
        if (null === $this->linkResource) {
            $this->linkResource = ObjectManager::getInstance()
                ->get(\SeePossible\Blog\Model\ResourceModel\Link::class);
        }
        return $this->linkResource;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getMetadataPool()
    {
        if (null === $this->metadataPool) {
            $this->metadataPool = ObjectManager::getInstance()
                ->get(MetadataPool::class);
        }
        return $this->metadataPool;
    }
}
