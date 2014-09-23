<?php


class Df_YandexMarket_Model_System_Config_Backend_Currency
	/**
	 * Наследуемся именно от класса Df_Admin_Model_Config_Backend,
	 * чтобы в методе _beforeSave использовать класс
	 * Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported
	 */
	extends Df_Admin_Model_Config_Backend {



	/**
	 * @overide
	 * @return Df_YandexMarket_Model_System_Config_Backend_Currency
	 */
	protected function _beforeSave () {

		parent::_beforeSave();

		if (
			!in_array (
				$this->getValue()
				,
				Df_YandexMarket_Model_System_Config_Source_Currency::getAllowedCurrencies()
			)
		) {
			/** @var string $currencyName  */
			$currencyName = $this->getValue();

			try {
				/** @var Zend_Currency $result  */
				$currency =
					Mage::app()->getLocale()->currency (
						$this->getValue()
					)
				;

				$currencyName = $currency->getName();
			}
			catch (Exception $e) {
			}


			df_error (
				sprintf (
					'Яндекс.Маркет не допускает указанную Вами валюту «%s» в качестве основной валюты магазина.'
					,
					$currencyName
				)
			);
		}
		else {

			/** @var Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported $checker */
			$checker =
				df_model (
					Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported::getNameInMagentoFormat()
					,
					array (
						Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported::PARAM__CURRENCY_CODE => $this->getValue()
						,
						Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported::PARAM__BACKEND => $this
					)
				)
			;

			df_assert ($checker instanceof Df_Admin_Model_Config_BackendChecker_CurrencyIsSupported);


			$checker->check();
		}

		return $this;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_System_Config_Backend_Currency';
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


