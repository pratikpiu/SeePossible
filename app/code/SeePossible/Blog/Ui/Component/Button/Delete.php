<?php

namespace SeePossible\Blog\Ui\Component\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete
 * @package SeePossible\Blog\Ui\Component\Button
 */
class Delete extends Generic implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        if (!$this->context->getRequestParam('post_id')) {
            return [];
        }
        $data = [
            'label' => __('Delete'),
            'class' => 'delete',
            'id' => 'blog-edit-delete-button',
            'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to delete this blog?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        $id = $this->context->getRequestParam('post_id');
        return $this->getUrl('*/*/delete', ['post_id' => $id]);
    }
}
