<?php

class Df_YandexMarket_Model_Processor_Offer extends Df_Core_Model_Abstract {



	/**
	 * @return array
	 */
	public function getDocumentData () {

		/** @var array $attributes */
		$attributes =
			array (
				'id' => $this->getProduct()->getId()
				,
				'available' =>
					df_output()->convertBooleanToString (
						$this->getProduct()->isInStock()
					)
			)
		;

		df_assert_array ($attributes);


		if ($this->hasVendorInfo()) {
			$attributes ['type'] = 'vendor.model';
		}


		/** @var array $result */
		$result =
			array (
				Df_Varien_Simplexml_Element::KEY__ATTRIBUTES => $attributes
				,
				Df_Varien_Simplexml_Element::KEY__VALUE => $this->getValue()
			)
		;



		df_result_array ($result);

		return $result;
	}




	/**
	 * @param string|float $money
	 * @return string
	 */
	private function formatMoney ($money) {

		/** @var string $result  */
		$result =
			sprintf (
				floatval ($money)
				,
				'.2f'
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return int
	 */
	private function getCategoryId () {

		/** @var int $result  */
		$result = null;

		if (1 > count ($this->getProduct()->getCategoryIds())) {
			df_error (
				sprintf (
					'Привяжите товар «%s» («%s») хотя бы к одному товарному разделу'
					,
					$this->getProduct()->getSku()
					,
					$this->getProduct()->getName()
				)
			);
		}
		else {
			$result =
				df_array_first (
					$this->getProduct()->getCategoryIds()
				)
			;
		}

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	private function getPriceAsText () {

		/** @var string $result  */
		$result =
			$this->formatMoney (
				df_helper()->directory()->currency()->convertFromBase (
					floatval (
						$this->getProduct()->getPrice()
					)
					,
					$this->getSettings()->general()->getCurrency()
				)
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {

		/** @var Object $result  */
		$result =
			$this->cfg (self::PARAM__PRODUCT)
		;

		df_assert ($result instanceof Mage_Catalog_Model_Product);

		return $result;
	}









	/**
	 * @return Df_YandexMarket_Helper_Settings
	 */
	private function getSettings () {

		/** @var Df_YandexMarket_Helper_Settings $result  */
		$result = df_cfg()->yandexMarket();

		df_assert ($result instanceof Df_YandexMarket_Helper_Settings);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getValue () {

		/** @var array $result  */
		$result =
			array (
				'url' =>
					df_helper()->yandexMarket()->preprocessUrl (
						$this->getProduct()->getProductUrl()
					)


				,
				'price' => $this->getPriceAsText()

				,
				'currencyId' => $this->getSettings()->general()->getCurrency()->getId()

				,
				'categoryId' => $this->getCategoryId()
			)
		;


		if (!is_null ($this->getProduct()->getData('image'))) {
			$result ['picture'] =
				df_helper()->yandexMarket()->preprocessUrl (
					$this->getProduct()->getMediaConfig()->getMediaUrl($this->getProduct()->getData('image'))
				)
			;
		}


		if (!$this->hasVendorInfo()) {

			$result ['name'] =
				Df_Varien_Simplexml_Element::markAsCData (
					$this->getProduct()->getName()
				)
			;

		}
		else {

			$result =
				array_merge (
					$result
					,
					array (
						'vendor' => $this->getProduct()->getAttributeText ('manufacturer')
						,
						'vendorCode' => $this->getProduct()->getData ('manufacturer')
						,
						'model' =>
							Df_Varien_Simplexml_Element::markAsCData (
								$this->getProduct()->getName()
							)
						,
						'description' =>
							Df_Varien_Simplexml_Element::markAsCData (
								df_convert_null_to_empty_string (
									$this->getProduct()->getData('description')
								)
							)
					)
				)
			;
		}



		if (!is_null ($this->getProduct()->getData ('country_of_manufacture'))) {
			$result ['country_of_origin'] =
				$this->getProduct()->getAttributeText ('country_of_manufacture')
			;
		}


		if ($this->getSettings()->general()->hasPointsOfSale()) {
			$result =
				array_merge (
					$result
					,
					array (
						'store' =>
							df_output()->convertBooleanToString (
								$this->getProduct()->isSalable()
							)
						,
						'pickup' =>
							df_output()->convertBooleanToString (
								$this->getSettings()->general()->isPickupAvailable()
							)
						,
						'delivery' =>
							df_output()->convertBooleanToString (
								true
							)
					)
				)
			;
		}

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return bool
	 */
	private function hasVendorInfo () {

		/** @var bool $result  */
		$result = !is_null ($this->getProduct()->getData ('manufacturer'));

		df_result_boolean ($result);

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
				self::PARAM__PRODUCT, 'Mage_Catalog_Model_Product'
			)
		;
	}


	const PARAM__PRODUCT = 'product';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_Processor_Offer';
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


