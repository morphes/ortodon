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

class CommerceLab_Mcenter_Block_Adminhtml_Mcenter_Edit_Tab_Additional extends Mage_Adminhtml_Block_Widget_Form
{
    public function initForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('mcenter_time_data',
            array('legend'=>Mage::helper('clmcenter')->__('Настройки времени'), 'style' => 'width: 520px;'));

        $fieldset->addField('mcenter_time', 'date', array(
            'name' => 'mcenter_time',
            'label' => Mage::helper('clmcenter')->__('Время'),
            'title' => Mage::helper('clmcenter')->__('Время'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'after_element_html' =>
                '<span class="hint" style="white-space:nowrap;"><p class="note">'.
                    Mage::helper('clmcenter')->__('Next to the Article will be stated current time').'</p></span>'
        ));

        $fieldset->addField('publicate_from_time', 'date', array(
            'name' => 'publicate_from_time',
            'label' => Mage::helper('clmcenter')->__('Опубликовать с:'),
            'title' => Mage::helper('clmcenter')->__('Опубликовать до:'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));

        $values = $this->getTimeValues(0, 23);
        $fieldset->addField('publicate_from_hours', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Часы'),
            'name'      => 'publicate_from_hours',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $values = $this->getTimeValues(0, 59);
        $fieldset->addField('publicate_from_minutes', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Минуты'),
            'name'      => 'publicate_from_minutes',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $fieldset->addField('publicate_to_time', 'date', array(
            'name' => 'publicate_to_time',
            'label' => Mage::helper('clmcenter')->__('Опубликовать пока:'),
            'title' => Mage::helper('clmcenter')->__('Опубликовать пока:'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));

        $values = $this->getTimeValues(0, 23);
        $fieldset->addField('publicate_to_hours', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Часы'),
            'name'      => 'publicate_to_hours',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $values = $this->getTimeValues(0, 59);
        $fieldset->addField('publicate_to_minutes', 'select', array(
            'label'     => Mage::helper('clmcenter')->__('Минуты'),
            'name'      => 'publicate_to_minutes',
            'style'     => 'width: 50px!important',
            'values'    => $values
        ));

        $fieldset = $form->addFieldset('mcenter_meta_data', array('legend'=>Mage::helper('clmcenter')->__('Meta Data')));

        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('clmcenter')->__('Ключевые слова'),
            'title' => Mage::helper('clmcenter')->__('Мета (ключевые слова)'),
            'style' => 'width: 520px;',
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('clmcenter')->__('Описание'),
            'title' => Mage::helper('clmcenter')->__('Мета (описание)'),
            'style' => 'width: 520px;',
        ));

        $fieldset = $form->addFieldset('mcenter_options_data',
            array('legend'=>Mage::helper('clmcenter')->__('Расширенные опции')));

        $fieldset->addField('author', 'text', array(
            'label'     => Mage::helper('clmcenter')->__('Имя автора'),
            'name'      => 'author',
            'style' => 'width: 520px;',
            'after_element_html' => '<span class="hint"><p class="note">'.$this->__('Оставьте пустым чтобы не выводить').'</p></span>',
        ));

        if ( Mage::getSingleton('adminhtml/session')->getMcenterData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMcenterData());
            Mage::getSingleton('adminhtml/session')->setMcenterData(null);
        } elseif ( Mage::registry('clmcenter_data') ) {
            $form->setValues(Mage::registry('clmcenter_data')->getData());
        }
        $this->setForm($form);
        return $this;
    }

    public function getTimeValues($start, $end)
    {
        $values = array();
        for($i=$start; $i<=$end; $i++) {
            $values[] = array('label' => (strlen($i)>1)?$i:('0'.$i), 'value' => $i);
        }
        return $values;
    }
}
