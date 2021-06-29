<?php

namespace Acx\BrandSlider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ImageAlt extends Column {
    /** Url path */

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;
    
    /**
     * 
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    
    public function __construct(
    ContextInterface $context,
    UiComponentFactory $uiComponentFactory, 
    UrlInterface $urlBuilder, 
    array $components = [], 
    array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
   

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        
        
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                
                $item['image_alt'] = (isset($item['image_alt']) && $item['image_alt'])?$item['image_alt'] : strtolower($item['name']);
            }
           
        }
        
        
        return $dataSource;
    }

}

