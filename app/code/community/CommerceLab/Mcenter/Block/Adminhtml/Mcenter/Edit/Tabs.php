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

class CommerceLab_Mcenter_Block_Adminhtml_Mcenter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('mcenter_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('clmcenter')->__('Основное'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('info', array(
            'label'     => Mage::helper('clmcenter')->__('Основное'),
            'content'   => $this->getLayout()->createBlock('clmcenter/adminhtml_mcenter_edit_tab_info')->initForm()->toHtml(),
        ));

        $this->addTab('additional', array(
            'label'     => Mage::helper('clmcenter')->__('Дополнительные опции'),
            'content'   => $this->getLayout()
                ->createBlock('clmcenter/adminhtml_mcenter_edit_tab_additional')->initForm()->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
