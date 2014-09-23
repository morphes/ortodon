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
 * @package    CommerceLab_News
 * @copyright  Copyright (c) 2012 CommerceLab Co. (http://commerce-lab.com)
 * @license    http://commerce-lab.com/LICENSE.txt
 */

class CommerceLab_Mcenter_Block_Adminhtml_Category_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $this->getPosition();
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('category_form',
            array('legend'=>Mage::helper('clmcenter')->__('Категория')));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Наименование'),
            'title'     => Mage::helper('clmcenter')->__('Наименование'),
            'name'      => 'title',
            'required'  => true
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Адрес'),
            'title'     => Mage::helper('clmcenter')->__('Адрес'),
            'name'      => 'url_key',
            'required'  => true
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Сортировать по'),
            'name'      => 'sort_order',
        ));

        $fieldset->addField('sort_id', 'hidden', array(
                'name' => 'sort_id',
                'values' => $this->getPosition(),
        ));
        $fieldset->addField('image_short_content', 'image', array(
            'label'     => Mage::helper('clmcenter')->__('Изображение'),
            'required'  => false,
            'name'      => 'image_short_content',
            'after_element_html' => '<script type="text/javascript">jQuery("#image_short_content").parent().parent().css("display","none");</script>',
        ));
        if ($this->getRequest()->getParam('parent_id') == null) {
            if (Mage::getSingleton('adminhtml/session')->getMcenterData()) {
                    $data = array('sort_id' => $this->getPosition());
                    Mage::getSingleton('adminhtml/session')->setMcenterData($data);
            } else if ($data = Mage::registry('clmcenter_data')) {
                if ($data->getSortId() == null) {
                    $params = array('sort_id' => $this->getPosition());
                    $data->setData($params);
                    Mage::unregister('clmcenter_data');
                    Mage::register('clmcenter_data', $data);
                }
            }
        }


        /**
         * Check is single store mode
         */
        $parentStore = array();
        if ($pid = $this->getRequest()->getParam('parent_id')) {
            $fieldset->addField('parent_id', 'hidden', array(
                'name' => 'parent_id',
                'values' => $pid,
            ));
            $category = Mage::getModel('clmcenter/category')->load($pid);
            if ($lev = $category->getLevel()) {
                $level = $lev + 1;
            } else {
                $level = '1';
            }
            $fieldset->addField('level', 'hidden', array(
                'name' => 'level',
                'values' => $level,
            ));
            if (Mage::getSingleton('adminhtml/session')->getMcenterData()) {
                $data = array('parent_id' => $pid, 'level' => $level, 'sort_id' => $this->getPosition());
                Mage::getSingleton('adminhtml/session')->setMcenterData($data);
            } else if ($data = Mage::registry('clmcenter_data')) {
                $params = array('parent_id' => $pid, 'level' => $level, 'sort_id' => $this->getPosition());
                $data->setData($params);
                Mage::unregister('clmcenter_data');
                Mage::register('clmcenter_data', $data);
            }
            $store = $category->getStoreId();

            $stores = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
            foreach ($stores as $val) {
                if (is_array($val['value'])) {
                    foreach ($val['value'] as $st) {
                        if ($st['value'] == $store[0]) {
                            $parentStore[] = $st;
                        }
                    }
                } else {
                    if ($val['value'] == $store[0]) {
                        $parentStore[] = $val;
                    }
                }
            }

        } else {
            $fieldset->addField('level', 'hidden', array(
                'name' => 'level',
                'values' => 0,
            ));

            $fieldset->addField('parent_id', 'hidden', array(
                'name' => 'parent_id',
                'values' => 0,
            ));
        }

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }

        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('clmcenter')->__('Ключевые слова'),
            'title' => Mage::helper('clmcenter')->__('Мета (ключевые слова)'),
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('clmcenter')->__('Описание'),
            'title' => Mage::helper('clmcenter')->__('Мета (Описание)'),
        ));

        if ( Mage::getSingleton('adminhtml/session')->getMcenterData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMcenterData());
            Mage::getSingleton('adminhtml/session')->setMcenterData(null);
        } elseif ( Mage::registry('clmcenter_data') ) {
            $form->setValues(Mage::registry('clmcenter_data')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getPosition()
    {
        if ($id = $this->getRequest()->getParam('parent_id')) {
            $collection = Mage::getModel('clmcenter/category')->getCollection();
            $collection->getSelect()->order('main_table.sort_id DESC');
            $collection->getSelect()->where('main_table.parent_id =?', $id);
            $collection->getSelect()->limit(1);
            $sortId = $collection->getData('sort_id');
            if (count($sortId) < 1) {
                unset($sortId);
                $collectionNew = Mage::getModel('clmcenter/category')->getCollection();
                $collectionNew->getSelect()->where('main_table.category_id =?', $id);

            $sortId = $collectionNew->getData('sort_id');
            }
            $position = $sortId[0]['sort_id'] + 1;
            if (count($this->checkPosition($position)) > 0) {
                $this->updatePosition($position);
            }
            return $position;
        } else if ($id = $this->getRequest()->getParam('id')) {
            $collection = Mage::getModel('clmcenter/category')->getCollection();
            $collection->getSelect()->where('main_table.category_id =?', $id);
            $sortId = $collection->getData('sort_id');
            $position = $sortId[0]['sort_id'];
            return $position;
        } else {
            $collection = Mage::getModel('clmcenter/category')->getCollection();
            $collection->getSelect()->order('main_table.sort_id DESC');
            $collection->getSelect()->limit(1);
            if (count($collection) < 1) {
                $position = 0;
            } else {
                $sortId = $collection->getData('sort_id');
                $position = $sortId[0]['sort_id'] + 1;
                if (count($this->checkPosition($position)) > 0) {
                    $this->updatePosition($position);
                }
            }
            return $position;
        }
    }

    protected function checkPosition($pos)
    {
        $collection = Mage::getModel('clmcenter/category')->getCollection();
        $collection->getSelect()->where('sort_id =?', $pos);
        return $collection->getData();
    }

    protected function updatePosition($pos)
    {
        $collection = Mage::getModel('clmcenter/category')->getCollection();

        foreach ($collection as $category) {
            if ($category->getSortId() >= $pos) {
                $category->setSortId($category->getSortId() + 10);
                try {
                    $category->save();
                } catch (Exception $ex) {
                    echo 'you have a problem with saving category!!!!' . "\n";
                }
            }
        }
    }
}
