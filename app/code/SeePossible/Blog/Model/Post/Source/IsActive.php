<?php
namespace SeePossible\Blog\Model\Post\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \SeePossible\Blog\Model\Blog
     */
    protected $blog;

    /**
     * Status constructor.
     * @param \SeePossible\Blog\Model\Blog $blog
     */
    public function __construct(\SeePossible\Blog\Model\Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Get options
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->blog->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
