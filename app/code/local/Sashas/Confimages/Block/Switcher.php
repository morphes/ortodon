<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Confimages
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Confimages_Block_Switcher extends Mage_Core_Block_Template
{
	protected $_product = null;
	protected $_is_enabled =  null;
	protected $_allow_placeholder =  null;
	protected $_customX =  null;
	protected $_customY =  null;
	public function __construct()
	{
		$_storeId = Mage::app()->getStore()->getStoreId();
		$this->_is_enabled=Mage::getStoreConfig('confimages/confimages_group/extension_enabled',$_storeId);
		$this->_allow_placeholder=Mage::getStoreConfig('confimages/confimages_group/show_placeholder',$_storeId);
		$this->_customX=Mage::getStoreConfig('confimages/confimages_group/custom_x',$_storeId);
		$this->_customY=Mage::getStoreConfig('confimages/confimages_group/custom_y',$_storeId);
		$this->setTemplate('confimages/view.phtml');		 
	}
	
	public function getProduct()
	{
		if ($this->_product==null) {
			$this->_product=Mage::registry('current_product');
		}
		return $this->_product;
	}
	
	public function isEnabled(){
		return $this->_is_enabled;
	}
	
	public function AllowPlaceholder(){
		return $this->_allow_placeholder;
	}

	public function getCustomY(){
		return $this->_customY;
	}
	
	public function getCustomX(){
		return $this->_customX;
	}
	public function getProductOptionsJs(){		
		$_product=$this->getProduct();
		$extension_enabled=$this->isEnabled();
		$allow_placeholders=$this->AllowPlaceholder();
		 
		$custom_x=$this->getCustomY();
		$custom_y=$this->getCustomX();
		if (!$custom_x && !$custom_y)
			$custom_x=$custom_y=400;
		$options=array();
		
		
		if ($extension_enabled && $_product->getTypeId() !='configurable')
			return json_encode($options);			
	 	 
		$allProducts =$_product->getTypeInstance()->getUsedProducts();
		 
		foreach ($allProducts as $product) {
			$productId  = $product->getId();
			
			$image=(string)$product->getImage();
			if (!$allow_placeholders && $image!='no_selection' && $image){
				$options[$productId]['small_image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize($custom_x,$custom_y);
				$options[$productId]['full_image'] = (string)Mage::helper('catalog/image')->init($product, 'image');
			} elseif ($allow_placeholders) {
				if ($custom_x && $custom_y)
					$options[$productId]['small_image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize($custom_x,$custom_y);
					$options[$productId]['full_image'] = (string)Mage::helper('catalog/image')->init($product, 'image');
			}
			
			$options['-1']['full_image'] = (string)Mage::helper('catalog/image')->init($_product, 'image');
			$options['-1']['small_image'] = (string)Mage::helper('catalog/image')->init($_product, 'image')->resize($custom_x,$custom_y);
			
				}
		return json_encode($options);
	}
	
	public function getAttributeHandlers(){
		$_product=$this->getProduct();
		$extension_enabled=$this->isEnabled();
		$js_observe_onchange_handles="";
		if ($extension_enabled && $_product->getTypeId() =='configurable'){
			$attributes=$_product->getTypeInstance()->getConfigurableAttributes();
			foreach ( $attributes as $attribute) {
				/*$js_observe_onchange_handles.="$('attribute".$attribute['attribute_id']."').on('change', conf_change_image);";*/
				$js_observe_onchange_handles.="document.getElementById('attribute".$attribute['attribute_id']."').addEventListener('change',  function(){   conf_change_image(".$attribute['attribute_id'].", this.value) }, false);";
			}
		}
 		return $js_observe_onchange_handles;
	}
		
}