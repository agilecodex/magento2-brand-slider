<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Helper;

/**
 * Helper Data
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * category collection factory.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Framework\App\Helper\Context                      $context              [description]
     * @param \Magento\Directory\Helper\Data                             $directoryData        [description]
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection       $countryCollection    [description]
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regCollectionFactory [description]
     * @param \Magento\Store\Model\StoreManagerInterface                 $storeManager         [description]
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_backendUrl = $backendUrl;
        $this->_storeManager = $storeManager;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
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
     * get categories array.
     *
     * @return array
     */
    public function getCategoriesArray()
    {
        $categoriesArray = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToSort('path', 'asc')
            ->load()
            ->toArray();

        $categories = array();
        foreach ($categoriesArray as $categoryId => $category) {
            if (isset($category['name']) && isset($category['level'])) {
                $categories[] = array(
                    'label' => $category['name'],
                    'level' => $category['level'],
                    'value' => $categoryId,
                );
            }
        }

        return $categories;
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
