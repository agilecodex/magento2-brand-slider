<?php

/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * Action class for new brand logo.
 *
 * @author Agile Codex
 */
class NewAction extends \Acx\BrandSlider\Controller\Adminhtml\Brand
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();
         $this->_getSession()->unsBrandName();
         $this->_getSession()->unsImageAlt();
        return $resultForward->forward('edit');
    }

}
