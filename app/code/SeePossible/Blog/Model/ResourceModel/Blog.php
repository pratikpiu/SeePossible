<?php

namespace SeePossible\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Blog
 * @package SeePossible\Blog\Model\ResourceModel
 */
class Blog extends AbstractDb
{

    public function _construct()
    {
        $this->_init('sp_blog', 'post_id');
    }
}
