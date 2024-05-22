<?php

namespace SeePossible\ProductSortBy\Plugin\Catalog\Model;

/**
 * Class Config
 * @package SeePossible\ProductSortBy\Plugin\Catalog\Model
 */
class Config
{
    /**
     * add sort by new label
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param $options
     * @return mixed
     */
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    )
    {
        $options['new'] = __('New');
        return $options;
    }
}
