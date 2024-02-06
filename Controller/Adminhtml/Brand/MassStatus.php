<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

use Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * Action class for mass status.
 *
 * @author Agile Codex
 */
class MassStatus extends Brand
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');

        if (!is_array($brandIds) || empty($brandIds)) {
            $this->messageManager->addError(__('Please select brand(s).'));
        } else {
            $brandCollection = $this->_brandCollectionFactory->create()
                ->setStoreViewId($storeViewId)
                ->addFieldToFilter('brand_id', ['in' => $brandIds]);
            try {
                foreach ($brandCollection as $brand) {
                    $brand->setStoreViewId($storeViewId)
                        ->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($brandIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/', ['store' => $this->getRequest()->getParam('store')]);
    }

}
