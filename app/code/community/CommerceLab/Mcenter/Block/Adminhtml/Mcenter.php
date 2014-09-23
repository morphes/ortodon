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

class CommerceLab_Mcenter_Block_Adminhtml_Mcenter extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_mcenter';
        $this->_blockGroup = 'clmcenter';
        $this->_headerText = Mage::helper('clmcenter')->__('Менеджер');
        $this->_addButtonLabel = Mage::helper('clmcenter')->__('Добавить');
        parent::__construct();
    }
}
