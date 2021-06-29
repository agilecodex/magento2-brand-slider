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
     * scope config.
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * @var \Acx\BrandSlider\Model\BrandRepository
     */
    protected $_brandRepository;
    
    /**
     * var \Magento\Framework\View\Asset\Repository
     */
    protected  $_assetRepo;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\View\Asset\Repository $assetRepo, 
        \Acx\BrandSlider\Model\BrandRepository $brandRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_brandRepository = $brandRepository;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_assetRepo = $assetRepo;
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
        
        if ($configEnable && $this->_brandRepository->getBrandCollection()->getSize()) {
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
        return $this->_brandRepository->getBrandCollection();
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
        $srcImage = $this->getBaseUrlMedia($brand->getImage());
        if (!preg_match('~\.(png|gif|jpe?g|bmp)~i', $srcImage)) {
            $srcImage = $this->_assetRepo->getUrl("Acx_BrandSlider::images/brand-logo-blank.png");
        }
        return $srcImage;
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
    
    /**
     * get Base Url Media.
     *
     * @param string $path   [description]
     * @param bool   $secure [description]
     *
     * @return string [description]
     */
    public function getBaseUrlMedia($path = '', $secure = false)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, $secure) . $path;
    }


    /**
     * get BrandSlider Brand Url
     * @return string
     */
    public function getBrandSliderBrandUrl()
    {
        return $this->_backendUrl->getUrl('*/*/brands', ['_current' => true]);
    }

    /**
     * get Backend Url
     * @param  string $route
     * @param  array  $params
     * @return string
     */
    public function getBackendUrl($route = '', $params = ['_current' => true])
    {
        return $this->_backendUrl->getUrl($route, $params);
    }
}