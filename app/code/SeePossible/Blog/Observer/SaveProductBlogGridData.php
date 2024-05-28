<?php
namespace SeePossible\Blog\Observer;

use Magento\Catalog\Model\Product\Link\Resolver as LinkResolver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use SeePossible\Blog\Model\ResourceModel\PostLink\CollectionFactory as BlogGridProductCollectionFactory;
use SeePossible\Blog\Model\PostLinkFactory;
use SeePossible\Blog\Api\PostLinkRepositoryInterface;

/**
 * Class SaveProductBlogGridData
 * @package SeePossible\Blog\Observer
 */
class SaveProductBlogGridData implements ObserverInterface
{
    /**
     * @var BlogGridProductCollectionFactory
     */
    protected $blogGridProductCollectionFactory;

    /**
     * @var PostLinkFactory
     */
    protected $customGridProductFactory;

    /**
     * @var LinkResolver
     */
    private $linkResolver;

    /**
     * @var PostLinkRepositoryInterface
     */
    protected $postLinkRepository;


    /**
     * Constructor
     *
     * @param BlogGridProductCollectionFactory $customGridProductCollectionFactory
     * @param PostLinkFactory $customGridProductFactory
     * @param LinkResolver $linkResolver
     * @param PostLinkRepositoryInterface $postLinkRepository
     */
    public function __construct(
        BlogGridProductCollectionFactory $customGridProductCollectionFactory,
        PostLinkFactory $customGridProductFactory,
        LinkResolver $linkResolver,
        PostLinkRepositoryInterface $postLinkRepository
    ) {
        $this->blogGridProductCollectionFactory = $customGridProductCollectionFactory;
        $this->customGridProductFactory = $customGridProductFactory;
        $this->linkResolver = $linkResolver;
        $this->postLinkRepository = $postLinkRepository;
    }

    /**
     * Link Blog Post With Product
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();
        try {
            $blogLinks = $this->linkResolver->getLinks();
            $linkPost = $this->postLinkRepository;
            $linkPostWithProducts = $this->blogGridProductCollectionFactory->create();
            $linkPostWithProducts->addFieldToFilter('product_id', $productId);
            $linkPostWithProductData = $linkPostWithProducts->getSize() > 0 ? true : false;

            if (isset($blogLinks['blog'])) {
                $linkPostWithProductPostIds = array_column($linkPostWithProducts->getData(),'linked_blog_post_id');
                $needlinkPostWithProductPostIds = array_column($blogLinks['blog'],'id');
                $removeIds = array_diff($linkPostWithProductPostIds, $needlinkPostWithProductPostIds);
                $linkedIds = array_intersect($linkPostWithProductPostIds, $needlinkPostWithProductPostIds);
                $needTolinkedIds = array_diff($needlinkPostWithProductPostIds,$linkPostWithProductPostIds);

                if ($removeIds) {
                    foreach ($linkPostWithProducts as $linkPostWithProductItem) {
                        if (in_array($linkPostWithProductItem->getLinkedBlogPostId(),$removeIds)) {
                            $linkPost->delete($linkPostWithProductItem);
                        }
                    }
                }
                if ($needTolinkedIds) {
                    foreach ($blogLinks['blog'] as $post) {
                        if (in_array($post['id'],$needTolinkedIds)) {
                            $blogGridProduct = $this->customGridProductFactory->create();
                            $blogGridProduct->setProductId($productId);
                            $blogGridProduct->setLinkedPostId($post['id']);
                            $blogGridProduct->setPosition($post['position']);
                            $blogGridProduct->save();
                        }
                    }
                }
            } else if ($linkPostWithProductData) {
                foreach ($linkPostWithProducts as $linkPostWithProductItem) {
                    $linkPost->delete($linkPostWithProductItem);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage(); die();
        }
    }
}
