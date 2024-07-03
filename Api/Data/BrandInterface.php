<?php

/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Api\Data;

/**
 * Brand Service Contract
 * @api
 * @author Agile Codex
 */
interface BrandInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const TABLE_NAME = 'acx_brand_slider';
    public const BRAND_ID = 'brand_id';
    public const NAME = 'name';
    public const SORT_ORDER = 'sort_order';
    public const STATUS = 'status';
    public const LOGO = 'logo';
    public const LOGO_ALT = 'logo_alt';
    public const UPDATE_TIME = 'update_time';
    public const STORE_ID = 'store_id';

    public const BASE_MEDIA_PATH = 'acx/brand/images';
    public const BRAND_TARGET_SELF = 0;
    public const BRAND_TARGET_PARENT = 1;
    public const BRAND_TARGET_BLANK = 2;

    /**
     * Set Brand logo ID
     *
     * @param int $id
     * @return BrandInterface
     */
    public function setBrandId($id);

    /**
     * Get Brand Logo ID
     *
     * @return mixed
     */
    public function getBrandId();

    /**
     * Get Brand Logo Name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param $brandName
     * @return BrandInterface
     */
    public function setName($brandName): BrandInterface;

    /**
     * Get brand logo sort order
     *
     * @return int|null
     */
    public function getSortOrder(): ?int;

    /**
     * Set brand logo sort order
     *
     * @param $sortOrder
     * @return BrandInterface|null
     */
    public function setSortOrder($sortOrder): ?BrandInterface;

    /**
     * Get active status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set active status
     *
     * @param int|bool $status
     * @return BrandInterface
     */
    public function setStatus($status): BrandInterface;

    /**
     * Get logo image
     *
     * @return string|null
     */
    public function getLogo(): ?string;

    /**
     * Set logo image
     *
     * @param string $image
     * @return BrandInterface
     */
    public function setLogo($image): ?BrandInterface;

    /**
     * Get Alt text of logo image
     *
     * @return string|null
     */
    public function getLogoAlt(): ?string;

    /**
     * Set Alt text of logo image
     *
     * @param string|null $imageAlt
     * @return BrandInterface
     */
    public function setLogoAlt($imageAlt): BrandInterface;

    /**
     * Get store id of brand logo
     *
     * @return array|null
     */
    public function getStoreId(): ?array;

    /**
     * Set store id for brand logo
     *
     * @param array $storeIds
     * @return BrandInterface
     */
    public function setStoreIds(array $storeIds): BrandInterface;

    /**
     * Unset store id for brand logo
     *
     * @return BrandInterface
     */
    public function unsetStoreIds(): BrandInterface;

    /**
     * Set brand logo update time
     *
     * @param string|null $value
     * @return BrandInterface
     */
    public function setUpdatedAt($value): BrandInterface;

    /**
     * Get brand logo update time
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

}
