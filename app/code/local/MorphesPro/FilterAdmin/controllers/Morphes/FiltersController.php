<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterAdmin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */

/**
 * This controller contains actions for morphesging layered navigation filters
 * @author Morphes Team
 *
 */
class MorphesPro_FilterAdmin_Morphes_FiltersController extends Morphes_Admin_Controller_Crud {
	protected function _getEntityName() {
		return 'morphes_filters/filter2';
	}
	/**
	 * Full page rendering action displaying list of entities of certain type. 
	 */
	public function indexAction() {
		// page
		$this->_title('Morphes')->_title($this->__('Layered Navigation Filters'));
        
		// layout
		$update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();

        if (!Mage::app()->isSingleStoreMode()) {
        	$update->addHandle('morphes_admin_multistore_list');
        }
        $this->loadLayoutUpdates();
        $this->generateLayoutXml()->generateLayoutBlocks();
		$this->_isLayoutLoaded = true;
        $this->_initLayoutMessages('adminhtml/session');

        // rendering
        $this->_setActiveMenu('morphes/filters');
        $this->renderLayout();
	}
	public function gridAction() {
		// layout
        $this->addActionLayoutHandles();
        $this->loadLayoutUpdates();
        $this->generateLayoutXml()->generateLayoutBlocks();
		$this->_isLayoutLoaded = true;

		// render AJAX result
		$this->renderLayout(); 
	}
	public function editAction() {
		$model = $this->_registerModel();

		// page
		$this->_title('Morphes')->_title($this->__('%s - Layered Navigation Filter', $model->getName()));
        
		// layout
		$update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();

        if (!Mage::app()->isSingleStoreMode()) {
        	$update->addHandle('morphes_admin_multistore_card');
        }
        $this->loadLayoutUpdates();
        $this->generateLayoutXml()->generateLayoutBlocks();
		$this->_isLayoutLoaded = true;
        $this->_initLayoutMessages('adminhtml/session');

        // simplify if one tab
		if (($tabs = $this->getLayout()->getBlock('tabs')) && count($tabs->getChild()) == 1) {
			$content = $tabs->getActiveTabBlock();
			$tabs->getParentBlock()->unsetChild('tabs');
			$this->getLayout()->getBlock('container')->insert($content, $content->getNameInLayout(), null, $content->getNameInLayout());
			$content->addToParentGroup('content');
		} 
		
        // rendering
		Mage::helper('morphes_core/js')->options('edit-form', array('editSessionId' => Mage::helper('morphes_db')->beginEditing()));
        $this->_setActiveMenu('morphes/filters');
        $this->renderLayout();
		
	}
	public function saveAction() {
		// data
		$fields = $this->getRequest()->getPost('fields');
        $useDefault = $this->getRequest()->getPost('use_default');
        $data = array();
		if (Mage::helper('morphes_admin')->isGlobal()) {
			$model = Mage::getModel('morphes_filters/filter2')->load($this->getRequest()->getParam('id'));
		}
		else {
			$model = Mage::getModel('morphes_filters/filter2_store')->loadByGlobalId($this->getRequest()->getParam('id'), 
				Mage::helper('morphes_admin')->getStore()->getId());
		}

        $response = new Varien_Object();
        $update = array();
        /* @var $messages Mage_Adminhtml_Block_Messages */ $messages = $this->getLayout()->createBlock('adminhtml/messages');

        try {
			// processing
			$model->addEditedData($fields, $useDefault);
            $model->addEditedDetails($this->getRequest());
			$model->validateKeys();
			Mage::helper('morphes_db')->replicateObject($model, array(
				$model->getEntityName() => array('saved' => array($model->getId()))
			));
			$model->validate();

			// do save
        	$model->save();
        	Mage::dispatchEvent('m_saved', array('object' => $model));
        	$messages->addSuccess($this->__('Your changes are successfully saved.'));
        }
        catch (Morphes_Db_Exception_Validation $e) {
        	foreach ($e->getErrors() as $error) {
        		$messages->addError($error);
        	}
        	$response->setError(true);
        }
        catch (Exception $e) {
        	$messages->addError($e->getMessage());
        	$response->setError(true);
        }
        
        $update[] = array('selector' => '#messages', 'html' => $messages->getGroupedHtml());
        $response->setUpdate($update);
        $this->getResponse()->setBody($response->toJson());
	}

	public function tabGeneralAction() {
		$model = $this->_registerModel();
		
		// layout
        $this->addActionLayoutHandles();
        $this->loadLayoutUpdates();
        $this->generateLayoutXml()->generateLayoutBlocks();
		$this->_isLayoutLoaded = true;

		// render AJAX result
		$this->renderLayout(); 
	}
}