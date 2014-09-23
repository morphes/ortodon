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

class CommerceLab_Mcenter_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
    }

    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        // load layout, set active menu
        $this->loadLayout()
            ->_setActiveMenu('clmcenter/category');
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction() {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('clmcenter/adminhtml_category'))
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('clmcenter/category');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('clmcenter')->__('Нет доступа'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        Mage::register('clmcenter_data', $model);

        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('clmcenter/adminhtml_category_edit'));

        $this->renderLayout();
    }


    public function saveAction() {

        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('clmcenter/category');


            try {

                if (isset($_FILES['image_short_content']['name']) && ($_FILES['image_short_content']['name'] != '')
                    && ($_FILES['image_short_content']['size'] != 0) ) {

                    try {
                        $uploader = new Varien_File_Uploader('image_short_content');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);

                        // Set the file upload mode
                        // false -> get the file directly in the specified folder
                        // true -> get the file in folders like /media/a/b/
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS . 'clmcenter' . DS;

                        //saved the name in DB
                        $prefix = time().rand();
                        $fileName = $prefix.'.'.pathinfo($_FILES['image_short_content']['name'], PATHINFO_EXTENSION);
                        $uploader->save($path, $fileName);
                        $filepath = 'clmcenter' . DS .$fileName;
                        /*
                        if (!getimagesize($filepath)) {
                            Mage::throwException($this->__('Disallowed file type.'));
                        }*/
                        $data['image_short_content'] = $filepath;
                        $data['image_short_content'] = str_replace('\\', '/', $data['image_short_content']);
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    }
                } elseif (isset($data['image_short_content']['delete'])) {
                    $path = Mage::getBaseDir('media') . DS;
                    $result = unlink($path . $data['image_short_content']['value']);
                    if ($data['short_height_resize'] && $data['short_width_resize']) {
                        $resizePath = Mage::getBaseDir('media') . DS . 'clmcenter' . DS . $data['short_width_resize'] . 'x' . $data['short_height_resize'] . DS;
                    }
                    $result = unlink($resizePath . str_replace('clmcenter/', '', $data['image_short_content']['value']));
                    $data['image_short_content'] = '';
                } else {
                    if (isset($data['image_short_content']['value'])) {
                        $data['image_short_content'] = $data['image_short_content']['value'];
                    }
                }

                $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));


//                $model->setImageShortContent($data['image_short_content']);



                $model->save();


                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('clmcenter')->__('Категория сохранена успешно'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('clmcenter/category');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('clmcenter')->__('Категория успешно удалена'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('category');
        if (!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')->__('Please select categories'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $model = Mage::getModel('clmcenter/category')->load($categoryId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                        ->__('%d categories have been successfully deleted',
                        count($categoryIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
