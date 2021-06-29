<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Model\ResourceModel\Brand;

/**
 * Brand Collection
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * store view id.
     *
     * @var int
     */
    protected $_storeViewId = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * added table
     * @var array
     */
    protected $_addedTable = [];

    /**
     * @var bool
     */
    protected $_isLoadBrandSliderTitle = FALSE;

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Acx\BrandSlider\Model\Brand', 'Acx\BrandSlider\Model\ResourceModel\Brand');
    }

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager
     * @param \Zend_Db_Adapter_Abstract                                    $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb              $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->_storeManager = $storeManager;

        if ($storeViewId = $this->_storeManager->getStore()->getId()) {
            $this->_storeViewId = $storeViewId;
        }
    }

    /**
     * @param $isLoadBrandSliderTitle
     * @return $this
     */
    public function setIsLoadBrandSliderTitle($isLoadBrandSliderTitle)
    {
        $this->_isLoadBrandSliderTitle = $isLoadBrandSliderTitle;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoadBrandSliderTitle()
    {
        return $this->_isLoadBrandSliderTitle;
    }

    /**
     * Before load action.
     *
     * @return $this
     */
    protected function _beforeLoad()
    {
        if ($this->isLoadBrandSliderTitle()) {
            $this->joinBrandSliderTitle();
        }

        return parent::_beforeLoad();
    }

    /**
     * join table to get BrandSlider Title of Brand
     * @return $this
     */
    public function joinBrandSliderTitle()
    {
        $this->getSelect()->joinLeft(
            ['brandsliderTable' => $this->getTable('acx_brandslider_brandslider')],
            'main_table.brandslider_id = brandsliderTable.brandslider_id',
            ['title' => 'brandsliderTable.title', 'brandslider_status' => 'brandsliderTable.status']
        );

        return $this;
    }

    /**
     * set order random by brand id
     *
     * @return $this
     */
    public function setOrderRandByBrandId()
    {
        $this->getSelect()->orderRand('main_table.entity_id');

        return $this;
    }

    /**
     * get store view id.
     *
     * @return int [description]
     */
    public function getStoreViewId()
    {
        return $this->_storeViewId;
    }

    /**
     * set store view id.
     *
     * @param int $storeViewId [description]
     */
    public function setStoreViewId($storeViewId)
    {
        $this->_storeViewId = $storeViewId;

        return $this;
    }

    /**
     * Multi store view.
     *
     * @param string|array      $field
     * @param null|string|array $condition
     */
    public function addFieldToFilter($field, $condition = null)
    {
        $attributes = array(
            'name',
            'status',
            'image_alt',
            'maintable',
        );
        $storeViewId = $this->getStoreViewId();
        
        if (in_array($field, $attributes) && $storeViewId) 
        {
            $mainfieldCondition = $this->_translateCondition("main_table.$field", $condition);
            $this->_select->where($mainfieldCondition, NULL, \Magento\Framework\DB\Select::TYPE_CONDITION);
            return $this;
        }
        
        if ($field == 'store_id') {
            $field = 'main_table.store_id';
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * get read connnection.
     */
    public function getConnection()
    {
        return $this->getResource()->getConnection();
    }

    /**
     * Multi store view.
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($storeViewId = $this->getStoreViewId()) {
            foreach ($this->_items as $item) {
                $item->setStoreViewId($storeViewId)->getStoreViewValue();
            }
        }

        return $this;
    }
}
