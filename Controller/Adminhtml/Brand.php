<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml;

use Magento\Framework\Controller\Result\Redirect;

/**
 * Brand Abstract Action
 * @author Agile Codex
 */
abstract class Brand extends \Acx\BrandSlider\Controller\Adminhtml\AbstractAction
{
    public const PARAM_CRUD_ID = 'brand_id';

    /**
     * Check if admin has permissions to visit related pages.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Acx_BrandSlider::brand_brands');
    }

    /**
     * Get back result redirect after add/edit.
     *
     * @param Redirect $resultRedirect
     * @param int|null $paramCrudId
     * @return Redirect
     */
    protected function _getBackResultRedirect(
        Redirect $resultRedirect,
        $paramCrudId = null
    ) {
        switch ($this->getRequest()->getParam('back')) {
            case 'edit':
                $resultRedirect->setPath(
                    '*/*/edit',
                    [
                        static::PARAM_CRUD_ID => $paramCrudId,
                        '_current' => true,
                        'store' => $this->getRequest()->getParam('store'),
                        'current_brandslider_id' => $this->getRequest()->getParam('current_brandslider_id'),
                        'saveandclose' => $this->getRequest()->getParam('saveandclose'),
                    ]
                );
                break;
            case 'new':
                $resultRedirect->setPath('*/*/new', ['_current' => true]);
                break;
            default:
                $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect;
    }

}
