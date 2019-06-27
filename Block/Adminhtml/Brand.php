<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Block\Adminhtml;

/**
 * Brand grid container.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Brand extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_brand';
        $this->_blockGroup = 'Acx_BrandSlider';
        $this->_headerText = __('Brands');
        $this->_addButtonLabel = __('Add New Brand');
        parent::_construct();
    }
}
