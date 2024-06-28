<?php
/**
 * @copyright Copyright (c) AgileCodex (https://www.agilecodex.com/)
 * @license https://www.agilecodex.com/license-agreement
 */

declare(strict_types=1);

namespace Acx\BrandSlider\Block\Adminhtml\Brand\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save and Continue Edit'),
            'class'          => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order'     => 80,
        ];
    }
}
