<?php

/**
 * Acx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * http://www.agilecodex.com/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Acx
 * @package     Acx_BrandSlider
 * @copyright   Copyright (c) 2016 Acx (http://www.agilecodex.com/)
 * @license     http://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * NewAction
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   Wasim haider Chowdhury
 */
class NewAction extends \Acx\BrandSlider\Controller\Adminhtml\Brand
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}