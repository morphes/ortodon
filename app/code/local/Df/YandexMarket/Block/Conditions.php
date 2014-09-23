<?php


/**
 * Обратите внимание, что Magento не создаёт отдельные экземпляры данного класса
 * для вывода каждого поля!
 * Magento использует ЕДИНСТВЕННЫЙ экземпляр данного класса для вывода всех полей!
 * Поэтому в объектах данного класса нельзя кешировать информацию,
 * которая индивидуальна для поля конкретного поля!
 */
class Df_YandexMarket_Block_Conditions extends Mage_Adminhtml_Block_System_Config_Form_Field {



	/**
	 * @override
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	protected function _getElementHtml (Varien_Data_Form_Element_Abstract $element) {


		/** @var Df_YandexMarket_Model_Field_Conditions $field */
		$field =
			df_model (
				Df_YandexMarket_Model_Field_Conditions::getNameInMagentoFormat()
				,
				array (
					Df_YandexMarket_Model_Field_Conditions::PARAM__ELEMENT => $element
					,
					Df_YandexMarket_Model_Field_Conditions::PARAM__BLOCK => $this
				)
			)
		;

		df_assert ($field instanceof Df_YandexMarket_Model_Field_Conditions);


		/** @var string $result  */
		$result = $field->getHtml();

		$result .=
			sprintf (
				'<input type="hidden" value="0" name="%s"/>'
				,
				$element->getData('name')
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Block_Conditions';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}

}


