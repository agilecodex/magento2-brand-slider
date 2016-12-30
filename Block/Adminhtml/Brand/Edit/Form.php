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

namespace Acx\BrandSlider\Block\Adminhtml\Brand\Edit;

/**
 * Adminhtml locator edit form block.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   Wasim haider Chowdhury
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            array(
                'data' => array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', ['store' => $this->getRequest()->getParam('store')]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ),
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
