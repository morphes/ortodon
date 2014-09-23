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

class CommerceLab_Mcenter_Adminhtml_McenterController extends Mage_Adminhtml_Controller_Action
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
            ->_setActiveMenu('clmcenter/items');
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('clmcenter/adminhtml_mcenter'))
            ->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('clmcenter/mcenter')->load($id);

        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError(Mage::helper('clmcenter')->__('Статьи не существует'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('clmcenter_data', $model);

        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('clmcenter/adminhtml_mcenter_edit'))
            ->_addLeft($this->getLayout()->createBlock('clmcenter/adminhtml_mcenter_edit_tabs'))
            ->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $_mcenterItem = Mage::getModel('clmcenter/mcenter')->getCollection()
                ->addFieldToFilter('url_key', $data['url_key'])
                ->setPageSize(1)
                ->getFirstItem();
            /*
            $arr = array();
            $arr = $mcenterCollection->getData();
            if (isset($arr[0]) && $this->getRequest()->getParam('id') == $arr[0]['mcenter_id'] && $data['url_key'] == $arr[0]['url_key']) {
                $sameUrl = null;
            } else {
                $sameUrl = $mcenterCollection->getData();
            }
            if ($sameUrl != null) {*/
            if ($_mcenterItem->getId() &&
            ($this->getRequest()->getParam('id') == $_mcenterItem->getId()) &&
            ($data['url_key'] == $_mcenterItem->getUrlKey()) &&
            (count(array_diff($_mcenterItem->getStoreId(), $data['stores'])) == 0) &&
            (count(array_diff($data['stores'], $_mcenterItem->getStoreId())) == 0 )
            ) {
                $sameUrl = false;
            } else {
                $sameUrl = false;
                $_mcenterItemCollection = Mage::getModel('clmcenter/mcenter')->getCollection()
                ->addFieldToFilter('mcenter_id', array('neq' => $this->getRequest()->getParam('id')))
                ->addFieldToFilter('url_key', $data['url_key']);
                foreach($_mcenterItemCollection as $_mcenterItem) {
                    if ((count(array_intersect($_mcenterItem->getStoreId(), $data['stores'])) != 0) ||
                    in_array(0, $data['stores']) ||
                    in_array(0, $_mcenterItem->getStoreId())
                    ) {
                        $sameUrl = true;
                    }
                }
            }
            if ($sameUrl) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('clmcenter')->__('Статьи не найдено'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } else {

                if (isset($data['is_delete'])) {
                    $isDeleteFile = true;
                } else {
                    $isDeleteFile = false;
                }

                if (isset($_FILES['document_save']['name']) && ($_FILES['document_save']['name'] != '')
                    && ($_FILES['document_save']['size'] != 0) ) {
                    try {
                        $uploader = new Varien_File_Uploader('document_save');
                        $uploader->setAllowRenameFiles(false);

                        // Set the file upload mode
                        // false -> get the file directly in the specified folder
                        // true -> get the file in folders like /media/a/b/
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS . 'clmcenter' . DS;

                        //saved the name in DB
                        $prefix = time().rand();
                        $fileName = $prefix.'.'.pathinfo($_FILES['document_save']['name'], PATHINFO_EXTENSION);
                        $uploader->save($path, $fileName);
                        $filepath = 'clmcenter' . DS .$fileName;
                        $data['full_path_document'] = $path . $fileName;
                        $data['document'] = $fileName;
                        $data['document'] = str_replace('\\', '/', $data['document']);
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    }
                } elseif ($isDeleteFile === true) {
                    unlink($data['full_path_document']);
                    $data['document']='';
                } else {
                    /// to insert a code for deleting image
                    /// ....
                    if (isset($data['document'])) {
                        $data['document']=$data['document'];
                    }
                }

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

                if (isset($_FILES['image_full_content']['name']) && ($_FILES['image_full_content']['name'] != '')
                    && ($_FILES['image_full_content']['size'] != 0) ) {
                    try {
                        $uploader = new Varien_File_Uploader('image_full_content');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);

                        // Set the file upload mode
                        // false -> get the file directly in the specified folder
                        // true -> get the file in folders like /media/a/b/
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS . 'clmcenter' . DS;

                        //saved the name in DB
                        $prefix = time().rand();
                        $fileName = $prefix.'.'.pathinfo($_FILES['image_full_content']['name'], PATHINFO_EXTENSION);
                        $uploader->save($path, $fileName);
                        $filepath = 'clmcenter' . DS .$fileName;
                        $data['image_full_content'] = $filepath;
                        $data['image_full_content'] = str_replace('\\', '/', $data['image_full_content']);
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    }
                } elseif (isset($data['image_full_content']['delete'])) {
                    $path = Mage::getBaseDir('media') . DS;
                    $result = unlink($path . $data['image_full_content']['value']);
                    if ($data['full_height_resize'] && $data['full_width_resize']) {
                        $resizePath = Mage::getBaseDir('media') . DS . 'clmcenter' . DS . $data['full_width_resize'] . 'x' . $data['full_height_resize'] . DS;
                    }
                    $result = unlink($resizePath . str_replace('clmcenter/', '', $data['image_full_content']['value']));
                    $data['image_full_content'] = '';
                } else {
                    if (isset($data['image_full_content']['value'])) {
                        $data['image_full_content'] = $data['image_full_content']['value'];
                    }
                }

                if (isset($data['use_full_img'])) {
                    if (isset($data['image_full_content'])) {
                        $data['image_short_content'] = $data['image_full_content'];
                    }
                }

                $model = Mage::getModel('clmcenter/mcenter');
                $hoursFrom = $this->getRequest()->getParam('publicate_from_hours');
                $minutesFrom = $this->getRequest()->getParam('publicate_from_minutes');
                $hoursTo = $this->getRequest()->getParam('publicate_to_hours');
                $minutesTo = $this->getRequest()->getParam('publicate_to_minutes');
                $data['publicate_from_hours'] = $hoursFrom;
                $data['publicate_from_minutes'] = $minutesFrom;
                $data['publicate_to_hours'] = $hoursTo;
                $data['publicate_to_minutes'] = $minutesTo;
                $data['link'] = $this->getRequest()->getParam('link');
                $data['tags'] = $this->getRequest()->getParam('tags');

                // prepare dates
                if ($this->getRequest()->getParam('mcenter_time')!='') {
                    $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                    if (!Zend_Date::isDate($this->getRequest()->getParam('mcenter_time') . ' ' . date("H:i:s"), $dateFormatIso)) {
                        throw new Exception($this->__(('Mcenter date field is required')));
                    }
                    $date = new Zend_Date($this->getRequest()->getParam('mcenter_time') . ' ' . date("H:i:s"), $dateFormatIso);
                    $dateInfo = $date->toArray();
                    $data['mcenter_time'] = preg_replace('/([0-9]{4})\-(.*)/', $dateInfo['year'].'-$2', $date->toString('YYYY-MM-dd HH:mm:ss'));
                } else {
                    $data['mcenter_time'] = new Zend_Db_Expr('null');
                }

                if ($this->getRequest()->getParam('publicate_from_time')!='') {
                    if (!Zend_Date::isDate($this->getRequest()->getParam('publicate_from_time'). ' ' . $hoursFrom . ':' . $minutesFrom . ':00', $dateFormatIso)) {
                        throw new Exception($this->__(('Mcenter date field is required')));
                    }
                    $date = new Zend_Date($this->getRequest()->getParam('publicate_from_time'). ' ' . $hoursFrom . ':' . $minutesFrom . ':00', $dateFormatIso);
                    $dateInfo = $date->toArray();
                    $data['publicate_from_time'] = preg_replace('/([0-9]{4})\-(.*)/', $dateInfo['year'].'-$2', $date->toString('YYYY-MM-dd HH:mm:ss'));
                } else {
                    $data['publicate_from_time'] = new Zend_Db_Expr('null');
                }

                if ($this->getRequest()->getParam('publicate_to_time')!='') {
                    if (!Zend_Date::isDate($this->getRequest()->getParam('publicate_to_time'). ' ' . $hoursTo . ':' . $minutesTo . ':00', $dateFormatIso)) {
                        throw new Exception($this->__(('Mcenter date field is required')));
                    }
                    $date = new Zend_Date($this->getRequest()->getParam('publicate_to_time'). ' ' . $hoursTo . ':' . $minutesTo . ':00', $dateFormatIso);
                    $dateInfo = $date->toArray();
                    $data['publicate_to_time'] = preg_replace('/([0-9]{4})\-(.*)/', $dateInfo['year'].'-$2', $date->toString('YYYY-MM-dd HH:mm:ss'));
                } else {
                    $data['publicate_to_time'] = new Zend_Db_Expr('null');
                }
                $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

                try {
                    if ($this->getRequest()->getParam('mcenter_time') == NULL) {
                        $model->setMcenterTime(now());
                        $model->setCreatedTime(now());
                    } else {
                        if (isset($arr[0]) && (!$mcenterItemId = $arr[0]['mcenter_id'])) {
                            $model->setCreatedTime(now());
                        }
                    }

                    $model->setUpdateTime(now());

                    if ($this->getRequest()->getParam('author') == NULL) {
                        $model->setUpdateAuthor(NULL);
                        /*$model->setAuthor(Mage::getSingleton('admin/session')->getUser()->getFirstname() .
                            " " . Mage::getSingleton('admin/session')->getUser()->getLastname())
                            ->setUpdateAuthor(Mage::getSingleton('admin/session')->getUser()->getFirstname() .
                            " " . Mage::getSingleton('admin/session')->getUser()->getLastname());*/
                    } else {
                        $model->setUpdateAuthor(Mage::getSingleton('admin/session')->getUser()->getFirstname() .
                            " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                    }
                    $model->save();

                    Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('clmcenter')->__('Сохранено успешно!'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

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

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('clmcenter')->__('Нет данных для сохранения'));
                $this->_redirect('*/*/');
            }
        }
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('clmcenter/mcenter');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')->__('Успешно удалено'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $mcenterIds = $this->getRequest()->getParam('clmcenter');
        if (!is_array($mcenterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $model = Mage::getModel('clmcenter/mcenter');
                foreach ($mcenterIds as $mcenterId) {
                    $model->reset()
                        ->load($mcenterId)
                        ->delete();
                }
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                    ->__('%d record(s) have been successfully deleted', count($mcenterIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'mcenter.csv';
        $grid       = $this->getLayout()
            ->createBlock('clmcenter/adminhtml_mcenter_grid')
            ->addColumn('mcenter_id', array(
            'header'    => Mage::helper('clmcenter')->__('ID'),
            'align'     =>'right',
            'width'     => '50',
            'index'     => 'mcenter_id',
        ))->addColumn('title', array(
            'header'    => Mage::helper('clmcenter')->__('Наименование'),
            'align'     =>'left',
            'index'     => 'title',
        ))->addColumn('url_key', array(
            'header'    => Mage::helper('clmcenter')->__('Адрес'),
            'align'     => 'left',
            'index'     => 'url_key',
        ))->addColumn('author', array(
            'header'    => Mage::helper('clmcenter')->__('Автор'),
            'index'     => 'author',
        ))->addColumn('short_content', array(
            'header'    => Mage::helper('clmcenter')->__('Короткое описание'),
            'type'     =>'text',
            'index'     => 'short_content',
        ))->addColumn('image_short_content', array(
            'header'    => Mage::helper('clmcenter')->__('Изображение для короткого описания'),
            'type'     =>'text',
            'display' => 'none',
            'index'     => 'image_short_content',
        ))->addColumn('full_content', array(
            'header'    => Mage::helper('clmcenter')->__('Текст статьи'),
            'type'     =>'text',
            'index'     => 'full_content',
        ))->addColumn('image_full_content', array(
            'header'    => Mage::helper('clmcenter')->__('Изображение для текста статьи'),
            'type'     =>'text',
            'index'     => 'image_full_content',
        ))->addColumn('document', array(
            'header'    => Mage::helper('clmcenter')->__('Документ'),
            'type'     =>'text',
            'index'     => 'document',
        ))->addColumn('meta_keywords', array(
            'header'    => Mage::helper('clmcenter')->__('Ключевые слова'),
            'type'     =>'text',
            'index'     => 'meta_keywords',
        ))->addColumn('meta_description', array(
            'header'    => Mage::helper('clmcenter')->__('Описание (мета)'),
            'type'     =>'text',
            'index'     => 'meta_description',
        ))->addColumn('tags', array(
            'header'    => Mage::helper('clmcenter')->__('Теги'),
            'type'     =>'text',
            'index'     => 'tags',
        ));

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function massStatusAction()
    {
        $mcenterIds = $this->getRequest()->getParam('clmcenter');
        if (!is_array($mcenterIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Выделите, пожалуйста'));
        } else {
            try {
                foreach ($mcenterIds as $mcenterId) {
                    $model = Mage::getSingleton('clmcenter/mcenter')
                        ->setId($mcenterId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->save();
                }
                $this->_getSession()
                    ->addSuccess($this->__('%d запись(ей) успешно удалено', count($mcenterIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
