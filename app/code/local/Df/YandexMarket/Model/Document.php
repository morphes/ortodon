<?php

class Df_YandexMarket_Model_Document extends Df_Core_Model_SimpleXml_Document {



	/**
	 * @return bool
	 */
	public function hasEncodingWindows1251 () {
		return false;
	}



	/**
	 * @override
	 * @return Df_Varien_Simplexml_Element
	 */
	protected function createElement () {

		/** @var Df_Varien_Simplexml_Element $result  */
		$result = parent::createElement();

		$result
			->importArray (
				array (
					'shop' => $this->getDocumentData_Shop()
				)
			)
		;

		df_assert ($result instanceof Df_Varien_Simplexml_Element);

		return $result;
	}



	/**
	 * @override
	 * @return array
	 */
	protected function getAttributes () {
		return
			array (
				'date' => Zend_Date::now()->toString ('y-MM-dd HH:mm')
			)
		;
	}



	/**
	 * @override
	 * @return string
	 */
	protected function getDocType () {
		return "<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>";
	}



	/**
	 * @override
	 * @return string
	 */
	protected function getTagName() {
		return 'yml_catalog';
	}




	/**
	 * @return Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Category_Collection
	 */
	private function getCategories () {
	
		if (!isset ($this->_categories)) {
	
			/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Category_Collection $result  */
			$result = 	
				Mage::getResourceModel (
					Df_Catalog_Const::CATEGORY_COLLECTION_CLASS_MF
				)
			;
	
			df_helper()->catalog()->assert()->categoryCollection($result);
			
			$result->addIdFilter ($this->getCategoryIds());

			$result->addAttributeToSelect ('name');

			$this->_categories = $result;
		}
		
		df_helper()->catalog()->assert()->categoryCollection($this->_categories);
	
		return $this->_categories;
	}
	
	
	/**
	* @var Object
	*/
	private $_categories;

	
	
	
	/**
	 * @return array
	 */
	private function getCategoryIds () {
	
		if (!isset ($this->_categoryIds)) {
	
			/** @var array $result  */
			$result = array ();

			foreach ($this->getProducts() as $product) {
				/** @var Mage_Catalog_Model_Product $product */
				df_assert ($product instanceof Mage_Catalog_Model_Product);

				$result =
					array_merge (
						$result
						,
						$product->getCategoryIds()
					)
				;
			}
	
			df_assert_array ($result);
	
			$this->_categoryIds = $result;
		}
	
	
		df_result_array ($this->_categoryIds);
	
		return $this->_categoryIds;
	}
	
	
	/**
	* @var array
	*/
	private $_categoryIds;	
	



	/**
	 * @return array
	 */
	private function getDocumentData_Categories () {

		/** @var array $result  */
		$result = array ();

		foreach ($this->getCategories() as $category) {
			/** @var Mage_Catalog_Model_Category $category */
			df_assert ($category instanceof Mage_Catalog_Model_Category);


			if (0 < $category->getId()) {

				/** @var array $attributes  */
				$attributes =
					array (
						'id' => $category->getId()
					)
				;

				if (0 < $category->getParentId()) {
					$attributes['parentId'] = $category->getParentId();
				}

				$result []=
					array (
						Df_Varien_Simplexml_Element::KEY__ATTRIBUTES => $attributes
						,
						Df_Varien_Simplexml_Element::KEY__VALUE =>
							Df_Varien_Simplexml_Element::markAsCData (
									df_empty (
										$category->getName()
									)
								?
									$category->getId()
								:
									$category->getName()
							)
					)
				;

			}
		}

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return array
	 */
	private function getDocumentData_Currencies () {

		/** @var array $result  */
		$result =
			array (
				array (
					Df_Varien_Simplexml_Element::KEY__ATTRIBUTES =>
						array (
							'id' => $this->getSettings()->general()->getCurrency()->getCode()

							/**
							 * Параметр rate указывает курс валюты к курсу основной валюты,
							 * взятой за единицу (валюта, для которой rate="1").
							 *
							 * В качестве основной валюты (для которой установлено rate="1")
							 * могут быть использованы только рубль (RUR, RUB),
							 * белорусский рубль (BYR),
							 * гривна (UAH)
							 * или тенге (KZT).
							 */
							,
							'rate' => 1
						)
				)
			)
		;

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return array
	 */
	private function getDocumentData_Offers () {

		/** @var array $result  */
		$result = array ();


		foreach ($this->getProducts() as $product) {
			/** @var Mage_Catalog_Model_Product $product */
			df_assert ($product instanceof Mage_Catalog_Model_Product);


			/** @var Df_YandexMarket_Model_Processor_Offer $processor */
			$processor =
				df_model (
					Df_YandexMarket_Model_Processor_Offer::getNameInMagentoFormat()
					,
					array (
						Df_YandexMarket_Model_Processor_Offer::PARAM__PRODUCT => $product
					)
				)
			;

			df_assert ($processor instanceof Df_YandexMarket_Model_Processor_Offer);


			$result []= $processor->getDocumentData();
		}

		df_result_array ($result);

		return $result;
	}



	/**
	 * @return array
	 */
	private function getDocumentData_Shop () {

		/** @var array $result  */
		$result =
			array (
				'name' => $this->getSettings()->shop()->getNameForClients()
				,
				'company' => $this->getSettings()->shop()->getNameForAdministration()
				,
				'url' =>
					df_helper()->yandexMarket()->preprocessUrl (
						$this->getStore()->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB)
					)
				,
				'platform' => 'Российская сборка Magento'
				,
				'version' => rm_version()
				,
				'agency' => $this->getSettings()->shop()->getAgency()
				,
				'email' => $this->getSettings()->shop()->getSupportEmail()
				,
				'currencies' =>
					array (
						'currency' => $this->getDocumentData_Currencies()
					)
				,
				'categories' =>
					array (
						'category' => $this->getDocumentData_Categories()
					)
			)
		;

		if (!is_null ($this->getSettings()->general()->getLocalDeliveryCost())) {
			$result ['local_delivery_cost'] =
				$this->getSettings()->general()->getLocalDeliveryCost()
			;
		}

		$result ['offers'] =
			array (
				'offer' => $this->getDocumentData_Offers()
			)
		;

		df_result_array ($result);

		return $result;
	}



	/**
	 * @return Mage_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	private function getProducts () {

		/** @var Mage_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $result  */
		$result = $this->cfg (self::PARAM__PRODUCTS);

		df_helper()->catalog()->assert()->productCollection ($result);

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
				self::PARAM__PRODUCTS, 'Varien_Data_Collection_Db'
			)
		;
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
	 * @return Mage_Core_Model_Store
	 */
	private function getStore () {

		/** @var Mage_Core_Model_Store $result  */
		$result = df_helper()->yandexMarket()->getStoreProcessed();

		df_assert ($result instanceof Mage_Core_Model_Store);

		return $result;
	}



	const PARAM__PRODUCTS = 'products';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_Document';
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


