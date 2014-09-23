<?php

class Df_YandexMarket_Model_Field_Conditions extends Df_Core_Model_Abstract {



	/**
	 * @return string
	 */
	public function getHtml () {

		/** @var string $result  */
		$result = $this->createForm()->toHtml();

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param Varien_Data_Form_Element_Fieldset $fieldset
	 * @return Varien_Data_Form_Element_Abstract
	 */
	private function createFieldConditions (Varien_Data_Form_Element_Fieldset $fieldset) {

		/** @var Varien_Data_Form_Element_Abstract $result */
		$result =
			$fieldset
				->addField (
					'conditions'
					,
					'text'
					,
					array (
						'name' => 'conditions',
						'label' => Mage::helper('catalogrule')->__('Conditions'),
						'title' => Mage::helper('catalogrule')->__('Conditions'),
						'required' => true,
					)
				)
		;


		/** @var Mage_Rule_Block_Conditions $blockRuleConditions */
		$blockRuleConditions = Mage::getBlockSingleton('rule/conditions');

		$result->setData ('rule', $this->getRule());
		$result
			->setRenderer (
				$blockRuleConditions
			)
		;


		df_assert ($result instanceof Varien_Data_Form_Element_Abstract);

		return $result;
	}




	/**
	 * @param Varien_Data_Form $form
	 * @return Varien_Data_Form_Element_Fieldset
	 */
	private function createFieldset (Varien_Data_Form $form) {

		/** @var Varien_Data_Form_Element_Fieldset $result  */
		$result =
			$form
				->addFieldset (
					'conditions_fieldset'
					,
					array ()
				)
				->setRenderer (
					$this->createRendererFieldset()
				)
		;

		df_assert ($result instanceof Varien_Data_Form_Element_Fieldset);

		$this->createFieldConditions ($result);

		return $result;
	}




	/**
	 * @return Varien_Data_Form
	 */
	private function createForm () {

		/** @var Varien_Data_Form $result */
		$result = new Varien_Data_Form();
		$result->setData ('html_id_prefix', 'rule_');

		$this->createFieldset ($result);

		df_assert ($result instanceof Varien_Data_Form);

		return $result;
	}




	/**
	 * @return Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset
	 */
	private function createRendererFieldset () {

		/** @var Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset $result */
		$result = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset');
		$result->setTemplate('df/yandex_market/conditions.phtml');
		$result
			->setData (
				'new_child_url'
				,
				$this->getBlock()->getUrl(
					'*/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'
				)
			)
		;
		df_assert ($result instanceof Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset);

		return $result;
	}




	/**
	 * @return Mage_Core_Block_Abstract
	 */
	private function getBlock () {
		return $this->cfg (self::PARAM__BLOCK);
	}




	/**
	 * @return Varien_Data_Form_Element_Abstract
	 */
	private function getElement () {
		return $this->cfg (self::PARAM__ELEMENT);
	}




	/**
	 * @return Mage_CatalogRule_Model_Rule
	 */
	private function getRule () {

		if (!isset ($this->_rule)) {

			/** @var Mage_CatalogRule_Model_Rule $result  */
			$result =
				df_model (
					'catalogrule/rule'
				)
			;

			df_assert ($result instanceof Mage_CatalogRule_Model_Rule);

			if (0 < $this->getRuleId()) {
				$result->load ($this->getRuleId());
			}

			$result->getConditions()->setJsFormObject ('rule_conditions_fieldset');

			$this->_rule = $result;
		}

		df_assert ($this->_rule instanceof Mage_CatalogRule_Model_Rule);

		return $this->_rule;
	}


	/**
	* @var Mage_CatalogRule_Model_Rule
	*/
	private $_rule;




	/**
	 * @return int
	 */
	private function getRuleId () {

		/** @var int $result  */
		$result = intval ($this->getElement()->getData ('value'));

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__BLOCK, 'Mage_Core_Block_Abstract'
			)
			->validateClass (
				self::PARAM__ELEMENT, 'Varien_Data_Form_Element_Abstract'
			)
		;
	}



	const PARAM__BLOCK = 'block';
	const PARAM__ELEMENT = 'element';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_Field_Conditions';
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


