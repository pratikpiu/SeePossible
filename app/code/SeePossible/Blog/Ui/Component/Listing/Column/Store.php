<?php

namespace SeePossible\Blog\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

/**
 * Class Store
 * @package SeePossible\Blog\Ui\Component\Listing\Column
 */
class Store extends Column
{
    /**
     * @var SystemStore
     */
    protected $systemStore;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var string
     */
    protected $storeKey;

    /**
     * Store constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param SystemStore $systemStore
     * @param StoreManager $storeManager
     * @param array $components
     * @param array $data
     * @param string $storeKey
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SystemStore $systemStore,
        StoreManager $storeManager,
        array $components = [],
        array $data = [],
        $storeKey = 'store_ids'
    )
    {
        $this->systemStore = $systemStore;
        $this->storeManager = $storeManager;
        $this->storeKey = $storeKey;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }
        return $dataSource;
    }

    /**
     * @param array $item
     * @return \Magento\Framework\Phrase|string
     */
    protected function prepareItem(array $item)
    {
        $content = '';
        $origStores = null;
        if ($item[$this->storeKey] != null) {
            $origStores = $item[$this->storeKey];
        }
        $origStores = explode(",", $origStores);
        if ($origStores == null) {
            return '';
        }
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }
        if (in_array(0, $origStores) && count($origStores) == 1) {
            return __('All Store Views');
        }
        $data = $this->systemStore->getStoresStructure(false, $origStores);
        foreach ($data as $website) {
            $content .= $website['label'] . "<br/>";
            foreach ($website['children'] as $group) {
                $content .= str_repeat('&nbsp;', 3) . $group['label'] . "<br/>";
                foreach ($group['children'] as $store) {
                    $content .= str_repeat('&nbsp;', 6) . $store['label'] . "<br/>";
                }
            }
        }

        return $content;
    }

    /**
     * Prepare component configuration
     * @return void
     */
    public function prepare()
    {
        parent::prepare();
        if ($this->storeManager->isSingleStoreMode()) {
            $this->_data['config']['componentDisabled'] = true;
        }
    }
}
