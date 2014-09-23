<?php

class Df_YandexMarket_Helper_Settings_General extends Df_Core_Helper_Settings {
	
	
	
	/**
	 * @return Mage_Directory_Model_Currency
	 */
	public function getCurrency () {
	
		if (!isset ($this->_currency)) {


			/** @var string $currencyCode  */
			$currencyCode =
				Mage::getStoreConfig (
					'df_yandex_market/general/currency'
					,
					df_helper()->yandexMarket()->getStoreProcessed()
				)
			;

			df_result_string ($currencyCode);


	
			/** @var Mage_Directory_Model_Currency $result  */
			$result = 	
				df_model (
					Df_Directory_Const::CURRENCY_CLASS_MF
				)
			;
	
			df_assert ($result instanceof Mage_Directory_Model_Currency);


			$result->load ($currencyCode);

	
			$this->_currency = $result;
		}
	
		df_assert ($this->_currency instanceof Mage_Directory_Model_Currency);
	
		return $this->_currency;
	}
	
	
	/**
	* @var Mage_Directory_Model_Currency
	*/
	private $_currency;	
	


	/**
	 * @return int|null
	 */
	public function getLocalDeliveryCost () {

		/** @var int|null $result  */
		$result =
			Mage::getStoreConfig (
				'df_yandex_market/general/local_delivery_cost'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		/**
		 * '' does not appear to be an integer
		 */
		if (df_empty ($result)) {
			$result = 0;
		}

		if (!is_null ($result)) {
			df_result_integer ($result);
		}

		return $result;
	}



	/**
	 * @return bool
	 */
	public function hasPointsOfSale () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_yandex_market/general/has_points_of_sale'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_yandex_market/general/enabled'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return bool
	 */
	public function isPickupAvailable () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_yandex_market/general/pickup'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Helper_Settings_General';
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