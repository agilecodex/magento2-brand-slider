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

namespace Acx\BrandSlider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install schema
 * @category Acx
 * @package  Acx_BrandSlider
 * @module   BrandSlider
 * @author   Wasim haider Chowdhury
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
         * Drop tables if exists
         */
        $installer->getConnection()->dropTable($installer->getTable('acx_brandslider_brand'));

        /*
         * Create table acx_brandslider_brand
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('acx_brandslider_brand')
        )->addColumn(
            'brand_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Brand ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Brand name'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => true, 'default' => 0],
            'Brand order'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Brand status'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Brand image'
        )->addColumn(
            'image_alt',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Brand image alt'
        )->addIndex(
            $installer->getIdxName('acx_brandslider_brand', ['brand_id']),
            ['brand_id']
        )->addIndex(
            $installer->getIdxName('acx_brandslider_brand', ['status']),
            ['status']
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table acx_brandslider_brand
         */
        
        $installer->endSetup();
    }
}
