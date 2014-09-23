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

class CommerceLab_Mcenter_Block_Adminhtml_Mcenter_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('mcenterGrid');
        $this->setDefaultSort('mcenter_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('clmcenter/mcenter')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('mcenter_id', array(
            'header'    => Mage::helper('clmcenter')->__('ID'),
            'align'     =>'right',
            'width'     => '50',
            'index'     => 'mcenter_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('clmcenter')->__('Наименование'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('clmcenter')->__('Адрес'),
            'align'     => 'left',
            'index'     => 'url_key',
        ));

        $this->addColumn('author', array(
            'header'    => Mage::helper('clmcenter')->__('Автор'),
            'index'     => 'author',
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

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('clmcenter')->__('Открыто'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'created_time',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('clmcenter')->__('Изменено'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'update_time',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('clmcenter')->__('Статус'),
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('clmcenter')->__('Включено'),
                2 => Mage::helper('clmcenter')->__('Отключено')
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('clmcenter')->__('Действие'),
                'width'     => '60',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('clmcenter')->__('Редактировать'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addColumn('view_comments',
            array(
                'header'    =>  Mage::helper('clmcenter')->__('Комментарии'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('clmcenter')->__('Смотреть комментарии'),
                        'url'       => array('base'=> 'clmcenter/adminhtml_comment/index'),
                        'field'     => 'mcenter_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('mcenter_id');
        $this->getMassactionBlock()->setFormFieldName('clmcenter');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('clmcenter')->__('Удалить'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('clmcenter')->__('Вы уверены?')
        ));

        $statuses = array(
              1 => Mage::helper('clmcenter')->__('Включено'),
              0 => Mage::helper('clmcenter')->__('Отключено')
        );
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('clmcenter')->__('Сменить статус'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('clmcenter')->__('Статус'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
