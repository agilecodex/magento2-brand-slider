<?php

/**
 * Acx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * http://www.agilecodex.com/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Acx
 * @package     Acx_BrandSlider
 * @copyright   Copyright (c) 2016 Acx (http://www.agilecodex.com/)
 * @license     http://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Block\Adminhtml\Brand\Edit\Tab;

use Acx\BrandSlider\Model\Status;

/**
 * Brand Edit tab.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   Wasim haider Chowdhury
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
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_brand = $brand;
        $this->_wysiwygConfig = $wysiwygConfig;
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
            $fieldset->addField('brand_id', 'hidden', ['name' => 'brand_id']);
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
        
        $elements['image'] = $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Brand Image'),
                'label' => __('Brand Image'),
                'name' => 'image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                'required' => true,
            ]
        );
        
        $elements['image_alt'] = $fieldset->addField(
            'image_alt',
            'text',
            [
                'title' => __('Alt Text'),
                'label' => __('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
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
