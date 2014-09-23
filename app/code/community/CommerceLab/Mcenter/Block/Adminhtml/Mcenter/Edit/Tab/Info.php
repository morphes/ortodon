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

class CommerceLab_Mcenter_Block_Adminhtml_Mcenter_Edit_Tab_Info extends Mage_Adminhtml_Block_Widget_Form
{
    public function initForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('mcenter_form', array('legend'=>Mage::helper('clmcenter')->__('Основное')));

        $fieldset->addField('status', 'select', array(
        'label'     => Mage::helper('clmcenter')->__('Статус'),
        'name'      => 'status',
        'values'    => array(
          array(
              'value'     => 1,
              'label'     => Mage::helper('clmcenter')->__('Включено'),
          ),

          array(
              'value'     => 2,
              'label'     => Mage::helper('clmcenter')->__('Отключено'),
          ),
        ),
        ));

        $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('clmcenter')->__('Наименование'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
        ));

        $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('clmcenter')->__('Адрес'),
          'title'     => Mage::helper('clmcenter')->__('Адрес'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'url_key',
          'class'     => 'validate-identifier',
          'after_element_html' => '<div class="hint"><p class="note">'.$this->__('e.g. domain.com/mcenter/url_key').'</p></div>',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('store_id', 'multiselect', array(
                    'name'      => 'stores[]',
                    'label'     => Mage::helper('clmcenter')->__('Магазин'),
                    'title'     => Mage::helper('clmcenter')->__('Магазин'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }

        $categories = array();
        $collection = Mage::getModel('clmcenter/category')->getCollection()->setOrder('sort_id', 'asc');
        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');
        foreach ($collection as $cat) {
            $categories[] = ( array(
                'label' => str_repeat($nonEscapableNbspChar, $cat->getLevel() * 4).(string)$cat->getTitle(),
                'value' => $cat->getCategoryId()
                ));
        }
            $fieldset->addField('category_id', 'multiselect', array(
                    'name'      => 'categories[]',
                    'label'     => Mage::helper('clmcenter')->__('Категория'),
                    'title'     => Mage::helper('clmcenter')->__('Категория'),
                    'required'  => false,
                    'style'     => 'height:100px',
                    'values'    => $categories,
            ));

        $fieldset->addField('comments_enabled', 'select', array(
        'label'     => Mage::helper('clmcenter')->__('Комментарии'),
        'name'      => 'comments_enabled',
        'values'    => array(
          array(
              'value'     => 1,
              'label'     => Mage::helper('clmcenter')->__('Включено'),
          ),

          array(
              'value'     => 0,
              'label'     => Mage::helper('clmcenter')->__('Отключено'),
          ),
        ),
        ));
        $mcenterCollection = Mage::getModel('clmcenter/mcenter')->getCollection()
                            ->addFieldToFilter('mcenter_id', $this->getRequest()->getParam('id'));
        $fieldset->addField('document_save', 'file', array(
            'label'     => Mage::helper('clmcenter')->__('Файл'),
            'required'  => false,
            'name'      => 'document_save',
        ));
        $file = $mcenterCollection->getData();
        if ($this->getRequest()->getParam('id')) {
            $documents = $file[0]['document'];
            $full_path = $file[0]['full_path_document'];
            $tag = $file[0]['tags'];
        } else {
            $documents = NULL;
            $full_path = '';
            $tag = '';
        }
        if ($documents) {
            $fieldset->addField('is_delete', 'checkbox', array(
                'name'      => 'is_delete',
                'label'     => Mage::helper('clmcenter')->__('Удалить файл'),
                'after_element_html' => '<a href="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'clmcenter' . DS . $documents . '" >' . $documents. '</a>',
            ));

            $fieldset->addField('full_path_document', 'hidden', array(
                'name'       => 'full_path_document',
                'value'      => $full_path,
            ));
        }

        $fieldset->addField('link', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Ссылка'),
            'title'     => Mage::helper('clmcenter')->__('Ссылка'),
            'name'      => 'link',
            'after_element_html' => '<div class="hint"><p class="note">'.$this->__('e.g. Download attached document - default').'</p></div>',
        ));

        $fieldset->addField('tags', 'text', array(
              'label'     => Mage::helper('clmcenter')->__('Добавить теги'),
              'title'     => Mage::helper('clmcenter')->__('Добавить теги'),
              'name'      => 'tags',
              'value'     => $tag,
              'after_element_html' => '<div class="hint"><p class="note">'.$this->__('Используйте кавычки для множества слова').'</p></div>',
            ));
        $data = Mage::registry('clmcenter_data');
        if ($data && (($data->getImageShortContent() == $data->getImageFullContent()) || $data->getImageShortContent() == '' || !$data->getImageShortContent())) {
            $fieldset->addField('use_full_img', 'checkbox', array(
                'label'     => Mage::helper('clmcenter')->__('Включить изображение в статью'),
                'required'  => false,
                'name'      => 'use_full_img',
                'onclick'   => 'checkboxSwitch();',
                'checked'   => true,
            ));
            $fieldset->addField('image_short_content', 'image', array(
                'label'     => Mage::helper('clmcenter')->__('Изображение для короткого описания'),
                'required'  => false,
                'name'      => 'image_short_content',
                'after_element_html' => '<script type="text/javascript">jQuery("#image_short_content").parent().parent().css("display","none");</script>',
            ));

        } else {
            $fieldset->addField('use_full_img', 'checkbox', array(
                'label'     => Mage::helper('clmcenter')->__('Включить изображение в статью'),
                'required'  => false,
                'name'      => 'use_full_img',
                'onclick'   => 'checkboxSwitch();'
            ));

            $fieldset->addField('image_short_content', 'image', array(
                'label'     => Mage::helper('clmcenter')->__('Изображение для короткого описания'),
                'required'  => false,
                'name'      => 'image_short_content',
            ));
        }

        $fieldset->addField('short_height_resize', 'text', array(
              'label'     => Mage::helper('clmcenter')->__('Высота'),
              'title'     => Mage::helper('clmcenter')->__('Высота'),
              'name'      => 'short_height_resize',
              'style'     => 'width: 50px;',
              'after_element_html' => '<span class="hint">px</span>',
            ));

        $fieldset->addField('short_width_resize', 'text', array(
              'label'     => Mage::helper('clmcenter')->__('Длина'),
              'title'     => Mage::helper('clmcenter')->__('Длина'),
              'name'      => 'short_width_resize',
              'style'     => 'width: 50px;',
              'after_element_html' => '<span class="hint">px</span>',
            ));

        $fieldset->addField('image_short_content_show', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Показать'),
            'required'  => false,
            'name'      => 'image_short_content_show',
            'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('clmcenter')->__('Да'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('clmcenter')->__('Нет'),
              ),
            ),
        ));

        $fieldset->addField('short_content', 'editor', array(
            'name'      => 'short_content',
            'label'     => Mage::helper('clmcenter')->__('Короткое описание'),
            'title'     => Mage::helper('clmcenter')->__('Короткое описание'),
            'config'    => Mage::getSingleton('clmcenter/wysiwyg_config')->getConfig(),
            'wysiwyg' => true
        ));

        $fieldset->addField('image_full_content', 'image', array(
            'label'     => Mage::helper('clmcenter')->__('Изображение для статьи'),
            'required'  => false,
            'name'      => 'image_full_content',
        ));

        $fieldset->addField('full_height_resize', 'text', array(
              'label'     => Mage::helper('clmcenter')->__('Высота'),
              'title'     => Mage::helper('clmcenter')->__('Высота'),
              'name'      => 'full_height_resize',
              'style'     => 'width: 50px;',
              'after_element_html' => '<span class="hint">px</span>',
            ));

        $fieldset->addField('full_width_resize', 'text', array(
              'label'     => Mage::helper('clmcenter')->__('Длина'),
              'title'     => Mage::helper('clmcenter')->__('Длина'),
              'name'      => 'full_width_resize',
              'style'     => 'width: 50px;',
              'after_element_html' => '<span class="hint">px</span>',
            ));

        $fieldset->addField('image_full_content_show', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Показать изображение'),
            'required'  => false,
            'name'      => 'image_full_content_show',
            'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('clmcenter')->__('Да'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('clmcenter')->__('Нет'),
              ),
            ),
        ));


        $fieldset->addField('full_content', 'editor', array(
            'name'      => 'full_content',
            'label'     => Mage::helper('clmcenter')->__('Полное описание'),
            'title'     => Mage::helper('clmcenter')->__('Полное описание'),
            'style'     => 'height:36em',
            'config'    => Mage::getSingleton('clmcenter/wysiwyg_config')->getConfig(),
            'wysiwyg' => true
        ));


        if (Mage::getSingleton('adminhtml/session')->getMcenterData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMcenterData());
            Mage::getSingleton('adminhtml/session')->setMcenterData(null);
        } elseif ( Mage::registry('clmcenter_data') ) {
            $data = Mage::registry('clmcenter_data');
            if (($data->getImageShortContent() == $data->getImageFullContent()) || $data->getImageShortContent() == '' || !$data->getImageShortContent()) {
                $data->setUseFullImg(1);
            }
            $form->setValues($data->getData());
        }

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'hidden', array(
                            'name'      => 'stores[]',
                            'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        $this->setForm($form);
        return $this;
    }
}
