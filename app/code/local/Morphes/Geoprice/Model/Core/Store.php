<?php 
class Morphes_Geoprice_Model_Core_Store extends Mage_Core_Model_Store {

    public function convertPrice($price, $format = false, $includeContainer = true) {
        $geoip_info = Mage::getModel('geoip/geoip')->getCollection();
        $geoip_coll = $geoip_info->getData();
        $store_config = Mage::getStoreConfig('general/region');
        $model_city = Mage::getModel('geoip/abstract')->data;
        if(isset($model_city['region'])) {
            if($model_city['region']=='Ростовская область') {
                $price = $price * 0.9;
            }
        }

        if ($this->getCurrentCurrency() && $this->getBaseCurrency()) {
            $value = $this->getBaseCurrency()->convert($price, $this->getCurrentCurrency());
        } else {
            $value = $price;
        }

        if ($this->getCurrentCurrency() && $format) {
            $value = $this->formatPrice($value, $includeContainer);
        }
        return $value;
    }    
}