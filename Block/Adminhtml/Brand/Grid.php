<?php

/**
 * This source file is subject to the agilecodex.com license that is
 * available through the world-wide-web at this URL:
 * https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Block\Adminhtml\Brand;

use Acx\BrandSlider\Model\Status;

/**
 * Brand grid.
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   dev@agilecodex.com
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * brand collection factory.
     *
     * @var \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        array $data = []
    ) {
        $this->_brandCollectionFactory = $brandCollectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('brandGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $storeViewId = $this->getRequest()->getParam('store');

        /** @var \Acx\BrandSlider\Model\ResourceModel\Brand\Collection $collection */
        $collection = $this->_brandCollectionFactory->create()->setStoreViewId($storeViewId);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Brand ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
                    'store_id',
                    [
                        'header' => __('Store Views'),
                        'index' => 'store_id',                        
                        'type' => 'store',
                        'store_all' => true,
                        'store_view' => true,
                        'renderer'=>  'Acx\BrandSlider\Block\Adminhtml\Brand\Edit\Tab\Renderer\Store',
                        'filter_condition_callback' => [$this, '_filterStoreCondition']
                    ]
                );
        $this->addColumn(
            'name',
            [
                'header' => __('Brand Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'class' => 'xxx',
                'width' => '50px',
                'filter' => false,
                'renderer' => 'Acx\BrandSlider\Block\Adminhtml\Brand\Helper\Renderer\Image',
            ]
        );

        $this->addColumn(
            'image_alt',
            [
                'header' => __('Image Alt'),
                'index' => 'image_alt',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        
        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Status::getAvailableStatuses(),
            ]
        );
        
        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index' => 'sort_order',
                'filter' => false,
            ]
        );
        
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * get brandslider vailable option
     *
     * @return array
     */

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('brand');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('brandslider/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = Status::getAvailableStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('brandslider/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['entity_id' => $row->getId()]
        );
    }
}
