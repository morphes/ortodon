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

class CommerceLab_Mcenter_Block_Adminhtml_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'clmcenter';
        $this->_controller = 'adminhtml_comment';

        $this->_updateButton('save', 'label', Mage::helper('clmcenter')->__('Сохранить комментарий'));
        $this->_updateButton('delete', 'label', Mage::helper('clmcenter')->__('Удалить комментарий'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('clmcenter_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'clmcenter_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'clmcenter_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if ( Mage::registry('clmcenter_data') && Mage::registry('clmcenter_data')->getId() ) {
            return Mage::helper('clmcenter')->__("Редактировать комментарий ('%s')",
                $this->htmlEscape(Mage::registry('clmcenter_data')->getUser()));
        } else {
            return Mage::helper('clmcenter')->__('Добавить комментарий');
        }
    }
}
