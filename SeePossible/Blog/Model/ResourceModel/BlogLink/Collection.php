<?php

namespace SeePossible\Blog\Model\ResourceModel\BlogLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package SeePossible\Blog\Model\ResourceModel\BlogLink
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \SeePossible\Blog\Model\Link::class,
            \SeePossible\Blog\Model\ResourceModel\Link::class
        );
    }
}
