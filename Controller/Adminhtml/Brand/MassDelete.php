<?php

/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory;

/**
 * Action class for mass delete.
 *
 * @author Agile Codex
 */
class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Acx_BrandSlider::brandslider_brands';

    /** @var Filter */
    protected $filter;

    /** @var CollectionFactory */
    protected $_brandCollectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->_brandCollectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $brandCollection = $this->filter->getCollection($this->_brandCollectionFactory->create());
        $collectionSize = $brandCollection->getSize();
        try {
            foreach ($brandCollection as $brand) {
                $brand->delete();
            }
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) have been deleted.', $collectionSize)
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

}
