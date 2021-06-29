<?php

namespace Acx\BrandSlider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class StoreView extends Column {
    /** Url path */

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;
    protected $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
    ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = [], StoreManagerInterface $storeManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        
        $store_list = [];
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                
                $store_arr = $this->getStoreData();
                
                foreach($store_arr as $st){
                    if(isset($st['value']))
                   $store_list[$st['value']] = $st['label'];
                }
                $item['store_id'] = isset($store_list[$item['store_id']])?$store_list[$item['store_id']]:$store_list[$item['store_id']];
            }
        }

        return $dataSource;
    }

    private function getStoreData() {
        $storeManagerDataList = $this->storeManager->getStores();
        $options = array();
        $options[] = ['label' => __('All Store Views'), 'value' => 0];
        foreach ($storeManagerDataList as $key => $value) {
            $options[] = ['label' => $value['name'] . ' - ' . $value['code'], 'value' => $key];
        }
        return $options;
    }

}
