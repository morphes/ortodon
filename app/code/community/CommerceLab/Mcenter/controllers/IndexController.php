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

class CommerceLab_Mcenter_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        //// check if this category is allowed to view
        if ($category = $this->getRequest()->getParam('category')) {
            $collection = Mage::getModel('clmcenter/category')->getCollection()
                ->addFieldToFilter('url_key', $category)
                ->addStoreFilter(Mage::app()->getStore()->getId());
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('clmcenter')->getRoute());
            }
        }
        if ($tag = $this->getRequest()->getParam('q')) {
            $collection = Mage::getModel('clmcenter/mcenter')->getCollection()
                            ->setOrder('mcenter_time', 'asc');
            if (count(Mage::app()->getStores()) > 1) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter_store');
                $collection->getSelect()->join($tableName, 'main_table.mcenter_id = ' . $tableName . '.mcenter_id','store_id');
                $collection->getSelect()->where('('.$tableName . '.store_id = '. Mage::app()->getStore()->getId(). ' OR '.$tableName.'.store_id = 0 )');
            }
            $tag = urldecode($tag);
            $collection->getSelect()->where("main_table.tags LIKE '%". $tag . "%'");
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('clmcenter')->getRoute());
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function mainviewAction()
    {
        //// check if this category is allowed to view
        if ($category = $this->getRequest()->getParam('category')) {
            $collection = Mage::getModel('clmcenter/category')->getCollection()
                ->addFieldToFilter('url_key', $category)
                ->addStoreFilter(Mage::app()->getStore()->getId());
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('clmcenter')->getRoute());
            }
        }
        if ($tag = $this->getRequest()->getParam('q')) {
            $collection = Mage::getModel('clmcenter/mcenter')->getCollection()
                ->setOrder('mcenter_time', 'asc');
            if (count(Mage::app()->getStores()) > 1) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter_store');
                $collection->getSelect()->join($tableName, 'main_table.mcenter_id = ' . $tableName . '.mcenter_id','store_id');
                $collection->getSelect()->where('('.$tableName . '.store_id = '. Mage::app()->getStore()->getId(). ' OR '.$tableName.'.store_id = 0 )');
            }
            $tag = urldecode($tag);
            $collection->getSelect()->where("main_table.tags LIKE '%". $tag . "%'");
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('clmcenter')->getRoute());
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }
}
