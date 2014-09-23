<?php

class Df_YandexMarket_Model_System_Config_Backend_Conditions extends Df_Admin_Model_Config_Backend {


	/**
	 * @overide
	 * @return Df_YandexMarket_Model_System_Config_Backend_Conditions
	 */
	protected function _beforeSave () {

		try {
			if ($this->validate()) {

				$this->getRule()
					->loadPost (
						array (
							'conditions' =>
								df_a (
									df_a ($this->getPost(), 'rule')
									,
									'conditions'
								)
							,
							'website_ids' => $this->getWebsiteIds()
						)
					)
				;
				$this->getRule()->setDataChanges (true);
				$this->getRule()->save ();

				df_assert_between ($this->getRule()->getId(), 1);

				$this->setValue ($this->getRule()->getId());
			}
		}
		catch (Exception $e) {
			df_log_exception ($e);
			rm_session()->addError ($e->getMessage());
		}

		parent::_beforeSave();

		return $this;
	}




	/**
	 * @return Df_YandexMarket_Model_System_Config_Backend_Conditions
	 */
	private function validate () {

		/** @var bool|array $validateResult */
		$validateResult =
			$this
				->getRule()->validateData (
					new Varien_Object (
						$this->getPost()
					)
				)
		;

		if (true !== $validateResult) {
			foreach($validateResult as $errorMessage) {
				rm_session()->addError ($errorMessage);
			}
		}

		return (true === $validateResult);
	}

	
	
	
	/**
	 * @return array
	 */
	private function getPost () {
	
		if (!isset ($this->_post)) {
	
			/** @var array $result  */
			$result =
				df()->request()
					->filterDates (
						df()->request()->getController()->getRequest()->getPost()
						,
						array('from_date', 'to_date')
					)
			;
	
			df_assert_array ($result);
	
			$this->_post = $result;
		}
	
	
		df_result_array ($this->_post);
	
		return $this->_post;
	}
	
	
	/**
	* @var array
	*/
	private $_post;	
	
	
	
	
	
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


			if (0 < $this->getRuleExistingId()) {
				$result->load ($this->getRuleExistingId());
				df_assert_between ($result->getId(), 1);
			}


			$result
				->addData (
					array (
						'name' => 'Яндекс.Маркет'
						,
						'description' => 'не редактировать'
					)
				)
			;

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
	private function getRuleExistingId () {

		if (!isset ($this->_ruleExistingId)) {

			/** @var int $result  */
			$result = intval ($this->getOldValue());

			df_assert_integer ($result);

			$this->_ruleExistingId = $result;
		}

		df_result_integer ($this->_ruleExistingId);

		return $this->_ruleExistingId;
	}


	/**
	* @var int
	*/
	private $_ruleExistingId;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_YandexMarket_Model_System_Config_Backend_Conditions';
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


