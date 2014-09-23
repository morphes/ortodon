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

class CommerceLab_Mcenter_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('commentGrid');
        $this->setDefaultSort('comment_status');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('clmcenter/comment_collection');
        if ($this->getRequest()->getParam('mcenter_id')) {
            $collection->addMcenterFilter($this->getRequest()->getParam('mcenter_id'));
        } else {
            $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter');
            $collection->getSelect()->joinLeft($tableName, 'main_table.mcenter_id = ' . $tableName . '.mcenter_id', array($tableName . '.title as title'));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('comment', array(
            'header'    => Mage::helper('clmcenter')->__('Комментарий'),
            'align'     =>'left',
            'index'     => 'comment',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('clmcenter')->__('Наименование'),
            'index'     => 'title',
        ));

        $this->addColumn('user', array(
            'header'    => Mage::helper('clmcenter')->__('Пользователь'),
            'index'     => 'user',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('clmcenter')->__('E-mail'),
            'index'     => 'email',
        ));

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('clmcenter')->__('Открыто'),
            'align'     => 'center',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_time',
        ));

        $this->addColumn('comment_status', array(
            'header'    => Mage::helper('clmcenter')->__('Статус'),
            'align'     => 'center',
            'width'     => '80px',
            'index'     => 'comment_status',
            'type'      => 'options',
            'options'   => array(
                CommerceLab_Mcenter_Helper_Data::UNAPPROVED_STATUS => 'Отклонен',
                CommerceLab_Mcenter_Helper_Data::APPROVED_STATUS => 'Одобрен',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('clmcenter')->__('Действие'),
                'width'     => '50',
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
        /*
        $this->addColumn('view_mcenter_item',
            array(
                'header'    =>  Mage::helper('clmcenter')->__('Mcenter Article'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('clmcenter')->__('Go to Mcenter Article'),
                        'url'       => array('base'=> '* /adminhtml_mcenter/edit'),
                        'field'     => 'mcenter_id'
                    ),
                 ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));*/
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('comments');

        $this->getMassactionBlock()->addItem('approve', array(
             'label'    => Mage::helper('clmcenter')->__('Одобрен'),
             'url'      => $this->getUrl('*/*/massApprove'),
             'confirm'  => Mage::helper('clmcenter')->__('Вы уверены?')
        ));

        $this->getMassactionBlock()->addItem('unapprove', array(
             'label'    => Mage::helper('clmcenter')->__('Отклонен'),
             'url'      => $this->getUrl('*/*/massUnapprove'),
             'confirm'  => Mage::helper('clmcenter')->__('Вы уверены?')
        ));

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('clmcenter')->__('Удалить'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('clmcenter')->__('Вы уверены?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
