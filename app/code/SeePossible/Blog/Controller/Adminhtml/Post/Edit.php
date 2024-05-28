<?php

namespace SeePossible\Blog\Controller\Adminhtml\Post;

use SeePossible\Blog\Api\BlogRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package SeePossible\Blog\Controller\Adminhtml\Post
 */
class Edit extends Action
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
    protected $blogRepository;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param BlogRepositoryInterface $blogRepository
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     */
    public function __construct(
        Action\Context $context,
        BlogRepositoryInterface $blogRepository,
        PageFactory $resultPageFactory,
        RequestInterface $request
    )
    {
        parent::__construct($context);
        $this->blogRepository = $blogRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $postId = (int)$this->getRequest()->getParam('post_id');
        $post = null;
        $isExistingPost = (bool)$postId;
        if ($isExistingPost) {
            try {
                $storeId = $this->request->getParam('store', 0);
                $post = $this->blogRepository->get($postId, $storeId);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while editing the supplier.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('blog/*/index');
                return $resultRedirect;
            }
        }
        $resultPage = $this->resultPageFactory->create();
        if ($isExistingPost) {
            $resultPage->getConfig()->getTitle()->prepend(__($post->getTitle()));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Blog'));
        }
        return $resultPage;
    }
}
