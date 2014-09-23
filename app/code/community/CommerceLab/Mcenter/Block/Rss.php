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

class CommerceLab_Mcenter_Block_Rss extends Mage_Rss_Block_Abstract
{
    protected function _toHtml()
    {
        $rssObj = Mage::getModel('rss/rss');

        $data = array('title' => 'Mcenter',
            'description' => 'Mcenter',
            'link'        => $this->getUrl('clmcenter/rss'),
            'charset'     => 'UTF-8',
            'language'    => Mage::getStoreConfig('general/locale/code')
            );

        $rssObj->_addHeader($data);

        $collection = Mage::getModel('clmcenter/mcenter')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('created_time ', 'desc');

        $categoryId = $this->getRequest()->getParam('category');

        if ($categoryId && $category = Mage::getSingleton('clmcenter/category')->load($categoryId)) {
            $collection->addCategoryFilter($category->getUrlKey());
        }

        $collection->setPageSize((int)Mage::getStoreConfig('clmcenter/rss/posts'));
        $collection->setCurPage(1);

        if ($collection->getSize()>0) {
            foreach ($collection as $item) {
                $data = array(
                            'title'         => $item->getTitle(),
                            'link'          => $this->getUrl("clmcenter/mcenteritem/view", array("id" => $item->getId())),
                            'description'   => $item->getShortContent(),
                            'lastUpdate'    => strtotime($item->getMcenterTime()),
                            );

                $rssObj->_addEntry($data);
            }
        } else {
             $data = array('title' => Mage::helper('clmcenter')->__('Не могу получить ленту'),
                    'description' => Mage::helper('clmcenter')->__('Не могу получить ленту'),
                    'link'        => Mage::getUrl(),
                    'charset'     => 'UTF-8',
                );
             $rssObj->_addHeader($data);
        }

        return $rssObj->createRssXml();
    }
}
