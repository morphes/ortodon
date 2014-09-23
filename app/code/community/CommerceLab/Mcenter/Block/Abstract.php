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

class CommerceLab_Mcenter_Block_Abstract extends Mage_Core_Block_Template
{
    protected $_pagesCount = null;
    protected $_currentPage = null;
    protected $_itemsOnPage = 10;
    protected $_itemsLimit;
    protected $_pages;
    protected $_latestItemsCount = 2;
    protected $_showFlag = 0;

    protected function _construct()
    {
        $this->_currentPage = $this->getRequest()->getParam('page');
        if (!$this->_currentPage) {
            $this->_currentPage=1;
        }

        $itemsPerPage = (int)Mage::getStoreConfig('clmcenter/mcenter/itemsperpage');
        if ($itemsPerPage > 0) {
            $this->_itemsOnPage = $itemsPerPage;
        }

        $itemsLimit = (int)$this->getData('itemslimit');
        if ($itemsLimit==null) {
            $itemsLimit = (int)Mage::getStoreConfig('clmcenter/mcenter/itemslimit');
        }
        if ($itemsLimit > 0) {
            $this->_itemsLimit = $itemsLimit;
        } else {
            $this->_itemsLimit = null;
        }

        $latestItemsCount = (int)Mage::getStoreConfig('clmcenter/mcenter/latestitemscount');
        if ($latestItemsCount > 0) {
            $this->_latestItemsCount = $latestItemsCount;
        }
    }

    public function getCategoryKey()
    {
        $category = $this->getData('category');
        if ($category==null) {
            $category = $this->getRequest()->getParam('category');
        }
        if (!preg_match('/^[0-9A-Za-z\-\_]+$/i', $category)) {
            return null;
        }
        return $category;
    }

    public function getCategory()
    {
        $category = $this->getData('category');
        if ($category==null) {
            $category = $this->getRequest()->getParam('category');
        }
        if (!preg_match('/^[0-9A-Za-z\-\_]+$/i', $category)) {
            return null;
        }
        return $category;
    }

