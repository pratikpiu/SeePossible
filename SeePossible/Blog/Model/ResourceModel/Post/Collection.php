<?php

namespace SeePossible\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package SeePossible\Blog\Model\ResourceModel\Post
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SeePossible\Blog\Model\Blog', 'SeePossible\Blog\Model\ResourceModel\Blog');
    }
}
