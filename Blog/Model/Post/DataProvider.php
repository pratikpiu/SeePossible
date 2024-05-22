<?php

namespace SeePossible\Blog\Model\Post;

use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use SeePossible\Blog\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class DataProvider
 * @package SeePossible\Blog\Model\Post
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var $loadedData
     */
    protected $loadedData;

    /**
     * @var \SeePossible\Blog\Model\ResourceModel\Post\Collection
     */
    protected $collection;


    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param FileInfo $fileInfo
     * @param File $fileSystemIo
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();

        }
        return $this->loadedData;
    }
}
