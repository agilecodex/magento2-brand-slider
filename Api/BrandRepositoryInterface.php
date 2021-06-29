<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Api;

/**
 * Brand Repository Interface
 * @author   dev@agilecodex.com
 */
interface BrandRepositoryInterface
{
    /**
     * get brand collection of brandslider.
     *
     * @return \Acx\BrandSlider\Model\ResourceModel\Brand\Collection
     */
    public function getBrandCollection();
}