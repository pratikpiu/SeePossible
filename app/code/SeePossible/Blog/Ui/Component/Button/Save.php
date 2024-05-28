<?php

namespace SeePossible\Blog\Ui\Component\Button;

use Magento\Ui\Component\Control\Container;

/**
 * Class Save
 * @package SeePossible\Blog\Ui\Component\Button
 */
class Save extends Generic
{
    /** @inheritdoc */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'blog_post_form.blog_post_form',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
        ];
    }

    /**
     * @return array[]
     */
    public function getOptions()
    {
        return [
            [
                'id_hard' => 'save_and_close',
                'label' => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'blog_post_form.blog_post_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
