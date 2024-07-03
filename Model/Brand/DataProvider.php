<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * License:    https://www.agilecodex.com/license-agreement
 * @author   agilecodex.com
 */
namespace Acx\BrandSlider\Model\Brand;

use Acx\BrandSlider\Api\Data\BrandInterface;
use Acx\BrandSlider\Model\Brand as BrandModel;
use Acx\BrandSlider\Model\ResourceModel\Brand\Collection as BrandCollection;
use Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory;
use Acx\BrandSlider\Service\ImageService;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * @inheritdoc
 */
class DataProvider extends AbstractDataProvider
{
    /** @var BrandCollection */
    protected $collection;

    /** @var array */
    protected $loadedData;

    /** @var StoreManager */
    private $storeManager;

    /** @var ImageHelper */
    protected $imageHelper;

    /** @var ReadInterface */
    protected $mediaDirectory;

    /** @var ImageService */
    protected $imageService;

    /** @var ImageUploader */
    protected $imageUploader;

    protected $mime;

    /**
     * Brand slider data provider Constructor
     *
     * @param ImageUploader $imageUploader
     * @param Filesystem $filesystem
     * @param Mime $mime
     * @param CollectionFactory $sliderCollectionFactory
     * @param StoreManager $storeManager
     * @param ImageHelper $imageHelper
     * @param ImageService $imageService
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        ImageUploader $imageUploader,
        Filesystem $filesystem,
        Mime $mime,
        CollectionFactory $sliderCollectionFactory,
        StoreManager $storeManager,
        ImageHelper $imageHelper,
        ImageService $imageService,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->imageService = $imageService;
        $this->imageUploader = $imageUploader;
        $this->mime = $mime;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $sliderCollectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var BrandModel $brand */
        foreach ($items as $brand) {
            $data = $brand->getData();
            $data = $this->prepareImageData($data, BrandInterface::LOGO);
            $this->loadedData[$brand->getId()] = $data;
        }

        return $this->loadedData;
    }

    /**
     * @param array  $data
     * @param string $imageKey
     *
     * @return array
     */
    protected function prepareImageData($data, $imageKey)
    {
        if (isset($data[$imageKey])) {
            $imageName = $data[$imageKey];
            unset($data[$imageKey]);
            if ($this->mediaDirectory->isExist($this->getFilePath($imageName, $imageKey))) {
                $data[$imageKey] = [
                    [
                        'name' => $imageName,
                        'url'  => $this->imageService->getImageUrl($imageName, $imageKey),
                        'size' => $this->mediaDirectory->stat($this->getFilePath($imageName, $imageKey))['size'],
                        'type' => $this->getMimeType($imageName, $imageKey),
                    ],
                ];
            }
        }

        return $data;
    }

    /**
     * @param string $fileName
     * @param string $imageKey
     *
     * @return string
     */
    protected function getMimeType($fileName, $imageKey)
    {
        $absoluteFilePath = $this->mediaDirectory->getAbsolutePath($this->getFilePath($fileName, $imageKey));

        return $this->mime->getMimeType($absoluteFilePath);
    }

    /**
     * @param string $fileName
     * @param string $imgType
     *
     * @return string
     */
    protected function getFilePath($fileName, $imgType)
    {
        return $this->imageUploader->getFilePath($this->imageUploader->getBasePath() . '/' . $imgType, $fileName);
    }
}
