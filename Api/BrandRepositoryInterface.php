<?php

/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Api;

use Acx\BrandSlider\Api\Data\BrandInterface;
use Acx\BrandSlider\Api\Data\BrandSearchResultsInterface;
use Acx\BrandSlider\Model\Brand;
use Acx\BrandSlider\Model\ResourceModel\Brand\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Repository interface to save, load or delete Brand logo
 * @api
 * @author Agile Codex
 */
interface BrandRepositoryInterface
{
    /**
     * Save Brand data
     *
     * @param BrandInterface $brand
     * @return BrandInterface
     * @throws CouldNotSaveException
     */
    public function save(BrandInterface $brand);

    /**
     * Load Brand data by given Brand Identity
     *
     * @param string $brandId
     * @return BrandInterface
     * @throws NoSuchEntityException
     */
    public function getById(string $brandId): BrandInterface;

    /**
     * Load Brand data collection by given search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Acx\BrandSlider\Api\Data\BrandSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): BrandSearchResultsInterface;

    /**
     * Delete Brand
     *
     * @param BrandInterface $brand
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(BrandInterface $brand): bool;

    /**
     * Delete Brand by given Brand Identity
     *
     * @param string $brandId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $brandId): bool;

    /**
     * Get brand collection of brandslider.
     *
     * @return \Acx\BrandSlider\Model\ResourceModel\Brand\Collection
     */
    public function getBrandCollection(): Collection;
}
