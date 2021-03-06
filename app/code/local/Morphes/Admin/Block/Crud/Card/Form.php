<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 */
class Morphes_Admin_Block_Crud_Card_Form extends Mage_Adminhtml_Block_Widget_Form {
	public function getEntityName() {
		return Mage::registry('m_crud_model')->getEntityName();
	}
	protected function _prepareForm() {
		Mage::dispatchEvent('m_crud_form', array('form' => $this));
		return parent::_prepareForm();
	}
	protected function _initFormValues() {
		$this->getForm()->setValues($this->getForm()->getModel()->getData());
		return parent::_initFormValues();
	}
}