<?php

/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Controller\Adminhtml;

use Acx\BrandSlider\Model\BrandFactory;
use Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Abstract Action
 * @author Agile Codex
 */
abstract class AbstractAction extends \Magento\Backend\App\Action
{
    public const PARAM_CRUD_ID = 'brand_id';

    /** @var Js */
    protected $_jsHelper;

    /** @var StoreManagerInterface  */
    public StoreManagerInterface $storeManager;

    /** @var ForwardFactory */
    protected $_resultForwardFactory;

    /** @var LayoutFactory */
    protected $_resultLayoutFactory;

    /** @var PageFactory */
    protected $_resultPageFactory;

    /** @var BrandFactory */
    protected $_brandFactory;

    /** @var CollectionFactory */
    protected $_brandCollectionFactory;

    /** @var Registry */
    protected $_coreRegistry;

    /** @var FileFactory */
    protected $_fileFactory;

    /**
     * @param Context $context
     * @param BrandFactory $brandFactory
     * @param CollectionFactory $brandCollectionFactory
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param ForwardFactory $resultForwardFactory
     * @param StoreManagerInterface $storeManager
     * @param Js $jsHelper
     */
    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        CollectionFactory $brandCollectionFactory,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        StoreManagerInterface $storeManager,
        Js $jsHelper
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->storeManager = $storeManager;
        $this->_jsHelper = $jsHelper;

        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->_resultForwardFactory = $resultForwardFactory;

        $this->_brandFactory = $brandFactory;
        $this->_brandCollectionFactory = $brandCollectionFactory;
    }

    /**
     * Get back result redirect after add/edit.
     *
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @param int|null $paramCrudId
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _getBackResultRedirect(
        \Magento\Framework\Controller\Result\Redirect $resultRedirect,
        $paramCrudId = null
    ) {
        switch ($this->getRequest()->getParam('back')) {
            case 'edit':
                $resultRedirect->setPath(
                    '*/*/edit',
                    [
                        static::PARAM_CRUD_ID => $paramCrudId,
                        '_current' => true,
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
