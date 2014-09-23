<?php

class Df_YandexMarket_YmlController extends Mage_Core_Controller_Front_Action {

	/**
	 * @return void
	 */
    public function indexAction() {

		/** @var Df_YandexMarket_Model_Action_Front $action */
		$action =
			df_model (
				Df_YandexMarket_Model_Action_Front::getNameInMagentoFormat()
				,
				array (
					Df_YandexMarket_Model_Action_Front::PARAM__CONTROLLER => $this
				)
			)
		;

		df_assert ($action instanceof Df_YandexMarket_Model_Action_Front);

		$action->process();
    }

}


