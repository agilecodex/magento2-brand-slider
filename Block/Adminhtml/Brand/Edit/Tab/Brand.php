<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Block\Adminhtml\Brand\Edit\Tab;

use Acx\BrandSlider\Model\Status;

/**
 * Brand Edit tab.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Brand extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * @var \Acx\BrandSlider\Model\Brand
     */
    protected $_brand;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
    
    protected $_systemStore;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \Acx\BrandSlider\Model\Brand $brand
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Acx\BrandSlider\Model\Brand $brand,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = [],
        \Magento\Store\Model\System\Store $systemStore
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_brand = $brand;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
        
        \Magento\Framework\Data\Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Acx\BrandSlider\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout().'_fieldset_element'
            )
        );

        return $this;

    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $brandAttributes = $this->_brand->getStoreAttributes();
        $brandAttributesInStores = ['store_id' => ''];

        foreach ($brandAttributes as $brandAttribute) {
            $brandAttributesInStores[$brandAttribute.'_in_store'] = '';
        }

        $dataObj = $this->_objectFactory->create(
            ['data' => $brandAttributesInStores]
        );
        $model = $this->_coreRegistry->registry('brand');

        $dataObj->addData($model->getData());
        
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix($this->_brand->getFormFieldHtmlIdPrefix());

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Brand Information')]);

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $elements = [];
        $elements['name'] = $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );
        
        $fieldset->addType('image', '\Acx\BrandSlider\Block\Adminhtml\Brand\Helper\Image');
        
        $image_path = null;
        if(preg_match('~\.(png|gif|jpe?g|bmp)~i', $this->_brand->getImage()))
              $image_path =  $this->_brand->getImage();
        
        if (!$this->_storeManager->isSingleStoreMode()) {
            $elements['store_id'] = $fieldset->addField(
                    'store_id', 'multiselect', [
                'name' => 'store_id[]',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                'disabled' => false,
                'value' => (null !== $model->getStoreId() ? $model->getStoreId() : 0)
                    ]
            );
            $renderer = $this->getLayout()->createBlock(
                    'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $elements['store_id']->setRenderer($renderer);
        } else {
            $elements['store_id'] = $fieldset->addField(
                    'store_id', 'hidden', ['name' => 'store_id[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }



        $elements['image'] = $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Brand Image'),
                'label' => __('Brand Image'),
                'name' => 'image',
                'path' => $image_path,
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                'required' => true,
                'value' => $image_path,
                'renderer' => 'Acx\BrandSlider\Block\Adminhtml\Brand\Helper\Renderer\Image'
            ]
        )->setAfterElementHtml('
        <script>    

            require([
                 "jquery",
            ], function($){
                $(document).ready(function () {                
                    if($("#page_image").attr("value")){
                        $("#page_image").removeClass("required-file");
                    }else{
                        $("#page_image").addClass("required-file");
                    }
                    $( "#page_image" ).attr( "accept", "image/x-png,image/gif,image/jpeg,image/jpg,image/png" );                    
                    
                });
              });
       </script>
    ');
        
        $elements['image_alt'] = $fieldset->addField(
            'image_alt',
            'text',
            [
                'title' => __('Alt Text'),
                'label' => __('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
                'required' => true
            ]
        );
        
        $elements['Sort Oder'] = $fieldset->addField(
            'sort_order',
            'text',
            [
                'label' => __('Sort Oder'),
                'title' => __('Sort Oder'),
                'name' => 'sort_order'
            ]
        );
        
        $elements['status'] = $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Brand Status'),
                'name' => 'status',
                'options' => Status::getAvailableStatuses(),
            ]
        );
        
        $form->addValues($dataObj->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->_coreRegistry->registry('brand');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getBrand()->getId()
            ? __("Edit Brand '%1'", $this->escapeHtml($this->getBrand()->getName())) : __('New Brand');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Brand Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Brand Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
