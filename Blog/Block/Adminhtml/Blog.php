<?php

namespace SeePossible\Blog\Block\Adminhtml;


class Blog extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_headerText = __('Post');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}
