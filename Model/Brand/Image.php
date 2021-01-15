<?php
namespace Acx\BrandSlider\Model\Brand;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class Image
{
    /**
     * media sub folder
     * @var string
     */
    protected $subDir = 'acx/brandslider';
    const BASE_MEDIA_PATH = 'acx/brandslider/images';
    
    /**
     * url builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @param UrlInterface $urlBuilder
     * @param Filesystem $fileSystem
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Filesystem $fileSystem
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->fileSystem = $fileSystem;
    }
    /**
     * get images base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        //return $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]).self::BASE_MEDIA_PATH;
        return $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
    }
    /**
     * get base image dir
     *
     * @return string
     */
    public function getBaseDir($path = null)
    {
        //return $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath(self::BASE_MEDIA_PATH);
        return $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($path);
    }
}