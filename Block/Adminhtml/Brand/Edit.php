<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */

namespace Acx\BrandSlider\Block\Adminhtml\Brand;

/**
 * Brand block edit form container.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container {

    /**
     * _construct
     * @return void
     */
    protected function _construct() {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Acx_BrandSlider';
        $this->_controller = 'adminhtml_brand';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Brand'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        if ($this->getRequest()->getParam('current_brandslider_id')) {
            $this->buttonList->remove('save');
            $this->buttonList->remove('delete');

            $this->buttonList->remove('back');
            $this->buttonList->add(
                    'close_window', [
                'label' => __('Close Window'),
                'onclick' => 'window.close();',
                    ], 10
            );

            $this->buttonList->add(
                    'save_and_continue', [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'onclick' => 'customsaveAndContinueEdit()',
                    ], 10
            );

            $this->buttonList->add(
                    'save_and_close', [
                'label' => __('Save and Close'),
                'class' => 'save_and_close',
                'onclick' => 'saveAndCloseWindow()',
                    ], 10
            );

            $this->_formScripts[] = "
				require(['jquery'], function($){
					$(document).ready(function(){
						var input = $('<input class=\"custom-button-submit\" type=\"submit\" hidden=\"true\" />');
						$(edit_form).append(input);

						window.customsaveAndContinueEdit = function (){
							edit_form.action = '" . $this->getSaveAndContinueUrl() . "';
							$('.custom-button-submit').trigger('click');

				        }

			    		window.saveAndCloseWindow = function (){
			    			edit_form.action = '" . $this->getSaveAndCloseWindowUrl() . "';
							$('.custom-button-submit').trigger('click');
			            }
					});
				});
			";

            if ($brandId = $this->getRequest()->getParam('entity_id')) {
                $this->_formScripts[] = '
					window.entity_id = ' . $brandId . ';
				';
            }
        } else {
            $this->buttonList->add(
                    'save_and_continue', [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ],
                    ], 10
            );
        }

        if ($this->getRequest()->getParam('saveandclose')) {
            $this->_formScripts[] = 'window.close();';
        }
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndContinueUrl() {
        return $this->getUrl(
                        '*/*/save', [
                    '_current' => true,
                    'back' => 'edit',
                    'tab' => '{{tab_id}}',
                    'store' => $this->getRequest()->getParam('store'),
                    'entity_id' => $this->getRequest()->getParam('entity_id'),
                    'current_brandslider_id' => $this->getRequest()->getParam('current_brandslider_id'),
                        ]
        );
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndCloseWindowUrl() {
        return $this->getUrl(
                        '*/*/save', [
                    '_current' => true,
                    'back' => 'edit',
                    'tab' => '{{tab_id}}',
                    'store' => $this->getRequest()->getParam('store'),
                    'entity_id' => $this->getRequest()->getParam('entity_id'),
                    'current_brandslider_id' => $this->getRequest()->getParam('current_brandslider_id'),
                    'saveandclose' => 1,
                        ]
        );
    }

}
