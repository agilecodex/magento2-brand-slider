<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * Delete Brand action
 *
 * @author Agile Codex
 */
class Delete extends \Acx\BrandSlider\Controller\Adminhtml\Brand
{
    /**
     * @inheirtDoc
     */
    public function execute()
    {
        $brandId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);
        try {
            $brand = $this->_brandFactory->create()->setBrandId($brandId);
            $brand->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
