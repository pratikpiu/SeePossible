<?php

namespace SeePossible\Blog\Ui\Component\Button;

use Magento\Framework\Phrase;

/**
 * Class Back
 * @package SeePossible\Blog\Ui\Component\Button
 */
class Back extends Javascript
{
    /** @return Phrase */
    public function getLabel()
    {
        return __('Back');
    }

    /** @return string */
    public function getOnClick()
    {
        return sprintf("location.href = '%s';", $this->getBackUrl());
    }

    /** @return string|string[] */
    public function getClasses()
    {
        return 'back';
    }

    /** @return int */
    public function getSortOrder()
    {
        return 10;
    }

    /** @return string */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}
