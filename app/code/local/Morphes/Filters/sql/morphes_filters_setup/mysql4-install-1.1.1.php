<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/* BASED ON SNIPPET: Resources/Install/upgrade script */
/* @var $installer Morphes_Filters_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->installEntities();

$installer->createEntityTables($this->getTable('morphes_filters/filter'));
/* BASED ON SNIPPET: Resources/Table creation/alteration script */
$table = 'morphes_filters/filter';
$installer->run("
	ALTER TABLE `{$this->getTable($table)}` DROP FOREIGN KEY `FK_{$this->getTable($table)}_store`;
	ALTER TABLE `{$this->getTable($table)}` DROP COLUMN `store_id`;
	ALTER TABLE `{$this->getTable($table)}` DROP COLUMN `increment_id`;
	
	ALTER TABLE `{$this->getTable($table)}` ADD COLUMN ( 
		`code` varchar(255) NOT NULL default ''
	);
	ALTER TABLE `{$this->getTable($table)}` ADD KEY `code` (`code`);
");

$installer->updateDefaultMaskFields(Morphes_Filters_Model_Filter::ENTITY);

$installer->endSetup();