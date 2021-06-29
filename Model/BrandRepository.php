<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Model;
use Acx\BrandSlider\Model\Status;

/**
 * BrandSlider Block
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class BrandRepository
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory
    ) {
        $this->_storeManager = $context->getStoreManager();
        $this->_brandCollectionFactory = $brandCollectionFactory;
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
            ->addFieldToFilter('store_id', ['in' => [0,$storeViewId]])
            ->setOrder('sort_order', 'ASC');
        
        return $brandCollection;
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
}