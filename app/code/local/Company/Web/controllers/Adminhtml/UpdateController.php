<?php

class Company_Web_Adminhtml_UpdateController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('web/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function kancAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Обновить Атрибуты Канцелярии:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_update/kancparse') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="filekanc"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }
   
    public function booksAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Обновить Атрибуты Книг:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_update/booksparse') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="filebooks"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }

    public function kidsAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Обновить Атрибуты Развивающей Продукции:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_update/kidsparse') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="filekids"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }
    public function suvenirAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Обновить Атрибуты Сувенирной Продукции:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_update/suvenirparse') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="filesuvenir"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }
 
    public function pricekancAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Загрузить Прайс Канцелярии:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_update/loadpricekanc') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="pricekanc"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }

    public function pricebooksAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Загрузить Прайс Книг:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/loadpricebooks') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="pricebooks"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }
    public function pricekidsAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Загрузить Прайс Развивающей Продукции:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/loadpricekids') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="pricekids"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }
    public function pricesuvenirAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('
                   <h1>Загрузить Прайс Сувенирной Продукции:</h1> <br/>
                   <form action="' . Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/loadpricesuvenir') . '" method="post" enctype="multipart/form-data">
                      <input type="file" name="pricesuvenir"/>
<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
                      <input type="submit" value="Загрузить"/>
                   </form>
        ');
        $this->_addContent($block);
        $this->renderLayout();
    }

    public function successAction() {
        $this->loadLayout();
        //create a text block with the name of "example-block"
        $block = $this->getLayout()
                ->createBlock('core/text', 'example-block')
                ->setText('<h1>Файл успешно загружен</h1>');
        $this->_addContent($block);
        $this->renderLayout();
    }




    public function kancparseAction() {
	set_time_limit(0);
	ini_set('memory_limit','1024M');
	$skus=array();
        $qtys=array();
        $prices=array();
        $attrSetName = 'kanc';
        $attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($attrSetName, 'attribute_set_name')->getAttributeSetId();
        $products = Mage::getModel('catalog/product')->getCollection()->joinField('qty','cataloginventory/stock_item', 
                                                                                        'qty', 
											'product_id=entity_id', 
											'{{table}}.stock_id=1', 
											'left')
                                                                                         ->addAttributeToSelect('price')
    										         ->addFieldToFilter('attribute_set_id', $attributeSetId);

        foreach($products as $p){
            $skus[] = $p->getData('sku');
            $qtys[] =$p->getQty();
            $prices[]= $p->getPrice();
        }


        $sku = '';
        $appRoot = Mage::getRoot();
        $root = dirname($appRoot);
        error_reporting(E_ALL ^ E_NOTICE);
        require_once $root . DS . 'lib' . DS . 'ExcelReader' . DS . 'excelreader.php';
        $data = new Spreadsheet_Excel_Reader();
        $data->read($_FILES['filekanc']['tmp_name']);
        $found = '0';
        $csv = array();
        $sku = array(); 
        $check_est=array();
        $check_net=array();

       for ($i = 2; $i < $data->rowcount()+1; $i++) {
            $price = str_replace(" ", "", $data->val($i, F));
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . trim($data->val($i, B)) . "'";         //sku
            $csv[$i - 2][] = "'" . str_replace(',', '.', $price) . "'";   //price
            $csv[$i - 2][] = "'" . trim($data->val($i, E)) . "'";         //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'kanc'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock

	    $find_sku = '0';
            $finder = array_search($data->val($i,B), $skus,true);
	    if (((string)$finder!='') or ((string)$finder=='0')) {
    	        $find_sku='1';
                $index = array_search($data->val($i, B), $skus,true);
                if(($index!=false) or ($index===0)) {
                   unset($skus[(int)$index]);
                   unset($qtys[(int)$index]);
                   unset($prices[(int)$index]);
                }
	    }
            if($find_sku=='0') {
                $check_net[]=$data->val($i,B);
                $csv[$i - 2][] = "'Отключено'";                                //status
                $csv[$i - 2][] = "'Да'";                                  //new
            } else {
                $check_est[]=$data->val($i,B);
                $csv[$i - 2][] = "'Включено'";                                //status
                $csv[$i - 2][] = "'Нет'"; 
            }
        }
        //get old products to invisible
        foreach($skus as $key=>$value) {
            $i++;
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . $value . "'";                          //sku
            $csv[$i - 2][] = "'" . $prices[(int)$key] . "'";               //price
            $csv[$i - 2][] = "'" . $qtys[(int)$key] . "'";                //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'kanc'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Отключено'";                                //status
            $csv[$i - 2][] = "'Нет'";                                     //status 
        }

        $file = fopen($root . DS . 'var' . DS . 'import' . DS . 'update_kanc.csv', 'w');
        foreach ($csv as $line) {
            fputcsv($file, $line, "~");
        }
        fclose($file);
        $data = file_get_contents($root . DS . 'var' . DS . 'import' . DS . 'update_kanc.csv');
        $data = str_replace('"', "", $data);
        $data = str_replace("'", "\"", $data);
        file_put_contents($root . DS . 'var' . DS . 'import' . DS . 'update_kanc.csv', $data); 
        $url = Mage::getModel('adminhtml/url')->getUrl('adminhtml/system_convert_gui/run/id/12/files/update_kanc.csv/');
        $this->_redirectUrl($url);
    }

    public function booksparseAction() {
	set_time_limit(0);
	ini_set('memory_limit','1024M');
	$skus=array();
        $qtys=array();
        $prices=array();
        $attrSetName = 'books';
        $attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($attrSetName, 'attribute_set_name')->getAttributeSetId();
        $products = Mage::getModel('catalog/product')->getCollection()->joinField('qty','cataloginventory/stock_item', 
                                                                                        'qty', 
											'product_id=entity_id', 
											'{{table}}.stock_id=1', 
											'left')
                                                                                         ->addAttributeToSelect('price')
    										         ->addFieldToFilter('attribute_set_id', $attributeSetId);

        foreach($products as $p){
            $skus[] = $p->getData('sku');
            $qtys[] =$p->getQty();
            $prices[]= $p->getPrice();
        }


        $sku = '';
        $appRoot = Mage::getRoot();
        $root = dirname($appRoot);
        error_reporting(E_ALL ^ E_NOTICE);
        require_once $root . DS . 'lib' . DS . 'ExcelReader' . DS . 'excelreader.php';
        $data = new Spreadsheet_Excel_Reader();
        $data->read($_FILES['filebooks']['tmp_name']);

	    	// if (in_array($data->val(1052, B), $skus))  unset($skus[(int)$index]); else die('net'); echo "<pre>"; die(print_r($skus));
        $found = '0';
        $csv = array();
        $sku = array(); 
        for ($i = 2; $i < $data->rowcount()+1; $i++) {
            $price = str_replace(" ", "", $data->val($i, F));
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . trim($data->val($i, K)) . "'";         //sku
            $csv[$i - 2][] = "'" . str_replace(',', '.', $price) . "'";   //price
            $csv[$i - 2][] = "'" . trim($data->val($i, N)) . "'";         //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'books'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Включено'";                                //status
	    $find_sku = '0';
	    if (in_array($data->val($i, K), $skus)) {
    	        $find_sku='1';
                $index = array_search($data->val($i, K), $skus,true);
                if(($index!=false) or ((int)$index==0)) {
                  // echo $data->val($data->val($i,B))."<br/>";
                   unset($skus[(int)$index]);
                   unset($qtys[(int)$index]);
                   unset($prices[(int)$index]);
                }
	    }
            if($find_sku=='0') {
                $csv[$i - 2][] = "'Отключено'";                                //status
                $csv[$i - 2][] = "'Да'";                                  //new
            } else {
                $csv[$i - 2][] = "'Включено'";                                //status
                $csv[$i - 2][] = "'Нет'"; 
            }
        }

        //get old products to invisible
        foreach($skus as $key=>$value) {
            $i++;
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . $value . "'";                          //sku
            $csv[$i - 2][] = "'" . $prices[(int)$key] . "'";               //price
            $csv[$i - 2][] = "'" . $qtys[(int)$key] . "'";                //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'books'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Отключено'";                                //status
            $csv[$i - 2][] = "'Нет'";                                     //status 
        }
        $file = fopen($root . DS . 'var' . DS . 'import' . DS . 'update_books.csv', 'w');
        foreach ($csv as $line) {
            fputcsv($file, $line, "~");
        }
        fclose($file);
        $data = file_get_contents($root . DS . 'var' . DS . 'import' . DS . 'update_books.csv');
        $data = str_replace('"', "", $data);
        $data = str_replace("'", "\"", $data);
        file_put_contents($root . DS . 'var' . DS . 'import' . DS . 'update_books.csv', $data);
        $url = Mage::getModel('adminhtml/url')->getUrl('adminhtml/system_convert_gui/run/id/12/files/update_books.csv/');
        $this->_redirectUrl($url);
    }

    public function kidsparseAction() {
	set_time_limit(0);
	ini_set('memory_limit','1024M');
	$skus=array();
        $qtys=array();
        $prices=array();
        $attrSetName = 'kids';
        $attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($attrSetName, 'attribute_set_name')->getAttributeSetId();
        $products = Mage::getModel('catalog/product')->getCollection()->joinField('qty','cataloginventory/stock_item', 
                                                                                        'qty', 
											'product_id=entity_id', 
											'{{table}}.stock_id=1', 
											'left')
                                                                                         ->addAttributeToSelect('price')
    										         ->addFieldToFilter('attribute_set_id', $attributeSetId);

        foreach($products as $p){
            $skus[] = $p->getData('sku');
            $qtys[] =$p->getQty();
            $prices[]= $p->getPrice();
        }


        $sku = '';
        $appRoot = Mage::getRoot();
        $root = dirname($appRoot);
        error_reporting(E_ALL ^ E_NOTICE);
        require_once $root . DS . 'lib' . DS . 'ExcelReader' . DS . 'excelreader.php';
        $data = new Spreadsheet_Excel_Reader();
        $data->read($_FILES['filekids']['tmp_name']);

	    	// if (in_array($data->val(1052, B), $skus))  unset($skus[(int)$index]); else die('net'); echo "<pre>"; die(print_r($skus));
        $found = '0';
        $csv = array();
        $sku = array(); 
        for ($i = 2; $i < $data->rowcount()+1; $i++) {
            $price = str_replace(" ", "", $data->val($i, F));
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . trim($data->val($i, K)) . "'";         //sku
            $csv[$i - 2][] = "'" . str_replace(',', '.', $price) . "'";   //price
            $csv[$i - 2][] = "'" . trim($data->val($i, L)) . "'";         //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'kids_attr'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Включено'";                                //status
	    $find_sku = '0';
	    if (in_array($data->val($i, K), $skus)) {
    	        $find_sku='1';
                $index = array_search($data->val($i, K), $skus,true);
                if(($index!=false) or ((int)$index==0)) {
                  // echo $data->val($data->val($i,B))."<br/>";
                   unset($skus[(int)$index]);
                   unset($qtys[(int)$index]);
                   unset($prices[(int)$index]);
                }
	    }
            if($find_sku=='0') {
                $csv[$i - 2][] = "'Отключено'";                                //status
                $csv[$i - 2][] = "'Да'";                                  //new
            } else {
                $csv[$i - 2][] = "'Включено'";                                //status
                $csv[$i - 2][] = "'Нет'"; 
            }
        }

        //get old products to invisible
        foreach($skus as $key=>$value) {
            $i++;
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . $value . "'";                          //sku
            $csv[$i - 2][] = "'" . $prices[(int)$key] . "'";               //price
            $csv[$i - 2][] = "'" . $qtys[(int)$key] . "'";                //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'kids_attr'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Отключено'";                                //status
            $csv[$i - 2][] = "'Нет'";                                     //status 
        }

        $file = fopen($root . DS . 'var' . DS . 'import' . DS . 'update_kids.csv', 'w');
        foreach ($csv as $line) {
            fputcsv($file, $line, "~");
        }
        fclose($file);
        $data = file_get_contents($root . DS . 'var' . DS . 'import' . DS . 'update_kids.csv');
        $data = str_replace('"', "", $data);
        $data = str_replace("'", "\"", $data);
        file_put_contents($root . DS . 'var' . DS . 'import' . DS . 'update_kids.csv', $data);
        $url = Mage::getModel('adminhtml/url')->getUrl('adminhtml/system_convert_gui/run/id/12/files/update_kids.csv/');
        $this->_redirectUrl($url);
    }



  public function suvenirparseAction() {
	set_time_limit(0);
	ini_set('memory_limit','1024M');
	$skus=array();
        $qtys=array();
        $prices=array();
        $attrSetName = 'suvenir';
        $attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($attrSetName, 'attribute_set_name')->getAttributeSetId();
        $products = Mage::getModel('catalog/product')->getCollection()->joinField('qty','cataloginventory/stock_item', 
                                                                                        'qty', 
											'product_id=entity_id', 
											'{{table}}.stock_id=1', 
											'left')
                                                                                         ->addAttributeToSelect('price')
    										         ->addFieldToFilter('attribute_set_id', $attributeSetId);

        foreach($products as $p){
            $skus[] = $p->getData('sku');
            $qtys[] =$p->getQty();
            $prices[]= $p->getPrice();
        }


        $sku = '';
        $appRoot = Mage::getRoot();
        $root = dirname($appRoot);
        error_reporting(E_ALL ^ E_NOTICE);
        require_once $root . DS . 'lib' . DS . 'ExcelReader' . DS . 'excelreader.php';
        $data = new Spreadsheet_Excel_Reader();
        $data->read($_FILES['filesuvenir']['tmp_name']);
        $found = '0';
        $csv = array();
        $sku = array(); 
        for ($i = 2; $i < $data->rowcount()+1; $i++) {
            $price = str_replace(" ", "", $data->val($i, D));
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . trim($data->val($i, B)) . "'";         //sku
            $csv[$i - 2][] = "'" . str_replace(',', '.', $price) . "'";   //price
            $csv[$i - 2][] = "'" . trim($data->val($i, E)) . "'";         //qty
            $csv[$i - 2][] = "'Каталог, поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'suvenir'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
	    $find_sku = '0';
	    if (in_array($data->val($i, B), $skus)) {
    	        $find_sku='1';
                $index = array_search($data->val($i, B), $skus);
                if(($index!=false) or ((int)$index==0)) {
                  // echo $data->val($data->val($i,B))."<br/>";
                   unset($skus[(int)$index]);
                   unset($qtys[(int)$index]);
                   unset($prices[(int)$index]);
                }
	    }
            if($find_sku=='0') {
                $csv[$i - 2][] = "'Отключено'";                                //status
                $csv[$i - 2][] = "'Да'";                                  //new
            } else {
                $csv[$i - 2][] = "'Включено'";                                //status
                $csv[$i - 2][] = "'Нет'"; 
            }
        }

        //get old products to invisible
        foreach($skus as $key=>$value) {
            $i++;
            $csv[$i - 2][] = "''";                                        //articul
            $csv[$i - 2][] = "'" . $value . "'";                          //sku
            $csv[$i - 2][] = "'" . $prices[(int)$key] . "'";               //price
            $csv[$i - 2][] = "'" . $qtys[(int)$key] . "'";                //qty
            $csv[$i - 2][] = "'Каталог, Поиск'";                          //visibility
            $csv[$i - 2][] = "'simple'";                                  //type
            $csv[$i - 2][] = "'suvenir'";                                    //attribute_set
            $csv[$i - 2][] = "'base'";                                    //websites
            $csv[$i - 2][] = "'1'";                                       //is in stock
            $csv[$i - 2][] = "'Отключено'";                                //status
            $csv[$i - 2][] = "'Нет'";                                     //status 
        }
        $file = fopen($root . DS . 'var' . DS . 'import' . DS . 'update_suvenir.csv', 'w');
        foreach ($csv as $line) {
            fputcsv($file, $line, "~");
        }
        fclose($file);
        $data = file_get_contents($root . DS . 'var' . DS . 'import' . DS . 'update_suvenir.csv');
        $data = str_replace('"', "", $data);
        $data = str_replace("'", "\"", $data);
        file_put_contents($root . DS . 'var' . DS . 'import' . DS . 'update_suvenir.csv', $data);
        $url = Mage::getModel('adminhtml/url')->getUrl('adminhtml/system_convert_gui/run/id/12/files/update_suvenir.csv/');
        $this->_redirectUrl($url);
    }

    public function loadpricekancAction() {
	if(isset($_FILES['pricekanc']['name']) && $_FILES['pricekanc']['name'] != '')
	{
	    try
	    {
		$path = Mage::getBaseDir().DS.'media'.DS.'prices'.DS.'kanc';  //desitnation directory
		$fname = $_FILES['pricekanc']['name']; //file name
		$uploader = new Varien_File_Uploader('pricekanc'); //load class
		$uploader->setAllowedExtensions(array('xls')); //Allowed extension for file
		$uploader->setAllowCreateFolders(true); //for creating the directory if not exists
		$uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
		$uploader->setFilesDispersion(false);
		$uploader->save($path,'file.xls'); //save the file on the specified path
		$url = Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/success');
		$this->_redirectUrl($url);
	 
	    }
	    catch (Exception $e)
	    {
		echo 'Error Message: '.$e->getMessage();
	    }
        }
    }

    public function loadpricebooksAction() {
	if(isset($_FILES['pricebooks']['name']) && $_FILES['pricebooks']['name'] != '')
	{
	    try
	    {
		$path = Mage::getBaseDir().DS.'media'.DS.'prices'.DS.'books';  //desitnation directory
		$fname = $_FILES['pricebooks']['name']; //file name
		$uploader = new Varien_File_Uploader('pricebooks'); //load class
		$uploader->setAllowedExtensions(array('xls')); //Allowed extension for file
		$uploader->setAllowCreateFolders(true); //for creating the directory if not exists
		$uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
		$uploader->setFilesDispersion(false);
		$uploader->save($path,'file.xls'); //save the file on the specified path
		$url = Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/success');
		$this->_redirectUrl($url);
	 
	    }
	    catch (Exception $e)
	    {
		echo 'Error Message: '.$e->getMessage();
	    }
        }
    }

    public function loadpricesuvenirAction() {
	if(isset($_FILES['pricesuvenir']['name']) && $_FILES['pricesuvenir']['name'] != '')
	{
	    try
	    {
		$path = Mage::getBaseDir().DS.'media'.DS.'prices'.DS.'suvenir';  //desitnation directory
		$fname = $_FILES['pricesuvenir']['name']; //file name
		$uploader = new Varien_File_Uploader('pricesuvenir'); //load class
		$uploader->setAllowedExtensions(array('xls')); //Allowed extension for file
		$uploader->setAllowCreateFolders(true); //for creating the directory if not exists
		$uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
		$uploader->setFilesDispersion(false);
		$uploader->save($path,'file.xls'); //save the file on the specified path
		$url = Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/success');
		$this->_redirectUrl($url);
	 
	    }
	    catch (Exception $e)
	    {
		echo 'Error Message: '.$e->getMessage();
	    }
        }
    }

    public function loadpricekidsAction() {
	if(isset($_FILES['pricekids']['name']) && $_FILES['pricekids']['name'] != '')
	{
	    try
	    {
		$path = Mage::getBaseDir().DS.'media'.DS.'prices'.DS.'kids';  //desitnation directory
		$fname = $_FILES['pricekids']['name']; //file name
		$uploader = new Varien_File_Uploader('pricekids'); //load class
		$uploader->setAllowedExtensions(array('xls')); //Allowed extension for file
		$uploader->setAllowCreateFolders(true); //for creating the directory if not exists
		$uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
		$uploader->setFilesDispersion(false);
		$uploader->save($path,'file.xls'); //save the file on the specified path
		$url = Mage::getModel('adminhtml/url')->getUrl('web/adminhtml_web/success');
		$this->_redirectUrl($url);
	 
	    }
	    catch (Exception $e)
	    {
		echo 'Error Message: '.$e->getMessage();
	    }
        }
    }




































































    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('web/web')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('web_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('web/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('web/adminhtml_web_edit'))
                    ->_addLeft($this->getLayout()->createBlock('web/adminhtml_web_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('web')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('filename');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS;
                    $uploader->save($path, $_FILES['filename']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['filename'] = $_FILES['filename']['name'];
            }


            $model = Mage::getModel('web/web');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('web')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('web')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('web/web');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $webIds = $this->getRequest()->getParam('web');
        if (!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $web = Mage::getModel('web/web')->load($webId);
                    $web->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($webIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $webIds = $this->getRequest()->getParam('web');
        if (!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $web = Mage::getSingleton('web/web')
                            ->load($webId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($webIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


}
