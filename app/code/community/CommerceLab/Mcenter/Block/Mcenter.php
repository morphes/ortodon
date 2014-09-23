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

class CommerceLab_Mcenter_Block_Mcenter extends CommerceLab_Mcenter_Block_Abstract
{
    protected function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            // show breadcrumbs
            $moduleName = $this->getRequest()->getModuleName();
            $showBreadcrumbs = (int)Mage::getStoreConfig('clmcenter/mcenter/showbreadcrumbs');
            if ($showBreadcrumbs && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) && ($moduleName=='clmcenter')) {
                $breadcrumbs->addCrumb('home',
                    array(
                    'label'=>Mage::helper('clmcenter')->__('Главная'),
                    'title'=>Mage::helper('clmcenter')->__('На главную'),
                    'link'=> Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
                $mcenterBreadCrumb = array(
                    'label'=>Mage::helper('clmcenter')->__(Mage::getStoreConfig('clmcenter/mcenter/title')),
                    'title'=>Mage::helper('clmcenter')->__('Вернуться на ' .Mage::helper('clmcenter')->__('Mcenter')),
                    );
                if ($this->getCategoryKey()) {
                    $mcenterBreadCrumb['link'] = Mage::getUrl(Mage::helper('clmcenter')->getRoute());
                }
                $breadcrumbs->addCrumb('mcenter', $mcenterBreadCrumb);

                if ($this->getCategoryKey()) {
                    $categories = Mage::getModel('clmcenter/category')
                        ->getCollection()
                        ->addFieldToFilter('url_key', $this->getCategoryKey())
                        ->setPageSize(1);
                    $category = $categories->getFirstItem();
                    $breadcrumbs->addCrumb('category',
                        array(
                        'label'=>$category->getTitle(),
                        'title'=>Mage::helper('clmcenter')->__('Вернуться на главную'),
                        ));
                }
            }

            if ($moduleName=='clmcenter') {
                // set default meta data
                $head->setTitle(Mage::getStoreConfig('clmcenter/mcenter/metatitle'));
                $head->setKeywords(Mage::getStoreConfig('clmcenter/mcenter/metakeywords'));
                $head->setDescription(Mage::getStoreConfig('clmcenter/mcenter/metadescription'));

                // set category meta data if defined
                $currentCategory = $this->getCurrentCategory();
                if ($currentCategory!=null) {
                    if ($currentCategory->getTitle()!='') {
                        $head->setTitle($currentCategory->getTitle());
                    }
                    if ($currentCategory->getMetaKeywords()!='') {
                        $head->setKeywords($currentCategory->getMetaKeywords());
                    }
                    if ($currentCategory->getMetaDescription()!='') {
                        $head->setDescription($currentCategory->getMetaDescription());
                    }
                }
            }
        }
    }

    public function getShortImageSize($item)
    {
        $width_max = Mage::getStoreConfig('clmcenter/mcenter/shortdescr_image_max_width');
        $height_max = Mage::getStoreConfig('clmcenter/mcenter/shortdescr_image_max_height');
        if (Mage::getStoreConfig('clmcenter/mcenter/resize_to_max') == 1) {
            $width = $width_max;
            $height = $height_max;
        } else {
            $imageObj = new Varien_Image(Mage::getBaseDir('media') . DS . $item->getImageShortContent());
            $original_width = $imageObj->getOriginalWidth();
            $original_height = $imageObj->getOriginalHeight();
            if ($original_width > $width_max) {
                $width = $width_max;
            } else {
                $width = $original_width;
            }
            if ($original_height > $height_max) {
                $height = $height_max;
            } else {
                $height = $original_height;
            }
        }
        if ($item->getShortWidthResize()): $width = $item->getShortWidthResize(); else: $width; endif;
        if ($item->getShortHeightResize()): $height = $item->getShortHeightResize(); else: $height; endif;

        return array('width' => $width, 'height' => $height);
    }
}
