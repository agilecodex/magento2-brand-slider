<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Block;
use Acx\BrandSlider\Model\Status;

/**
 * BrandSlider Block
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class BrandSlider extends \Magento\Framework\View\Element\Template
{
    /**
     * template for evolution brandslider.
     */
    const TEMPLATE = 'Acx_BrandSlider::brandslider/brandslider.phtml';
    const XML_CONFIG_BRANDSLIDER = 'brandslider/general/enable_frontend';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Acx BrandSlider helper.
     *
     * @var \Acx\BrandSlider\Helper\Data
     */
    protected $_brandsliderHelper;

    /**
     * @var \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * scope config.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    public $_widget;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Acx\BrandSlider\Helper\Data $brandsliderHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_brandsliderHelper = $brandsliderHelper;
        $this->_storeManager = $context->getStoreManager();
        $this->_brandCollectionFactory = $brandCollectionFactory;
        $this->_scopeConfig = $context->getScopeConfig();
    }
    
    /**
     * @return
     */
    protected function _toHtml()
    {
        $store = $this->_storeManager->getStore()->getId();
        $configEnable = $this->_scopeConfig->getValue(
            self::XML_CONFIG_BRANDSLIDER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        
        if ($configEnable && $this->getBrandCollection()->getSize()) {
            $this->setTemplate(self::TEMPLATE);
        }
        
        return parent::_toHtml();
    }
    
    /**
     * get brand collection of brandslider.
     *
     * @return \Acx\BrandSlider\Model\ResourceModel\Brand\Collection
     */
    public function getBrandCollection()
    {
        $storeViewId = $this->_storeManager->getStore()->getId();

        /** @var \Acx\BrandSlider\Model\ResourceModel\Brand\Collection $brandCollection */
        $brandCollection = $this->_brandCollectionFactory->create()
            ->setStoreViewId($storeViewId)
            ->addFieldToFilter('status', Status::STATUS_ENABLED)
            ->setOrder('sort_order', 'ASC');
        
        return $brandCollection;
    }
    
    /**
     * get brand image url.
     *
     * @param \Acx\BrandSlider\Model\Brand $brand
     *
     * @return string
     */
    public function getBrandImageUrl(\Acx\BrandSlider\Model\Brand $brand)
    {
        return $this->_brandsliderHelper->getBaseUrlMedia($brand->getImage());
    }

    /**
     * get flexslider html id.
     *
     * @return string
     */
    public function getFlexSliderHtmlId()
    {
        return 'acx-brandslider-brandslider';
    }
}