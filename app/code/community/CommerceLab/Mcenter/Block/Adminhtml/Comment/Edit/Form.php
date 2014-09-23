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

class CommerceLab_Mcenter_Block_Adminhtml_Comment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $collection = Mage::getResourceModel('clmcenter/comment_collection');

        $mcenter_id = $this->getRequest()->getParam('id');
        $tableName = Mage::getSingleton('core/resource')->getTableName('clmcenter_mcenter');
        $collection->getSelect()->joinLeft($tableName, 'main_table.mcenter_id = '. $tableName . '.mcenter_id', 'title');
        $collection->getSelect()->distinct();
        $collection->getSelect()->where('main_table.mcenter_id =' . $mcenter_id);
        $collection->getSelect()->limit(1);
        $data = $collection->getData();

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $fieldset = $form->addFieldset('comment_form',
            array('legend'=>Mage::helper('clmcenter')->__('Комментарий')));


         $fieldset->addField('title', 'hidden', array(
            'label'     => Mage::helper('clmcenter')->__('Наименование'),
            'after_element_html' => '<tr><td class="label"><label for="title">Наименование</label></td>
                <td class="value">' .$data[0]['title'] . '</td></tr>',
        ));

        $fieldset->addField('user', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Пользователь'),
            'name'      => 'user',
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('E-mail'),
            'name'      => 'email',
        ));

        $fieldset->addField('comment_status', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Статус'),
            'name'      => 'comment_status',
            'values'    => array(
                array(
                    'value'     => CommerceLab_Mcenter_Helper_Data::UNAPPROVED_STATUS,
                    'label'     => Mage::helper('clmcenter')->__('Отклонен'),
                ),

                array(
                    'value'     => CommerceLab_Mcenter_Helper_Data::APPROVED_STATUS,
                    'label'     => Mage::helper('clmcenter')->__('Одобрен'),
                ),
            ),
        ));

        $fieldset->addField('comment', 'editor', array(
            'name'      => 'comment',
            'label'     => Mage::helper('clmcenter')->__('Комментарий'),
            'title'     => Mage::helper('clmcenter')->__('Комментарий'),
            'style'     => 'width:500px; height:250px;',
            'wysiwyg'   => false,
            'required'  => false,
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
}
