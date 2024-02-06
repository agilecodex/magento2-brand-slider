<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * License: https://www.agilecodex.com/license-agreement
*/
namespace Acx\BrandSlider\Model;

use Acx\BrandSlider\Api\Data\BrandInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Brand Model
 * @author Agile Codex
 */
class Brand extends AbstractModel implements BrandInterface
{
    /** Brand slider cache tag */
    public const CACHE_TAG = 'acx_bs_b';

    /** @var string */
    protected $_eventPrefix = 'brand_slider';

    /** Brand's statuses */
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Brand::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_'
            . str_replace(' ', '_', $this->getName())];
    }

    /**
     * Set Brand ID.
     *
     * @param int $id
     * @return BrandInterface
     */
    public function setBrandId($id) {
        return $this->setData(self::BRAND_ID, $id);
    }

    /**
     * Get Brand ID.
     *
     * @return mixed
     */
    public function getBrandId(){
        return $this->getData(self::BRAND_ID);
    }

    /**
     * Get Brand Name.
     *
     * @return string|null
     */
    public function getName(): ?string {
        return $this->getData(self::NAME);
    }

    /**
     * Set Brand Name.
     *
     * @param $brandName
     * @return BrandInterface
     */
    public function setName($brandName): BrandInterface {
        return $this->setData(self::NAME, $brandName);
    }

    /**
     * Get sort order.
     *
     * @return int|null
     */
    public function getSortOrder(): ?int {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set sort order.
     *
     * @param $sortOrder
     * @return BrandInterface
     */
    public function setSortOrder($sortOrder): BrandInterface{
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get active status.
     *
     * @return int|null
     */
    public function getStatus(): ?int {
        return $this->getData(self::STATUS);
    }

    /**
     * Set active status.
     *
     * @param $status
     * @return BrandInterface
     */
    public function setStatus($status): BrandInterface{
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get logo image.
     *
     * @return string|null
     */
    public function getImage(): ?string {
        return $this->getData(self::IMAGE);
    }

    /**
     * Set logo image.
     *
     * @param $image
     * @return BrandInterface
     */
    public function setImage($image): BrandInterface{
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Get alt text of logo image.
     *
     * @return string|null
     */
    public function getImageAlt(): ?string {
        return $this->getData(self::IMAGE_ALT);
    }

    /**
     * Set alt text of logo image.
     *
     * @param string|null $imageAlt
     * @return BrandInterface
     */
    public function setImageAlt($imageAlt): BrandInterface{
        return $this->setData(self::IMAGE_ALT, $imageAlt);
    }

    /**
     * Get store ID.
     *
     * @return array|null
     */
    public function getStoreId(): ?array {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store ID.
     *
     * @param array|null $storeIds
     * @return BrandInterface
     */
    public function setStoreIds($storeIds): BrandInterface {
        return $this->setData(self::STORE_ID, $storeIds);
    }

    /**
     * Unset store ID.
     *
     * @return BrandInterface
     */
    public function unsetStoreIds(): BrandInterface {
        return $this->unsetData('store_id');
    }

    /**
     * Get updating time.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set updating time.
     *
     * @param string $value
     * @return BrandInterface
     */
    public function setUpdatedAt($value): BrandInterface{
        return $this->setData(self::UPDATE_TIME, $value);
    }

}
