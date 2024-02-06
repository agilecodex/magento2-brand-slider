<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Block;

use Acx\BrandSlider\Model\BrandFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * BrandSlider Widget Block
 *
 * @author Agile Codex
 */
class BrandSlider extends Template implements IdentityInterface
{
    /** @var BrandFactory */
    protected $brandFactory;

    /**
     * @param Context $context
     * @param BrandFactory $brandFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->brandFactory = $brandFactory;
    }

    /**
     * Get cache identities of the Brand
     *
     * @return array
     */
    public function getIdentities()
    {
        $brand = $this->getBrand();

        if ($brand) {
            return $brand->getIdentities();
        }

        return [];
    }
}
