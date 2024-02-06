<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License:    https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Model\ResourceModel\Store;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Brand Store Relations Resource model
 *
 * @author Agile Codex
 */
class Relation extends AbstractDb
{
    /**
     * Initialize resource model and define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('acx_brandslider_brand_store', 'brand_id');
    }

    /**
     * Save (rebuild) brand & store relations
     *
     * @param int $brandId
     * @param array $storeIds
     * @return $this
     */
    public function processRelations($brandId, $storeIds)
    {
        $select = $this->getConnection()->select()->from(
            $this->getMainTable(),
            ['store_id']
        )->where(
            'brand_id = ?',
            $brandId
        );
        $old = $this->getConnection()->fetchCol($select);
        $new = $storeIds;

        $insert = array_diff($new, $old);
        $delete = array_diff($old, $new);

        $this->addRelations($brandId, $insert);
        $this->removeRelations($brandId, $delete);

        return $this;
    }

    /**
     * Add Relation on duplicate update
     *
     * @param int $brandId
     * @param int $storeId
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addRelation($brandId, $storeId)
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            ['brand_id' => $brandId, 'store_id' => $storeId]
        );
        return $this;
    }

    /**
     * Add Relations
     *
     * @param int $brandId
     * @param int[] $storeIds
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addRelations($brandId, $storeIds)
    {
        if (!empty($storeIds)) {
            $insertData = [];
            foreach ($storeIds as $storeId) {
                $insertData[] = ['brand_id' => $brandId, 'store_id' => $storeId];
            }
            $this->getConnection()->insertMultiple($this->getMainTable(), $insertData);
        }
        return $this;
    }

    /**
     * Remove Relations
     *
     * @param int $brandId
     * @param int[] $storeIds
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeRelations($brandId, $storeIds)
    {
        if (!empty($storeIds)) {
            $where = join(
                ' AND ',
                [
                    $this->getConnection()->quoteInto('brand_id = ?', $brandId),
                    $this->getConnection()->quoteInto('store_id IN(?)', $storeIds)
                ]
            );
            $this->getConnection()->delete($this->getMainTable(), $where);
        }
        return $this;
    }
}
