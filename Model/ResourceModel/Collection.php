<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License:    https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Model\ResourceModel;

use Acx\BrandSlider\Api\Data\BrandInterface;
use Acx\BrandSlider\Model\Brand as BrandModel;
use Acx\BrandSlider\Model\ResourceModel\AbstractCollection;
use Acx\BrandSlider\Model\ResourceModel\Brand as BrandResourceModel;

/**
 * Brand Collection
 */
class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = 'brand_id';

    /** @var string */
    protected $_eventPrefix = 'brand_brand_collection';

    /** @var string */
    protected $_eventObject = 'brand_collection';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);

        $this->performAfterLoad('acx_brand_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BrandModel::class,BrandResourceModel::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['brand_id'] = 'main_table.brand_id';
    }

    /**
     * Returns pairs brand_id - name
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('brand_id', 'name');
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $this->joinStoreRelationTable('acx_brand_store', $entityMetadata->getLinkField());
    }
}
