<?php

namespace SeePossible\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Catalog\Api\Data\ProductInterface;
use SeePossible\Blog\Model\BlogLink\Link\SaveHandler;
use Magento\Framework\App\ObjectManager;

/**
 * Class Link
 * @package SeePossible\Blog\Model
 */
class Link extends AbstractModel
{
    /**
     * @var $saveBlogPostLinks
     */
    protected $saveBlogPostLinks;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Link::class);
    }

    /**
     * @param $product
     * @return $this
     */
    public function saveBlogPostRelations($product)
    {
        $this->getBlogPostLinkSaveHandler()->execute(ProductInterface::class, $product);
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getBlogPostLinkSaveHandler()
    {
        if (null === $this->saveBlogPostLinks) {
            $this->saveBlogPostLinks = ObjectManager::getInstance()->get(SaveHandler::class);
        }
        return $this->saveBlogPostLinks;
    }
}
