<?php
/**
 * @copyright Copyright (c) AgileCodex (https://www.agilecodex.com/)
 * @license https://www.agilecodex.com/license-agreement
 */

declare(strict_types=1);

namespace Acx\BrandSlider\Api\Data;

interface BrandStoreInterface
{
    const TABLE_NAME = 'acx_brand_store';

    const ID = 'id';
    const BRAND_ID = 'brand_id';
    const STORE_ID = 'store_id';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param string $value
     * @return $this
     */
    public function setStoreId($value);
}
