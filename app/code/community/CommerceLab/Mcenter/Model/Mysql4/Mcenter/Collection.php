<?php
/**
 * CommerceLab Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the CommerceLab License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://commerce-lab.com/LICENSE.txt
 *
 * @category   CommerceLab
 * @package    CommerceLab_Mcenter
 * @copyright  Copyright (c) 2012 CommerceLab Co. (http://commerce-lab.com)
 * @license    http://commerce-lab.com/LICENSE.txt
 */

class CommerceLab_Mcenter_Model_Mysql4_Mcenter_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('clmcenter/mcenter');
    }

    public function addEnableFilter($status)
    {
        $this->getSelect()
            ->where('status = ?', $status);
        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $this->getSelect()->join(
            array('mcenter_category_table' => $this->getTable('mcenter_category')),
            'main_table.mcenter_id = mcenter_category_table.mcenter_id',
            array()
        )->join(
            array('category_table' => $this->getTable('category')),
            'mcenter_category_table.category_id = category_table.category_id',
            array()
        )->join(
            array('category_store_table' => $this->getTable('category_store')),
            'category_table.category_id = category_store_table.category_id',
            array()
        )
        ->where('category_table.url_key = "'.$categoryId.'"')
        ->where('category_store_table.store_id in (?)', array(0, Mage::app()->getStore()->getId()))
        ;
        return $this;
    }

    public function addStoreFilter($store)
    {
        $this->getSelect()->join(
            array('mcenter_store_table' => $this->getTable('mcenter_store')),
            'main_table.mcenter_id = mcenter_store_table.mcenter_id',
            array()
        )
        ->where('mcenter_store_table.store_id in (?)', array(0, $store));
        $this->getSelect()->distinct();
        return $this;
    }

    protected function _afterLoad()
    {
        foreach($this as $item) {
            $stores = $this->lookupStoreIds($item->getId());
            $item->setData('store_id', $stores);
        }
        return parent::_afterLoad();
    }

    public function lookupStoreIds($objectId)
    {
        $adapter = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter_store');
        $select  = $adapter->select()
        ->from($tableName, 'store_id')
        ->where('mcenter_id = ?',(int)$objectId);

        return $adapter->fetchCol($select);
    }
}
