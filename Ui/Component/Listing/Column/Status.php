<?php

namespace Acx\BrandSlider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Directory\Model\Config\Source\Country as SourceCountry;

class Status extends Column {
    /** Url path */

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;
    
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 2;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
    ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = [], SourceCountry $sourceCountry
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->sourceCountry = $sourceCountry;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
   

    /**
     * get available statuses.
     *
     * @return []
     */
    public static function getAvailableStatuses()
    {
         $options[] = ['label' => __('Enabled'), 'value' => self::STATUS_ENABLED];
         $options[] = ['label' => __('Disabled'), 'value' => self::STATUS_DISABLED];
        return $options;
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        
        $status_arr = $this->getAvailableStatuses();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                foreach ($status_arr as $st) {
                    $status_list[$st['value']] = $st['label'];
                }
                $item['status'] = isset($status_list[$item['status']]) ? $status_list[$item['status']] : "";
            }
        }
        
        
        return $dataSource;
    }

}

