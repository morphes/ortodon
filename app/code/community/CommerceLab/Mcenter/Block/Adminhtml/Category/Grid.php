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

class CommerceLab_Mcenter_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('categoryGrid');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('clmcenter/category')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('clmcenter')->__('ID'),
            'align'     =>'right',
            'width'     => '50',
            'index'     => 'category_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('clmcenter')->__('Наименование'),
            'align'     =>'left',
            'index'     => 'title',
            'renderer' => 'clmcenter/adminhtml_category_grid_column_renderer_subCategories',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('clmcenter')->__('Адрес'),
            'align'     =>'left',
            'index'     => 'url_key',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Магазин'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('clmcenter')->__('Сортировать по'),
            'align'     => 'left',
            'width'     => '50',
            'index'     => 'sort_order',
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('clmcenter')->__('Действие'),
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('clmcenter')->__('Редактирование'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => Mage::helper('clmcenter')->__('Добавить подкатегорию'),
                        'url'       => array('base'=> '*/*/new'),
                        'field'     => 'parent_id'
                    )

                ),
                'width'     => '70',
                'index'     => 'stores',
                'is_system' => true,
                'filter'    => false,
                'sortable'  => false,
                'renderer' => 'clmcenter/adminhtml_category_grid_column_renderer_action',
                ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('category_id');
        $this->getMassactionBlock()->setFormFieldName('category');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('clmcenter')->__('Удалить'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('clmcenter')->__('Вы уверены?')
        ));

        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value, true);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
