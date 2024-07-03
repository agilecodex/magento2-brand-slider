<?php
namespace Acx\BrandSlider\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Ui\Component\Listing\Columns\Thumbnail as CatalogThumbnail;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends CatalogThumbnail
{
    public const ALT_FIELD = 'name';

    /** @var ImageHelper */
    private $imageHelper;

    /** @var UrlInterface */
    private $urlBuilder;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ImageHelper $imageHelper
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        ImageHelper           $imageHelper,
        UrlInterface          $urlBuilder,
        StoreManagerInterface $storeManager,
        array                 $components = [],
        array                 $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $imageHelper, $urlBuilder, $components, $data);
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl().$item[$fieldName];
                } else {
                    $url = $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
                }

                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'brand/brand/edit',
                    ['brand_id' => $item['brand_id']]
                );
                $item[$fieldName . '_orig_src'] = $url;
            }
        }
        return $dataSource;
    }

    /**
     * Get Alt text
     *
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
