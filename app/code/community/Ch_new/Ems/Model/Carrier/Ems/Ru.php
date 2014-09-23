<?php
/**
 * @copyright Copyright (c) 2013 Sergey Cherepanov. (http://www.cherepanov.org.ua)
 * @license Creative Commons Attribution 3.0 License
 */

class Ch_Ems_Model_Carrier_Ems_Ru
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    const CARRIER_CODE  = 'ch_ems_ru';

    /** @var  Ch_Ems_Helper_Data */
    protected $_helper;
    /** @var  Ch_Ems_Model_Api_Ru */
    protected $_api;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->_helper = Mage::helper('ch_ems');
        $this->_code   = self::CARRIER_CODE;
        parent::__construct($data);
    }

    /**
     * @return Ch_Ems_Model_Api_Ru
     */
    public function getApi()
    {
        if (is_null($this->_api)) {
            $this->_api = Mage::getModel('ch_ems/api_ru');
            $this->_api->setUri(trim(trim($this->getConfigData('api_uri')), '/'));
        }

        return $this->_api;
    }

    /**
     * CHeck is carrier enabled and service api available
     *
     * @return bool
     */
    protected function _isCarrierAvailable()
    {
        if ($this->getConfigFlag('active') && 'RU' == Mage::getStoreConfig('shipping/origin/country_id')) {
            return $this->getApi()->isAvailable();
        }

        return false;
    }

    /**
     * @param int $regionId
     * @param string $regionCode
     * @return bool|string
     */
    protected function _getEmsLocation($regionId, $regionCode)
    {
        $result = false;

        /** @var Ch_Ems_Model_Ems_Region $emsRegion */
        $emsRegion = Mage::getModel('ch_ems/ems_region');
        $emsRegion->load($regionId);
        if ($emsRegion->getId()) {
            $emsRegionCode = $emsRegion->getData('ems_code');
            if ($emsRegionCode) {
                $result = 'region--' . $emsRegionCode;
            } else if ($regionCode) {
                switch ($regionCode) {
                    case 'MOW':
                        // Is Moscow or Petersburg
                        $result = 'city--moskva';
                        break;
                    case 'SPE':
                        // Is Petersburg
                        $result = 'city--sankt-peterburg';
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->_isCarrierAvailable()) {
            return false;
        }


        /** @var Mage_Directory_Model_Region $storeRegion */
//        $storeRegion = Mage::getModel('directory/region');
//        $storeRegion->load(Mage::getStoreConfig('shipping/origin/region_id'));
        $country_hide = false;
        if ($country_hide) {
            $country = 0;
        }
        if (!$country) {
            $country = 0;
        }
        $county_edost = array(1960 => 'AU', 1961 => 'AT', 1962 => 'AZ', 1963 => 'AL', 1964 => 'DZ', 1965 => 'AS', 1966 => 'AI', 1968 => 'AO', 1969 => 'AD', 1970 => 'AG', 1971 => 'AN', 1972 => 'AR', 1973 => 'AM', 1974 => 'AW', 1975 => 'AF', 1976 => 'BS', 1977 => 'BD', 1978 => 'BB', 1979 => 'BH', 1980 => 'BY', 1981 => 'BZ', 1982 => 'BE', 1983 => 'BJ', 1984 => 'BM', 1985 => 'BG', 1986 => 'BO', 1988 => 'BA', 1989 => 'BW', 1990 => 'BR', 1991 => 'BN', 1992 => 'BF', 1993 => 'BI', 1994 => 'BT', 1995 => 'WF', 1996 => 'VU', 1997 => 'GB', 1998 => 'HU', 1999 => 'VE', 2000 => 'VG', 2001 => 'VI', 2002 => 'TL', 2003 => 'VN', 2004 => 'GA', 2005 => 'HT', 2006 => 'GY', 2007 => 'GM', 2008 => 'GH', 2009 => 'GP', 2010 => 'GT', 2011 => 'GN', 2012 => 'GQ', 2013 => 'GW', 2014 => 'DE', 2016 => 'GI', 2017 => 'HN', 2018 => 'HK', 2019 => 'GD', 2020 => 'GL', 2021 => 'GR', 2022 => 'GE', 2023 => 'GU', 2024 => 'DK', 2026 => 'DJ', 2027 => 'DM', 2028 => 'DO', 2029 => 'EG', 2030 => 'ZM', 2031 => 'CV', 2032 => 'ZW', 2033 => 'IL', 2034 => 'IN', 2035 => 'ID', 2036 => 'JO', 2037 => 'IQ', 2038 => 'IR', 2039 => 'IE', 2040 => 'IS', 2041 => 'ES', 2042 => 'IT', 2043 => 'YE', 2044 => 'KZ', 2045 => 'KY', 2046 => 'KH', 2047 => 'CM', 2048 => 'CA', 2049 => 'EQ', 2050 => 'QA', 2051 => 'KE', 2052 => 'CY', 2053 => 'KI', 2054 => 'CN', 2055 => 'CO', 2056 => 'KM', 2057 => 'CG', 2058 => 'CD', 2059 => 'KP', 2060 => 'KR', 2062 => 'CR', 2063 => 'CI', 2064 => 'CU', 2065 => 'KW', 2066 => 'CK', 2067 => 'KG', 2069 => 'LA', 2070 => 'LV', 2071 => 'LS', 2072 => 'LR', 2073 => 'LB', 2074 => 'LY', 2075 => 'LT', 2076 => 'LI', 2077 => 'LU', 2078 => 'MU', 2079 => 'MR', 2080 => 'MG', 2081 => 'YT', 2082 => 'MO', 2083 => 'MK', 2084 => 'MW', 2085 => 'MY', 2086 => 'ML', 2087 => 'MV', 2088 => 'MT', 2089 => 'MA', 2090 => 'MQ', 2091 => 'MH', 2092 => 'MX', 2093 => 'FM', 2094 => 'MZ', 2095 => 'MD', 2096 => 'MC', 2097 => 'MN', 2098 => 'MS', 2099 => 'MM', 2100 => 'NA', 2101 => 'NR', 2102 => 'KN', 2103 => 'NP', 2104 => 'NE', 2105 => 'NG', 2106 => 'NL', 2107 => 'NI', 2108 => 'NU', 2109 => 'NZ', 2110 => 'NC', 2111 => 'NO', 2112 => 'AE', 2113 => 'OM', 2114 => 'PK', 2115 => 'PW', 2116 => 'PA', 2117 => 'PG', 2118 => 'PY', 2119 => 'PE', 2120 => 'PL', 2121 => 'PT', 2122 => 'PR', 2123 => 'RE', 2124 => 'RW', 2125 => 'RO', 2126 => 'MP', 2127 => 'SV', 2128 => 'WS', 2129 => 'SM', 2130 => 'ST', 2131 => 'SA', 2132 => 'SZ', 2134 => 'SC', 2136 => 'SN', 2137 => 'VC', 2138 => 'KN', 2139 => 'KN', 2140 => 'LC', 2145 => 'SG', 2146 => 'SY', 2147 => 'SK', 2148 => 'SI', 2149 => 'SB', 2150 => 'SO', 2152 => 'SD', 2153 => 'SR', 2154 => 'US', 2155 => 'SL', 2156 => 'TJ', 2157 => 'TH', 2158 => 'PF', 2159 => 'TW', 2160 => 'TZ', 2161 => 'TG', 2162 => 'TO', 2163 => 'TT', 2164 => 'TV', 2165 => 'TN', 2166 => 'TM', 2167 => 'TC', 2168 => 'TR', 2169 => 'UG', 2170 => 'UZ', 2171 => 'UA', 2172 => 'UY', 2174 => 'FO', 2175 => 'FJ', 2176 => 'PH', 2177 => 'FI', 2178 => 'FK', 2179 => 'FR', 2180 => 'GF', 2181 => 'PF', 2182 => 'HR', 2183 => 'CF', 2184 => 'TD', 2186 => 'CZ', 2187 => 'CL', 2188 => 'CH', 2189 => 'SE', 2191 => 'LK', 2192 => 'EC', 2193 => 'ER', 2194 => 'EE', 2195 => 'ET', 2196 => 'ZA', 2197 => 'JM', 2198 => 'JP', 0 => 'RU');
        $country = array_search($request->getDestCountryId(), $county_edost);
        if (isset($_SESSION['city_for_cart'])) {
            $to_city = Mage::getModel('directory/region')->load($_SESSION['city_for_cart'])->getCode();
            $city_code = Mage::getModel('directory/region')->load($_SESSION['city_for_cart'])->getEmsCode();
        } else {
            if ($country == 0) {
                $to_city = Mage::getModel('directory/region')->load($request->getDestRegionId())->getCode();
                $city_code = Mage::getModel('directory/region')->load($request->getDestRegionId())->getEmsCode();
            } else {
                $to_city = $country;
                $city_code = '0';
            }
        }

//        $emsFrom = $this->_getEmsLocation($storeRegion->getId(), $storeRegion->getCode());
//        $emsTo   = $this->_getEmsLocation($request->getDestRegionId(), $request->getDestRegionCode());
        $emsFrom = 'region--rostovskaja-oblast';
        $emsTo = 'region--'.$city_code;
        $packageWeight  = $request->getPackageWeight();


        if (!$emsFrom || !$emsTo) {
            return false;
        }

        $emsWeightInfo = $this->getApi()->makeRequest(array(
            'method' => Ch_Ems_Model_Api_Ru::REST_METHOD_GET_MAX_WEIGHT,
            'from'   => $emsFrom,
            'to'     => $emsTo,
        ));

        if ($packageWeight > $emsWeightInfo['max_weight']) {
            return false;
        }

        /** @var $result Mage_Shipping_Model_Rate_Result */
        $result = Mage::getModel('shipping/rate_result');

        $emsResponse = $this->getApi()->makeRequest(array(
            'method' => Ch_Ems_Model_Api_Ru::REST_METHOD_CALCULATE,
            'from'   => $emsFrom,
            'to'     => $emsTo,
            'weight' => $packageWeight
        ));

        /** @var $method Mage_Shipping_Model_Rate_Result_Method */
        $method = Mage::getModel('shipping/rate_result_method');
        $method->addData(array(
            'carrier'       => $this->_code,
            'carrier_title' => $this->getConfigData('name'),
            'method'        => 'default',
            'method_title'  => Mage::helper('ch_ems')->__(
                'Delivery %d - %d day(s)',
                $emsResponse['term']['min'],
                $emsResponse['term']['max']
            ),
            'price'         => (float) $emsResponse['price'],
            'cost'          => (float) $emsResponse['price'],
        ));
        $result->append($method);

        return $result;
    }

    public function getAllowedMethods()
    {
        return array($this->_code => $this->_helper->__('EMS Post'));
    }
}