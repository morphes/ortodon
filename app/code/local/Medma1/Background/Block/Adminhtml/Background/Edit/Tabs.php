<?php

class Medma_Background_Block_Adminhtml_Background_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {	
	  parent::__construct();
		
	  $this->setId('background_tabs');
	  $this->setDestElementId('edit_form');
	  $this->setTitle(Mage::helper('background')->__('Manage Backgrounds'));
	$this->_removeButton('delete');
  }

  protected function _beforeToHtml()
  {
	  $this->addTab('form_section', array(
		  'label'     => Mage::helper('background')->__('Item Information'),
		  'title'     => Mage::helper('background')->__('Item Information'),
		  'content'   => $this->getLayout()->createBlock('background/adminhtml_background_edit_tab_form')->toHtml(),
	  ));
	  return parent::_beforeToHtml();
  }
}
