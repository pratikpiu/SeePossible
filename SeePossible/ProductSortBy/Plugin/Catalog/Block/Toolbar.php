<?php

namespace SeePossible\ProductSortBy\Plugin\Catalog\Block;
use Closure;

/**
 * Class Toolbar
 * @package SeePossible\ProductSortBy\Plugin\Catalog\Block
 */
class Toolbar
{
    const NEW = 'new';


    /**
     * Sor product by new
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
     * @param Closure $proceed
     * @param $collection
     * @return mixed
     */

    public function aroundSetCollection(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
        Closure $proceed,
        $collection
    )
    {
        $currentOrder = $subject->getCurrentOrder();
        $direction = $subject->getCurrentDirection();
        $result = $proceed($collection);
        if ($currentOrder) {
            if ($currentOrder == self::NEW) {
                $result->getCollection()->setOrder('created_at', $direction);
            }
        }
        return $result;
    }
}
