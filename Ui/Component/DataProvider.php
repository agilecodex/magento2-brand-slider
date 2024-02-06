<?php
declare(strict_types=1);
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * @website www.agilecodex.com
 */
namespace Acx\BrandSlider\Ui\Component;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param AuthorizationInterface $authorization
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        AuthorizationInterface $authorization,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->authorization = $authorization;
        $this->meta = array_replace_recursive($meta, $this->prepareMetadata());
    }

    /**
     * Prepares Meta
     *
     * @return array
     */
    public function prepareMetadata()
    {
        $metadata = [];

        if (!$this->authorization->isAllowed('Acx_BrandSlider::brandslider')) {
            $metadata = [
                'brandslider_brand_columns' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'editorConfig' => [
                                    'enabled' => false
                                ],
                                'componentType' => \Magento\Ui\Component\Container::NAME
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $metadata;
    }
}
