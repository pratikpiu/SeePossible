<?php

namespace SeePossible\Blog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Modal;
use Magento\Backend\Model\UrlInterface as backendUrl;
use Magento\Framework\Phrase;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use SeePossible\Blog\Api\BlogLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use SeePossible\Blog\Api\Data\BlogLinkInterface;
use SeePossible\Blog\Api\BlogRepositoryInterface;
use SeePossible\Blog\Ui\Component\Listing\Column\Store;
use Magento\Store\Model\System\Store as SystemStore;


class BlogPostTab extends AbstractModifier
{
    const DATA_SCOPE = '';
    const DATA_SCOPE_BLOG = 'blog';
    const GROUP_BLOG = 'blog';

    /**
     * @var backendUrl
     */
    protected $backendUrl;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @var string
     */
    protected $scopePrefix;

    /**
     * @var BlogLinkRepositoryInterface
     */
    protected $blogLinkRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var string
     */
    protected  $storeKey;


    /**
     * @var SystemStore
     */
    protected $systemStore;

    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        backendUrl $backendUrl,
        BlogLinkRepositoryInterface $blogLinkRepository,
        ProductRepositoryInterface $productRepository,
        BlogRepositoryInterface $blogRepository,
        SystemStore $systemStore,
        Store $store,
        $scopeName = '',
        $scopePrefix = '',
        $storeKey = 'store_ids'
    )
    {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->backendUrl = $backendUrl;
        $this->blogLinkRepository = $blogLinkRepository;
        $this->productRepository = $productRepository;
        $this->blogRepository = $blogRepository;
        $this->systemStore = $systemStore;
        $this->store = $store;
        $this->scopeName = $scopeName;
        $this->scopePrefix = $scopePrefix;
        $this->storeKey = $storeKey;
    }

    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_BLOG
        ];
    }

    public function modifyData(array $data)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }

        foreach ($this->getDataScopes() as $dataScope) {
            //$data[$productId]['links'][$dataScope] = [];
            foreach ($this->blogLinkRepository->getList($product) as $blogvalue) {
                /** @var \Magento\Catalog\Model\Product $linkedProduct */
                $linkedBlogPost = $this->blogRepository->get($blogvalue['linked_post_id']);
                $data[$productId]['links'][$dataScope][] = $this->fillData($linkedBlogPost);
            }
        }

        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_product_id'] = $productId;
        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_store_id'] = $this->locator->getStore()->getId();

        return $data;
    }

    protected function fillData($linkedBlogPost)
    {
        if ($linkedBlogPost->getIsActive() == 1) {
            $status = __('Enabled');
        } else {
            $status = __('Disabled');
        }

        $item = $linkedBlogPost->getData();

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
            $content = __('All Store Views');
        }else{
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
        }

        return [
            'id' => $linkedBlogPost->getPostId(),
            'title' => $linkedBlogPost->getTitle(),
            'description' => $linkedBlogPost->getDescription(),
            'is_active' => $status
        ];
    }

    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                static::GROUP_BLOG => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_BLOG => $this->getBlogPostFieldset(),
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Blog Posts'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' => 10,
                            ],
                        ],

                    ],
                ],
            ]
        );
        return $meta;
    }

    protected function getBlogPostFieldset()
    {
        $content = __('SeePossible Blog display in product detail page');
        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Blog To Product'),
                    $this->scopePrefix . static::DATA_SCOPE_BLOG
                ),
                'modal' => $this->getGenericModal(
                    __('Add Blog To Product'),
                    $this->scopePrefix . static::DATA_SCOPE_BLOG
                ),
                static::DATA_SCOPE_BLOG => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_BLOG),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Blog Posts'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ]
        ];
    }

    /**
     * @param Phrase $content
     * @param Phrase $buttonTitle
     * @param $scope
     * @return array
     */
    protected function getButtonSet(Phrase $content, Phrase $buttonTitle, $scope)
    {
        $modalTarget = $this->scopeName . '.' . static::GROUP_BLOG . '.' . $scope . '.modal';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content' => $content,
                        'template' => 'ui/form/components/complex',
                    ],
                ],
            ],
            'children' => [
                'button_' . $scope => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $modalTarget,
                                        'actionName' => 'toggleModal',
                                    ],
                                    [
                                        'targetName' => $modalTarget . '.' . $scope . '_product_listing',
                                        'actionName' => 'render',
                                    ]
                                ],
                                'title' => $buttonTitle,
                                'provider' => null,
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     * @param Phrase $title
     * @param $scope
     * @return array
     */
    protected function getGenericModal(Phrase $title, $scope)
    {
        $listingTarget = $scope . '_product_listing';
        $modal = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'options' => [
                            'title' => $title,
                            'buttons' => [
                                [
                                    'text' => __('Cancel'),
                                    'actions' => [
                                        'closeModal'
                                    ]
                                ],
                                [
                                    'text' => __('Add Selected Blog Post'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = ' . $listingTarget,
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                $listingTarget => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'insertListing',
                                'dataScope' => $listingTarget,
                                'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                                'selectionsProvider' => $listingTarget . '.' . $listingTarget . '.blog_product_columns.ids',
                                'ns' => $listingTarget,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'dataLinks' => [
                                    'imports' => false,
                                    'exports' => false
                                ],
                                'behaviourType' => 'simple'
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $modal;
    }

    protected function getGrid($scope)
    {
        $dataProvider = $scope . '_product_listing';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'label' => null,
                        'columnsHeader' => false,
                        'columnsHeaderAfterRender' => true,
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
                        'addButton' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => 'data.links',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProvider' => $dataProvider,
                        'map' => [
                            'id' => 'post_id',
                            'client' => 'title',
                            'company' => 'description',
                            'website_url' => 'is_active'
                        ],
                        'links' => [
                            'insertData' => '${ $.provider }:${ $.dataProvider }'
                        ],
                        'sortOrder' => 2,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $this->fillMeta(),
                ],
            ],
        ];
    }

    protected function fillMeta()
    {
        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 0),
            'title' => $this->getTextColumn('client', false, __('Title'), 20),
            'description' => $this->getTextColumn('company', false, __('Description'), 30),
            'is_active' => $this->getTextColumn('status', true, __('Is Active'), 80),
            'actionDelete' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'data-grid-actions-cell',
                            'componentType' => 'actionDelete',
                            'dataType' => Text::NAME,
                            'label' => __('Actions'),
                            'sortOrder' => 90,
                            'fit' => true,
                        ],
                    ],
                ],
            ],
            'position' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'dataType' => Number::NAME,
                            'formElement' => Input::NAME,
                            'componentType' => Field::NAME,
                            'dataScope' => 'position',
                            'sortOrder' => 100,
                            'visible' => false,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Retrieve text column structure
     *
     * @param string $dataScope
     * @param bool $fit
     * @param Phrase $label
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getTextColumn($dataScope, $fit, Phrase $label, $sortOrder, $config = [])
    {
        $column = [
            'arguments' => [
                'data' => [
                    'config' => array_merge([
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'dataType' => Text::NAME,
                        'dataScope' => $dataScope,
                        'fit' => $fit,
                        'label' => $label,
                        'sortOrder' => $sortOrder,
                    ], $config),
                ],
            ],
        ];

        return $column;
    }
}
