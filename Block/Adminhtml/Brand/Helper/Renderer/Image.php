<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Block\Adminhtml\Brand\Helper\Renderer;

//use Acx\BrandSlider\Model\Brand\Image as BrandImage;

/**
 * Image renderer.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer {

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * brand factory.
     *
     * @var \Acx\BrandSlider\Model\BrandFactory
     */
    protected $_brandFactory;

    /**
     * image model
     *
     * @var \Acx\BrandSlider\Model\Brand\Image
     */
    protected $imageModel;
    protected $_assetRepo;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param \Acx\BrandSlider\Model\BrandFactory $brandFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context, 
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Acx\BrandSlider\Model\BrandFactory $brandFactory, 
        \Acx\BrandSlider\Model\Brand\Image $imageModel, 
        \Magento\Framework\View\Asset\Repository $assetRepo, 
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_brandFactory = $brandFactory;
        $this->imageModel = $imageModel;
        $this->_assetRepo = $assetRepo;
    }

    /**
     * Render action.
     *
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row) {
        $storeViewId = $this->getRequest()->getParam('store');
        $brand = $this->_brandFactory->create()->setStoreViewId($storeViewId)->load($row->getId());
        
        if (preg_match('~\.(png|gif|jpe?g|bmp)~i', $brand->getImage())) {
            $srcImage = $this->imageModel->getBaseUrl() . $brand->getImage();
        } else {
            $srcImage = $this->_assetRepo->getUrl("Acx_BrandSlider::images/brand-logo-blank.png");
        }

        return '<image max-width="150" height="50" src ="' . $srcImage . '" alt="' . $brand->getAltText() . '" >';
    }

}
