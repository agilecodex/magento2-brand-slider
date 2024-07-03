<?php
/**
 * @copyright Copyright (c) AgileCodex (https://www.agilecodex.com/)
 * @license https://www.agilecodex.com/license-agreement
 */

declare(strict_types=1);

namespace Acx\BrandSlider\Service;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Factory as ImageFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Acx\BrandSlider\Model\Config;

class ThumbnailFile
{
    /** @var array */
    private $sizeByTypes
        = [
            'thumbnail'   => 75,
            'small_image' => 95,
        ];

    /** @var ImageFactory */
    private $imageProcessorFactory;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var Filesystem */
    private $filesystem;

    /** @var Config */
    private $config;

    /**
     * ThumbnailFile constructor.
     * @param ImageFactory $imageProcessorFactory
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     */
    public function __construct(
        ImageFactory $imageProcessorFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        Config $config
    ) {
        $this->imageProcessorFactory = $imageProcessorFactory;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    /**
     * @param string $imageType
     * @param string $fileName
     *
     * @return bool
     */
    public function hasImage($imageType, $fileName)
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->isExist($this->getImagePath($imageType, $fileName));
    }

    /**
     * @param string $imageType
     * @param string $fileName
     *
     * @throws LocalizedException
     */
    public function createImage($imageType, $fileName)
    {
        $path     = $this->getImagePath($imageType, $fileName);
        $mediaDir = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        $mediaDir->copyFile("acx/tmp/brand/{$fileName}", $path);

        $imageProcessor = $this->imageProcessorFactory->create($mediaDir->getAbsolutePath($path));
        $imageProcessor->keepAspectRatio(true);
        $imageProcessor->keepFrame(true);
        $imageProcessor->keepTransparency(true);
        $imageProcessor->backgroundColor([255, 255, 255]);
        $imageProcessor->constrainOnly(true);
        $imageProcessor->quality(80);
        $imageProcessor->resize($this->getImageSize($imageType));
        $imageProcessor->save();
    }

    /**
     * @param string $imageType
     * @param string $fileName
     *
     * @return string
     */
    public function getImageUrl($imageType, $fileName)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . $this->getImagePath($imageType, $fileName);
    }

    /**
     * @param string $imageType
     *
     * @return string
     */
    public function getPlaceholderPath($imageType)
    {
        return "Magento_Catalog::images/product/placeholder/{$imageType}.jpg";
    }

    /**
     * @param string $imageType
     * @param string $fileName
     *
     * @return string
     */
    private function getImagePath($imageType, $fileName)
    {
        return "acx/brand/{$imageType}/{$fileName}";
    }

    /**
     * @param string $imageType
     *
     * @return mixed
     * @throws LocalizedException
     */
    private function getImageSize($imageType)
    {
        if (!isset($this->sizeByTypes[$imageType])) {
            throw new LocalizedException(__('Unknown image type %1', $imageType));
        }

        if ($imageType == 'thumbnail' && $this->config->isProductPageBrandLogoEnabled()) {
            return $this->config->getProductPageBrandLogoImageWidth();
        }

        return $this->sizeByTypes[$imageType];
    }
}
