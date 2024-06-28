<?php
/**
 * @copyright Copyright (c) AgileCodex (https://www.agilecodex.com/)
 * @license https://www.agilecodex.com/license-agreement
 */

declare(strict_types=1);

namespace Acx\BrandSlider\Block\Adminhtml\Brand\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {

        $data = [];
        if ($this->getBrandId()) {
            $data = [
                'label'      => __('Reset'),
                'class'      => 'reset',
                'on_click'   => 'location.href;',
                'sort_order' => 30,
                'title'      => 'Resets input fields values',
            ];
        }
        return $data;
    }
}
