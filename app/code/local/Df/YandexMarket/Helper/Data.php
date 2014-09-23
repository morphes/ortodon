<?php

class Df_YandexMarket_Helper_Data extends Mage_Core_Helper_Data {


	/**
	 * @return Mage_Core_Model_Store
	 */
	public function getStoreProcessed () {

		if (!isset ($this->_storeProcessed)) {

			/** @var Df_Core_Model_ActionHelper_StoreProcessed $helper */
			$helper =
				df_model (
					Df_Core_Model_ActionHelper_StoreProcessed::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_ActionHelper_StoreProcessed::PARAM__MODULE_NAME =>
							'Яндекс.Маркет'
						,
						Df_Core_Model_ActionHelper_StoreProcessed::PARAM__URL_EXAMPLE =>
							'http://example.ru/df-yandex-market/yml/?store-view=<системное имя витрины>/'
					)
				)
			;

			df_assert ($helper instanceof Df_Core_Model_ActionHelper_StoreProcessed);


			/** @var Mage_Core_Model_Store $result */
			$result = $helper->getStoreProcessed();


			df_assert ($result instanceof Mage_Core_Model_Store);

			$this->_storeProcessed = $result;
		}


		df_assert ($this->_storeProcessed instanceof Mage_Core_Model_Store);

		return $this->_storeProcessed;

	}


	/**
	* @var Mage_Core_Model_Store
	*/
	private $_storeProcessed;





	/**
	 * @param string $url
	 * @return string
	 */
	public function preprocessUrl ($url) {

		/** @var string $result  */
		$result =
				!df_is_it_my_local_pc()
			?
					$url
			:
				str_replace (
					$this->getStoreProcessed()->getBaseUrl (
						Mage_Core_Model_Store::URL_TYPE_WEB
					)
					,
					self::URL_BASE_TEST
					,
					$url
				)
		;


		$schemeAndOther = explode ('//', $result);

		$result =
			implode (
				'//'
				,
				array (
					df_a ($schemeAndOther, 0)
					,
					implode (
						'/'
						,
						array_map (
							'urlencode'
							,
							explode (
								'/'
								,
								df_a ($schemeAndOther, 1)
							)
						)
					)
				)
			)
		;


		df_result_string ($result);

		return $result;
	}




	/**
	 * @param string $message
	 * @return Df_YandexMarket_Helper_Data
	 */
	public function log ($message) {

		df_param_string ($message, 0);

		Mage::log ($message, null, 'rm.yandex.market.log', true);

		return $this;
	}




	const URL_BASE_TEST = 'http://tagestrade.ru/';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Helper_Data';
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


