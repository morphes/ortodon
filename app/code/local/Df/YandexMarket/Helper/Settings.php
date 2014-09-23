<?php

class Df_YandexMarket_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_YandexMarket_Helper_Settings_General
	 */
	public function general () {

		/** @var Df_YandexMarket_Helper_Settings_General $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_YandexMarket_Helper_Settings_General $result  */
			$result = Mage::helper (Df_YandexMarket_Helper_Settings_General::getNameInMagentoFormat());

			df_assert ($result instanceof Df_YandexMarket_Helper_Settings_General);
		}

		return $result;
	}



	/**
	 * @return Df_YandexMarket_Helper_Settings_Products
	 */
	public function products () {

		/** @var Df_YandexMarket_Helper_Settings_Products $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_YandexMarket_Helper_Settings_Products $result  */
			$result = Mage::helper (Df_YandexMarket_Helper_Settings_Products::getNameInMagentoFormat());

			df_assert ($result instanceof Df_YandexMarket_Helper_Settings_Products);
		}

		return $result;
	}



	/**
	 * @return Df_YandexMarket_Helper_Settings_Shop
	 */
	public function shop () {

		/** @var Df_YandexMarket_Helper_Settings_Shop $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_YandexMarket_Helper_Settings_Shop $result  */
			$result = Mage::helper (Df_YandexMarket_Helper_Settings_Shop::getNameInMagentoFormat());

			df_assert ($result instanceof Df_YandexMarket_Helper_Settings_Shop);
		}

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Helper_Settings';
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