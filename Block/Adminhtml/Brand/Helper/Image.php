<?php
namespace Acx\BrandSlider\Block\Adminhtml\Brand\Helper;
use Magento\Framework\Data\Form\Element\Image as ImageField;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\CollectionFactory as ElementCollectionFactory;
use Magento\Framework\Escaper;
use Acx\BrandSlider\Model\Brand\Image as BrandImage;
use Magento\Framework\UrlInterface;

/**
 * @method string getValue()
 */
class Image extends ImageField
{
    /**
     * image model
     *
     * @var \Acx\BrandSlider\Model\Brand\Image
     */
    protected $imageModel;

    /**
     * @param BrandImage $imageModel
     * @param ElementFactory $factoryElement
     * @param ElementCollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        BrandImage $imageModel,
        ElementFactory $factoryElement,
        ElementCollectionFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $urlBuilder,
        \Magento\Framework\View\Asset\Repository $assetRepo, 
        $data = []
    )
    {
        $this->imageModel = $imageModel;
        $this->_assetRepo = $assetRepo;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $urlBuilder, $data);
    }
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        //valid image
        if ($this->getValue() && preg_match('~\.(png|gif|jpe?g|bmp)~i', $this->getValue())) {
            $url = $this->imageModel->getBaseUrl().$this->getValue();
        } else {
            $url = $this->_assetRepo->getUrl("Acx_BrandSlider::images/brand-logo-blank.png");
        }
        return $url;
    }
}