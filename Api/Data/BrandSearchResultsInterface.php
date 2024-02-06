<?php
declare(strict_types=1);
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * @website www.agilecodex.com
 */
namespace Acx\BrandSlider\Api\Data;

use Magento\Framework\Api\Search\SearchResultInterface;

/**
 * Interface for brand logo search results.
 * @api
 */
interface BrandSearchResultsInterface extends SearchResultInterface
{
    /**
     * Get brand list.
     *
     * @return \Acx\BrandSlider\Api\Data\BrandInterface[]
     */
    public function getItems();

    /**
     * Set brand list.
     *
     * @param \Acx\BrandSlider\Api\Data\BrandInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);

}
