<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * NewAction
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class NewAction extends \Acx\BrandSlider\Controller\Adminhtml\Brand
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();
         $this->_getSession()->unsBrandName();
         $this->_getSession()->unsImageAlt();
        return $resultForward->forward('edit');
    }
}
