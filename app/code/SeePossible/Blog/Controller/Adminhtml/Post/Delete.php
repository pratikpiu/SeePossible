<?php

namespace SeePossible\Blog\Controller\Adminhtml\Post;

use SeePossible\Blog\Api\BlogRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Class Delete
 * @package SeePossible\Blog\Controller\Adminhtml\Post
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SeePossible_Blog::blog';

    /**
     * @var BlogRepositoryInterface
     */
    private $blogRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository
    )
    {
        parent::__construct($context);
        $this->blogRepository = $blogRepository;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->blogRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the post.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $resultRedirect->setPath('blog/post/');
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a post to delete.'));
        return $resultRedirect->setPath('blog/post/');
    }
}
