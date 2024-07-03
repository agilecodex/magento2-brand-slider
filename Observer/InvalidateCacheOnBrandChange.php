<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * License:  https://www.agilecodex.com/license-agreement
 */
declare(strict_types=1);

namespace Acx\BrandSlider\Observer;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer for invalidating cache on Brand Logo change
 * @author   Agile Codex
 */
class InvalidateCacheOnBrandChange implements ObserverInterface
{
    /** @var TypeListInterface */
    private $cacheTypeList;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @param TypeListInterface $cacheTypeList
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TypeListInterface $cacheTypeList,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->cacheTypeList = $cacheTypeList;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Invalidate cache on brand logo changed
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        $brandFields = ['name', 'sort_order', 'image', 'logo_alt', 'store_id'];
        $brand = $observer->getEvent()->getData('entity');
        $oldData = $observer->getEvent()->getData('oldData');

        if (!$brand->getOrigData()) {
            foreach ($oldData as $key => $value) {
                $brand->setOrigData($key, $value);
            }
        }

        if (!$brand->isObjectNew()) {
            foreach ($brandFields as $field) {
                if ($this->isBrandFieldChanged($field, $brand)) {
                    $this->cacheTypeList->invalidate(
                        [
                            \Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER,
                            \Magento\Framework\App\Cache\Type\Layout::TYPE_IDENTIFIER
                        ]
                    );
                    break;
                }
            }
        }
    }

    /**
     * Check if brand data changed
     *
     * @param string $field
     * @param \Acx\BrandSlider\Api\Data\BrandInterface $brand
     * @return bool
     */
    private function isBrandFieldChanged($field, $brand)
    {
        if (array_key_exists($field, (array)$brand->getOrigData())) {
            if ($brand->dataHasChangedFor($field)) {
                return true;
            }
        }

        return false;
    }
}
