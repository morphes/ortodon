<?php

class Df_YandexMarket_Helper_Settings_Shop extends Df_Core_Helper_Settings {


	/**
	 * @return string
	 */
	public function getAgency () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_yandex_market/shop/agency'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_string ($result);

		return $result;
	}



	/**
	 * @return string
	 */
	public function getNameForAdministration () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_yandex_market/shop/name_for_administration'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_string ($result);

		return $result;
	}



	/**
	 * @return string
	 */
	public function getNameForClients () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_yandex_market/shop/name_for_clients'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
			)
		;

		df_result_string ($result);

		return $result;
	}



	/**
	 * @return string
	 */
	public function getSupportEmail () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_yandex_market/shop/support_email'
				,
				df_helper()->yandexMarket()->getStoreProcessed()
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
		return 'Df_YandexMarket_Helper_Settings_Shop';
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