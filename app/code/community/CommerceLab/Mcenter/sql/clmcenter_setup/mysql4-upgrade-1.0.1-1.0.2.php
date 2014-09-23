<?php

$installer = $this;
/** @var $installer Enterprise_CatalogEvent_Model_Resource_Setup */


$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('clmcenter/category'),
                'parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true,
                                                        'nullable'  => false,
                                                        'default'   => '0',), 'Parent Category ID');
$installer->getConnection()
    ->addColumn($installer->getTable('clmcenter/category'),
        'level', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Tree Level');

$installer->getConnection()
    ->addColumn($installer->getTable('clmcenter/category'),
                'sort_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true,
                                                        'nullable'  => false,
                                                        'default'   => '0',), 'Sort ID');

$installer->run("update {$this->getTable('clmcenter/category')} set parent_id = 0 where parent_id is null");
$installer->run("update {$this->getTable('clmcenter/category')} set sort_id = 0 where sort_id is null");
$installer->run("update {$this->getTable('clmcenter/category')} set level = 0 where level is null");
$installer->endSetup();
