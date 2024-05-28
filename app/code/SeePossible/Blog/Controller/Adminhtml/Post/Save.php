<?php

namespace SeePossible\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use SeePossible\Blog\Api\BlogRepositoryInterface;
use SeePossible\Blog\Api\Data\BlogInterfaceFactory;

/**
 * Class Save
 * @package SeePossible\Blog\Controller\Adminhtml\Post
 */
class Save extends Action
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
    protected $blogRepositoryInterface;

    /**
     * @var BlogInterfaceFactory
     */
    protected $blogInterfaceFactory;

    /**
     * @param Context $context
     * @param BlogInterfaceFactory $blogInterfaceFactory
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepositoryInterface,
        BlogInterfaceFactory $blogInterfaceFactory
    )
    {
        $this->blogRepositoryInterface = $blogRepositoryInterface;
        $this->blogInterfaceFactory = $blogInterfaceFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            if (empty($id)) {
                $data['post_id'] = null;
            }
            if ($id) {
                $model = $this->blogRepositoryInterface->get($id);
            } else {
                $model = $this->blogInterfaceFactory->create();
            }
            if (!$model->getPostId() && $id) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the post.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/', ['id' => $model->getPostId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
            }
            return $resultRedirect->setPath('*/*/', ['id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
