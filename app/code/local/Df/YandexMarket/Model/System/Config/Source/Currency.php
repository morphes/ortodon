<?php

class Df_YandexMarket_Model_System_Config_Source_Currency
	extends Df_Admin_Model_Config_Source {


	/**
	 * @override
	 * @param bool $isMultiSelect
	 * @return array
	 */
    protected function toOptionArrayInternal ($isMultiSelect = false) {

		/** @var array $result  */
		$result = $this->getAsOptionArray();

		df_result_array ($result);

		return $result;
    }



	/**
	 * @return array
	 */
	private function getAsOptionArray () {

		if (!isset ($this->_asOptionArray)) {


			/** @var array $optionCurrenciesAll */
			$optionCurrenciesAll = Mage::app()->getLocale()->getOptionCurrencies();

			df_assert_array ($optionCurrenciesAll);


			/** @var array $optionCurrencyMap */
			$optionCurrencyMap =
				array_combine (
					df_column (
						$optionCurrenciesAll
						,
						'value'
					)
					,
					df_column (
						$optionCurrenciesAll
						,
						'label'
					)
				)
			;


			df_assert_array ($optionCurrencyMap);


			/** @var array $result */
			$result = array ();

			foreach (self::getAllowedCurrencies() as $currencyCode) {
				/** @var string $currencyCode */

				/** @var string|null $label  */
				$label = df_a ($optionCurrencyMap, $currencyCode);

				if (!is_null ($label)) {
					$result []=
						array (
							'value' => $currencyCode
							,
							'label' => $label
						)
					;
				}
			}


			df_assert_array ($result);

			$this->_asOptionArray = $result;
		}


		df_result_array ($this->_asOptionArray);

		return $this->_asOptionArray;

	}


	/**
	* @var array
	*/
	private $_asOptionArray;



	/**
	 * «В качестве основной валюты (для которой установлено rate="1")
	 * могут быть использованы только рубль (RUR, RUB),
	 * белорусский рубль (BYR), гривна (UAH) или тенге (KZT).»
	 *
	 * @link http://help.yandex.ru/partnermarket/?id=1111480
	 * @return array
	 */
	public static function getAllowedCurrencies () {
		return
			array (
				'RUB', 'UAH', 'KZT', 'BYR'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_System_Config_Source_Currency';
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

