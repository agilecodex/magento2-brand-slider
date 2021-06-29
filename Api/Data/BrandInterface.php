<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Api\Data;

/**
 * Brand Service Contract
 * @author   dev@agilecodex.com
 */
interface BrandInterface
{
    const BASE_MEDIA_PATH = 'acx/brandslider/images';

    const BRAND_TARGET_SELF = 0;
    const BRAND_TARGET_PARENT = 1;
    const BRAND_TARGET_BLANK = 2;



    /**
     * get form field html id prefix.
     *
     * @return string
     */
    public function getFormFieldHtmlIdPrefix();

    /**
     * get available slides.
     *
     * @return []
     */
    public function getAvailableSlides();

    /**
     * get store attributes.
     *
     * @return array
     */
    public function getStoreAttributes();

    /**
     * get store view id.
     *
     * @return int
     */
    public function getStoreViewId();

    /**
     * set store view id.
     *
     * @param int $storeViewId
     */
    public function setStoreViewId($storeViewId);

    /**
     * before save.
     */
    public function beforeSave();

    /**
     * after save.
     */
    public function afterSave();

    /**
     * load info multistore.
     *
     * @param mixed  $id
     * @param string $field
     *
     * @return $this
     */
    public function load($id, $field = null);

    /**
     * get store view value.
     *
     * @param string|null $storeViewId
     *
     * @return $this
     */
    public function getStoreViewValue($storeViewId = null);
}
