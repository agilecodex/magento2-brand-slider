<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

/**
 * Edit action.
 *
 * @author Agile Codex
 */
class Edit extends \Acx\BrandSlider\Controller\Adminhtml\Brand
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('brand_id');
        $storeViewId = $this->getRequest()->getParam('store');
        $model = $this->_brandFactory->create();

        if ($id) {
            $model->load($id, 'brand_id');
            if (!$model->getId()) {
                $this->messageManager->addError(__('This brand no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        } elseif (!$id) {
            $brand_name = $this->_getSession()->getBrandName();
            $logo_alt = $this->_getSession()->getImageAlt();

            if (isset($brand_name) && strlen($brand_name)) {
                $data = [ 'name' => $brand_name, 'logo_alt' => $logo_alt ];
                $model->setData($data);
            }

        }

        $this->_coreRegistry->register('brand', $model);

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
