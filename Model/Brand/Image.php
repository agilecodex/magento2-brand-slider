<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * License:    https://www.agilecodex.com/license-agreement
 * @author   agilecodex.com
 */
namespace Acx\BrandSlider\Model\Brand;

use Acx\BrandSlider\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\File\Uploader as FileUploader;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Brand logo image model
 *
 * @see Magento\Catalog\Model\Category\Attribute\Backend\Image
 * @api
 */
class Image
{
    /** @var UploaderFactory */
    protected $_uploaderFactory;

    /** @var Filesystem */
    protected $_filesystem;

    /** @var UploaderFactory */
    protected $_fileUploaderFactory;

    /** @var LoggerInterface */
    protected $_logger;

    /** @var ImageUploader */
    private $imageUploader;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var FileUploader */
    private $fileUploader;

    /**
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        LoggerInterface $logger,
        Filesystem $filesystem,
        UploaderFactory $fileUploaderFactory,
        FileUploader $fileUploader,
        StoreManagerInterface $storeManager = null,
        ImageUploader $imageUploader = null
    ) {
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->fileUploader = $fileUploader;
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
     * Check that image name exists in catalog/category directory and return new image name if it already exists.
     *
     * @param string $imageName
     * @return string
     */
    private function checkUniqueImageName(string $imageName): string
    {
        $mediaDirectory = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $imageAbsolutePath = $mediaDirectory->getAbsolutePath(
            $this->imageUploader->getBasePath() . DIRECTORY_SEPARATOR . $imageName
        );

        return $this->fileUploader->getNewFilename($imageAbsolutePath);
    }

    /**
     * Do not save empty image value to DB if image was not uploaded.
     *
     * @param \Magento\Framework\DataObject $object
     * @return \Magento\Framework\DataObject $object
     */
    public function beforeSave($object)
    {
        $attributeName = 'image';

        $value = $object[$attributeName];

        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                /** @var StoreInterface $store */
                $store = $this->storeManager->getStore();
                $baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $value[0]['url'] = '/' . $baseMediaDir . '/' . $newImgRelativePath;
                $value[0]['name'] = $value[0]['url'];
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        } elseif ($this->fileResidesOutsideCategoryDir($value)) {
            //todo
            $uri = \Laminas\Uri\UriFactory::factory($value[0]['url']);
            $query = $uri->getPath();
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            $value[0]['name'] = $value[0]['url'];
        }

        if ($imageName = $this->getUploadedImageName($value)) {
            if (!$this->fileResidesOutsideCategoryDir($value)) {
                $imageName = $this->checkUniqueImageName($imageName);
            }
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
}
