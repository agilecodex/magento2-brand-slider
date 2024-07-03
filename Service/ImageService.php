<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * License:    https://www.agilecodex.com/license-agreement
 * @author   agilecodex.com
 */
namespace Acx\BrandSlider\Service;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

/**
 * Brand logo image Service
 *
 * @api
 */
class ImageService
{
    /** @var Filesystem */
    protected $_filesystem;

    /** @var LoggerInterface */
    protected $_logger;

    /** @var ImageUploader */
    private $imageUploader;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var ThumbnailFile  */
    private $thumbnailFile;

    /** @var AssetRepository */
    private $assertRepository;

    /**
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param ThumbnailFile $thumbnailFile
     * @param AssetRepository $assertRepository
     * @param StoreManagerInterface|null $storeManager
     * @param ImageUploader|null $imageUploader
     */
    public function __construct(
        LoggerInterface $logger,
        Filesystem $filesystem,
        ThumbnailFile $thumbnailFile,
        AssetRepository $assertRepository,
        StoreManagerInterface $storeManager = null,
        ImageUploader $imageUploader = null
    ) {
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->thumbnailFile = $thumbnailFile;
        $this->assertRepository = $assertRepository;
        $this->storeManager = $storeManager ??
            ObjectManager::getInstance()->get(StoreManagerInterface::class);
        $this->imageUploader = $imageUploader ??
            ObjectManager::getInstance()->get(ImageUploader::class);
    }

    /**
     * Gets image name from $value array.
     * Will return empty string in a case when $value is not an array.
     *
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

    /**
     * Do not save empty image value to DB if image was not uploaded.
     *
     * @param \Magento\Framework\DataObject $object
     * @return \Magento\Framework\DataObject $object
     */
    public function beforeSave($object, $attributeName)
    {
        //$attributeName = 'image';

        $value = $object[$attributeName];

        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                /** @var StoreInterface $store */
                $store = $this->storeManager->getStore();
                $baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->getImageUrl($imageName, $attributeName);
                //$value[0]['url'] =  $newImgRelativePath;
                $value[0]['name'] = $imageName;
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        } elseif ($this->fileResidesOutsideCategoryDir($value)) {
            $uri = \Laminas\Uri\UriFactory::factory($value[0]['url']);
            $query = $uri->getPath();
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            //$value[0]['name'] = $value[0]['name'];
        }

        if ($imageName = $this->getUploadedImageName($value)) {
            $object[$attributeName] = $imageName;
        } elseif (!is_string($value)) {
            $object[$attributeName] = null;
        }
        return $object;
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * Check for file path resides outside of category media dir. The URL will be a path including pub/media if true
     *
     * @param array|null $value
     * @return bool
     */
    private function fileResidesOutsideCategoryDir($value)
    {
        if (!is_array($value) || !isset($value[0]['url'])) {
            return false;
        }

        $fileUrl = ltrim($value[0]['url'], '/');
        $baseMediaDir = $this->_filesystem->getUri(DirectoryList::MEDIA);

        if (!$baseMediaDir) {
            return false;
        }

        return strpos($fileUrl, $baseMediaDir) !== false;
    }

    /**
     * Save uploaded file and set its name to category
     *
     * @param \Magento\Framework\DataObject $object
     * @return \Acx\BrandSlider\Model\Brand\Image
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave($object)
    {
        return $this;
    }

    public function getImageUrl(string $imageName, string $imageType = null): string
    {
        $placeholderUrl = $this->assertRepository->getUrl(
            $this->thumbnailFile->getPlaceholderPath(
                $imageType ?: 'small_image'
            )
        );

        if (empty($imageName)) {
            return $placeholderUrl;
        }

        if ($imageType) {
            if (!$this->thumbnailFile->hasImage($imageType, $imageName)) {
                try {
                    $this->thumbnailFile->createImage($imageType, $imageName);
                } catch (\Exception $e) {
                    return $placeholderUrl;
                }
            }

            $image = (string)$this->thumbnailFile->getImageUrl($imageType, $imageName);
        } else {
            /** @var Store $store */
            $store = $this->storeManager->getStore();
            $image = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "acx/brand/{$imageType}/{$imageName}";
        }

        return $image;
    }
}
