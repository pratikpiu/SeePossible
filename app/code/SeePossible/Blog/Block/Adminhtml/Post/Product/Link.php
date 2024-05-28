<?php
namespace SeePossible\Blog\Block\Adminhtml\Post\Product;


use Magento\Backend\Block\Template;
use SeePossible\Blog\Model\ResourceModel\PostLink\CollectionFactory;

/**
 * Class Link
 * @package SeePossible\Blog\Block\Adminhtml\Post
 */
class Link extends Template
{
    protected $_template = 'SeePossible_Blog::post/link.phtml';

    /**
     * @var CollectionFactory
     */
    protected $postLinkCollectionFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param Template\Context $context
     * @param CollectionFactory $postLinkCollectionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $postLinkCollectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->postLinkCollectionFactory = $postLinkCollectionFactory;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPostProductLink()
    {
        $postId = $this->_request->getParam('post_id');
        $postProductLinkData = [];
        $collection = $this->postLinkCollectionFactory->create();
        $collection->addFieldToFilter('linked_blog_post_id', $postId);
        $collection->getItems();

        if ($collection->getSize() > 0) {
            foreach ($collection as $item) {
                $product = $this->productRepository->getById($item->getProductId());
                $postProductLinkData[$item->getProductId()]['id'] = $product->getId();
                $postProductLinkData[$item->getProductId()]['name'] = $product->getName();
                $postProductLinkData[$item->getProductId()]['sku'] = $product->getSku();
            }
        }
        return $postProductLinkData;
    }
}
