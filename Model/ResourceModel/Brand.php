<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License:    https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Model\ResourceModel;

use Acx\BrandSlider\Api\Data\BrandInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Brand Resource Model
 *
 * @author   Agile Codex
 */
class Brand extends AbstractDb
{
    /** @var StoreManagerInterface */
    protected $_storeManager;

    /** @var EntityManager */
    protected $entityManager;

    /** @var MetadataPool */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $connectionName);
    }

    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('acx_brandslider_brand', 'brand_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(BrandInterface::class)->getEntityConnection();
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->getIsUniqueBlockToStores($object)) {
            throw new LocalizedException(
                __('A brand logo with the same properties already exists in the selected store.')
            );
        }
        return $this;
    }

    /**
     * Get brand brand_id.
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getBrandId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);
        if (!$field || $field === null) {
            $field = 'brand_id';
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    /**
     * Load an object
     *
     * @param \Acx\BrandSlider\Model\Brand|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model brand_id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $brandId = $this->getBrandId($object, $value, $field);
        if ($brandId) {
            $this->entityManager->load($object, $brandId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Acx\BrandSlider\Model\Brand|AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['cbs' => $this->getTable('acx_brandslider_brand_store')],
                $this->getMainTable() . '.' . $linkField . ' = cbs.' . $linkField,
                ['store_id']
            )
                ->where('status = ?', 1)
                ->where('cbs.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Check for unique of identifier of brand to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueBlockToStores(AbstractModel $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $stores = $this->_storeManager->isSingleStoreMode()
            ? [Store::DEFAULT_STORE_ID]
            : (array)$object->getData('store_id');

        $select = $this->getConnection()->select()
            ->from(['cb' => $this->getMainTable()])
            ->join(
                ['cbs' => $this->getTable('acx_brandslider_brand_store')],
                'cb.' . $linkField . ' = cbs.' . $linkField,
                []
            )
            ->where('cbs.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('cb.' . $entityMetadata->getIdentifierField() . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cbs' => $this->getTable('acx_brandslider_brand_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . $linkField . ' = cb.' . $linkField,
                []
            )
            ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :brand_id');

        return $connection->fetchCol($select, ['brand_id' => (int)$id]);
    }

    /**
     * Save an object.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
