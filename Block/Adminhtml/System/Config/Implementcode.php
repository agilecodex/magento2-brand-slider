<?php
/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Block\Adminhtml\System\Config;

/**
 * Implement
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Implementcode extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '
		<div class="notices-wrapper">
		        <div class="messages">
		            <div class="message" style="margin-top: 10px;">
		                <strong>'.__('Add code below to a template file.').'</strong><br />
		                $this->getLayout()->createBlock("Acx\BrandSlider\Block\BrandSliderItem")->setBrandSliderId(your_brandslider_id)->toHtml();
		            </div>
		            <div class="message" style="margin-top: 10px;">
		                <strong>'.__('You can put a brandslider on a cms page. Below is an example which we put a brandslider with brandslider_id is your_brandslider_id on a cms page.').'</strong><br />
		                {{block class="Acx\BrandSlider\Block\BrandSliderItem" name="brandslider.brandslidercustom" brandslider_id="your_brandslider_id"}}
		            </div>
		            <div class="message" style="margin-top: 10px;">
		                <strong>'.__('Please copy and paste the code below on one of xml layout files where you want to show the brand. Please replace the your_brandslider_id variable with your own brandslider Id.').'</strong><br />
		                &lt;block class="Acx\BrandSlider\Block\BrandSliderItem"&gt;<br />
                           &nbsp;&nbsp;&lt;action method="setBrandSliderId"&gt;<br />
                               &nbsp;&nbsp;&nbsp;&nbsp;&lt;argument name="brandsliderId" xsi:type="string"&gt;your_brandslider_id&lt;/argument&gt;<br />
                           &nbsp;&nbsp;&lt;/action&gt;<br />
                       &lt;/block>
		            </div>
		        </div>
		</div>';
    }
}
