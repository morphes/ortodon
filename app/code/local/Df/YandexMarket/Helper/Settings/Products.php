<?php

class Df_YandexMarket_Helper_Settings_Products extends Df_Core_Helper_Settings {



	/**
	 * @return int
	 */
	public function getConditions () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_yandex_market/products/conditions'
					,
					df_helper()->yandexMarket()->getStoreProcessed()
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @return Mage_CatalogRule_Model_Rule
	 */
	public function getRule () {
	
		if (!isset ($this->_rule)) {
	
			/** @var Mage_CatalogRule_Model_Rule $result  */
			$result =
				df_model (
					'catalogrule/rule'
				)
			;

			df_assert ($result instanceof Mage_CatalogRule_Model_Rule);


			$result->load ($this->getConditions());
			df_assert_between ($result->getId(), 1);


			df_assert ($result instanceof Mage_CatalogRule_Model_Rule);
	
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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Helper_Settings_Products';
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