    public function getMcenterItems($count = false)
    {
        $this->_showFlag = 1;
        $collection = Mage::getModel('clmcenter/mcenter')->getCollection();
        $category = $this->getCategoryKey();
        if ($category!=null) {

            $catCollection = Mage::getModel('clmcenter/category')->getCollection()
                ->addFieldToFilter('url_key', $category)
                ->addStoreFilter(Mage::app()->getStore()->getId());
            $categoryId = $catCollection->getData();
            if ($categoryId[0]['category_id']) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter_category');
                $collection->getSelect()->join($tableName, 'main_table.mcenter_id = ' . $tableName . '.mcenter_id','category_id');
                $collection->getSelect()->where($tableName . '.category_id =?', $categoryId[0]['category_id']);
                if($count) {
                    $collection->getSelect()->limit($count);
                }
            }
        } else {
            $collection->addStoreFilter(Mage::app()->getStore()->getId());
        }
        if ($tag = $this->getRequest()->getParam('q')) {
            $collection = Mage::getModel('clmcenter/mcenter')->getCollection()->setOrder('mcenter_time', 'desc');
            if (count(Mage::app()->getStores()) > 1) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter_store');
                $collection->getSelect()->join($tableName, 'main_table.mcenter_id = ' . $tableName . '.mcenter_id','store_id');
                $collection->getSelect()->where('('. $tableName . '.store_id = '. Mage::app()->getStore()->getId(). ' OR store_id = 0)');
            }
            $tag = urldecode($tag);
            $collection->getSelect()->where("tags LIKE '%". $tag . "%'");
        }

        $collection
            ->addEnableFilter(1)
            ->addFieldToFilter('publicate_from_time', array('or' => array(
                0 => array('date' => true, 'to' => date('Y-m-d H:i:s')),
                1 => array('is' => new Zend_Db_Expr('null'))),
                ), 'left')
            ->addFieldToFilter('publicate_to_time', array('or' => array(
                0 => array('date' => true, 'from' => date('Y-m-d H:i:s')),
                1 => array('is' => new Zend_Db_Expr('null'))),
                ), 'left')
            ->setOrder('mcenter_time ', 'desc');
        if ($this->_itemsLimit!=null && $this->_itemsLimit<$collection->getSize()) {
            $this->_pagesCount = ceil($this->_itemsLimit/$this->_itemsOnPage);
        } else {
            $this->_pagesCount = ceil($collection->getSize()/$this->_itemsOnPage);
        }
        for ($i=1; $i<=$this->_pagesCount;$i++) {
            $this->_pages[] = $i;
        }
        $this->setLastPageNum($this->_pagesCount);

        $offset = $this->_itemsOnPage*($this->_currentPage-1);
        if ($this->_itemsLimit!=null) {
            $_itemsCurrentPage = $this->_itemsLimit - $offset;
            if ($_itemsCurrentPage > $this->_itemsOnPage) {
                $_itemsCurrentPage = $this->_itemsOnPage;
            }
            $collection->getSelect()->limit($_itemsCurrentPage, $offset);
        } else {
            $collection->getSelect()->limit($this->_itemsOnPage, $offset);
        }
        if($count) {
            $collection->getSelect()->limit($count);
        }
        foreach ($collection as $item) {
            $comments = Mage::getModel('clmcenter/comment')->getCollection()
                ->addMcenterFilter($item->getMcenterId())
                ->addApproveFilter(CommerceLab_Mcenter_Helper_Data::APPROVED_STATUS);
            $item->setCommentsCount(count($comments));
        }
        return $collection;
    }



    public function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else {
                        if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                            // if tag is an opening tag
                        } else {
                            if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                                // add tag to the beginning of $open_tags list
                                array_unshift($open_tags, strtolower($tag_matchings[1]));
                            }
                        }
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }

    public function getLatestMcenterItems()
    {
        $collection = Mage::getModel('clmcenter/mcenter')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId());
        $collection->setPageSize($this->_latestItemsCount);
        $collection
            ->addEnableFilter(1)
            ->addFieldToFilter('publicate_from_time', array('or' => array(
                0 => array('date' => true, 'to' => date('Y-m-d H:i:s')),
                1 => array('is' => new Zend_Db_Expr('null'))),
                ), 'left')
            ->addFieldToFilter('publicate_to_time', array('or' => array(
                0 => array('date' => true, 'from' => date('Y-m-d H:i:s')),
                1 => array('is' => new Zend_Db_Expr('null'))),
                ), 'left')
            ->setOrder('mcenter_time ', 'desc');
        return $collection;
    }

    public function getCategories()
    {
        $collection = Mage::getModel('clmcenter/category')->getCollection()
        ->addStoreFilter(Mage::app()->getStore()->getId())
        ->setOrder('sort_id', 'asc');

        foreach ($collection as $item) {
            $item->setUrl(Mage::getUrl(Mage::helper('clmcenter')->getRoute()).'category/'.$item->getUrlKey().Mage::helper('clmcenter')->getMcenteritemUrlSuffix());
        }
        return $collection;
    }

    public function getTopLink()
    {
        $route = Mage::helper('clmcenter')->getRoute();
        $title = Mage::helper('clmcenter')->__(Mage::getStoreConfig('clmcenter/mcenter/title'));
        if ($this->getParentBlock() && $this->getParentBlock()!=null) {
            $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-mcenter"');
        }
    }

    public function getItemUrl($itemId) {
        return $this->getUrl(Mage::helper('clmcenter')->getRoute().'/mcenteritem/view', array('id' => $itemId));
    }

    public function isFirstPage()
    {
        if ($this->_currentPage==1) {
            return true;
        }
        return false;
    }

    public function isLastPage()
    {
        if ($this->_currentPage==$this->_pagesCount) {
            return true;
        }
        return false;
    }

    public function isPageCurrent($page)
    {
        if ($page==$this->_currentPage) {
            return true;
        }
        return false;
    }

    public function getPageUrl($page)
    {
        if (Mage::app()->getRequest()->getModuleName()==Mage::helper('clmcenter')->getRoute()) {
            if ($category = $this->getCategoryKey()) {
                return $this->getUrl('*', array('category' => $category, 'page' => $page));
            } else {
                return $this->getUrl('*', array('page' => $page));
            }
        } else {
            if (strstr( Mage::helper("core/url")->getCurrentUrl(), '?')) {
                $sign = '&';
            } else {
                $sign = '?';
            }
            if (strstr( Mage::helper("core/url")->getCurrentUrl(),'page=' )) {
                return preg_replace('(page=[0-9]+)', 'page='.$page, Mage::helper("core/url")->getCurrentUrl());
            } else {
                return Mage::helper("core/url")->getCurrentUrl().$sign.'page='.$page;
            }
        }
    }

    public function getNextPageUrl()
    {
        $page = $this->_currentPage+1;
        return $this->getPageUrl($page);
    }

    public function getPreviousPageUrl()
    {
        $page = $this->_currentPage-1;
        return $this->getPageUrl($page);
    }

    public function getPages()
    {
        return $this->_pages;
    }

    public function getCurrentCategory()
    {
        if ($this->getCategoryKey()) {
            $categories = Mage::getModel('clmcenter/category')
                ->getCollection()
                ->addFieldToFilter('url_key', $this->getCategoryKey())
                ->setPageSize(1);
            $category = $categories->getFirstItem();
            return $category;
        }
        return null;
    }

    protected function _toHtml()
    {
        $html = parent::_toHtml();
        if ($this->_showFlag==1) {
            $html = $html;
        }

        return $html;
    }

    public function getCategoryByMcenter($id)
    {
        $data = Mage::getModel('clmcenter/category')->getCategoryByMcenterId($id);
        $data = new Varien_Object($data);
        $collection = Mage::getModel('clmcenter/category')->getCollection()
        ->addStoreFilter(Mage::app()->getStore()->getId());
        if ($data->getData('0/category_id')!= NULL) {
            $collection->getSelect()->where('main_table.category_id =' . $data->getData('0/category_id'));
            $category = $collection->getFirstItem();
            return $category;
        } else {
            $category = $collection->getFirstItem();
            return $category->setData('title','');
        }
    }
}
