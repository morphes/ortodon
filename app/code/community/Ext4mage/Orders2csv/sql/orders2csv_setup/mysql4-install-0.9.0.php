<?php

$installer = $this;

$installer->startSetup();

$installString = <<<EOT
DROP TABLE IF EXISTS {$this->getTable('orders2csv/file')};
CREATE TABLE {$this->getTable('orders2csv/file')} (
  `file_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL ,
  `is_active` TINYINT NOT NULL DEFAULT 1,
  `num_formatting` TINYINT NOT NULL DEFAULT 1,
  `creation_time` DATETIME NULL DEFAULT NULL ,
  `update_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`file_id`)
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = 'File structur for orders2cvs';

DROP TABLE IF EXISTS {$this->getTable('orders2csv/column')};
CREATE TABLE {$this->getTable('orders2csv/column')} (
  `column_id` INT NOT NULL AUTO_INCREMENT ,
  `file_id` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `sort_order` INT NOT NULL DEFAULT 0 ,
  `value` VARCHAR(255) NOT NULL ,
  `creation_time` DATETIME NULL DEFAULT NULL ,
  `update_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`column_id`) ,
  CONSTRAINT `FK_ORDERS2CVS_COLUMN_FILE` FOREIGN KEY (`file_id` ) REFERENCES `{$this->getTable('orders2csv/file')}` (`file_id` ) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = 'Single columns for the csv file in orders2csv';

INSERT INTO {$this->getTable('orders2csv/file')} (`file_id`, `title`, `is_active`, `num_formatting`, `creation_time`, `update_time`) VALUES
(1, 'DanishVatEUSale', 1, 2, NULL, NULL),
(2, 'BasicOrderInfo', 1, 3, NULL, NULL),
(3, 'BasicItemInfo', 1, 3, NULL, NULL);

INSERT INTO {$this->getTable('orders2csv/column')} (`column_id`, `file_id`, `title`, `sort_order`, `value`, `creation_time`, `update_time`) VALUES
(1, 1, 'Order amount services', 70, 'order_data_grand_total', NULL, NULL),
(2, 1, 'Order amount triangular trade', 60, 'order_data_grand_total', NULL, NULL),
(3, 1, 'Order amount goods', 50, 'order_data_grand_total', NULL, NULL),
(4, 1, 'Buyers Vat', 40, 'order_data_customer_taxvat', NULL, NULL),
(5, 1, 'Contry code', 30, 'order_billing_data_country_id', NULL, NULL),
(6, 1, 'Order date', 20, 'order_data_created_at', NULL, NULL),
(7, 1, 'Order id', 10, 'order_data_increment_id', NULL, NULL),
(8, 2, 'Order weight', 70, 'order_data_weight', NULL, NULL),
(9, 2, 'Billing email', 130, 'order_billing_data_email', NULL, NULL),
(10, 2, 'Billing telephone', 120, 'order_billing_data_telephone', NULL, NULL),
(11, 2, 'Billing zip', 110, 'order_billing_data_postcode', NULL, NULL),
(12, 2, 'Billing street', 100, 'order_billing_data_street', NULL, NULL),
(13, 2, 'Customer lastname', 90, 'order_data_customer_lastname', NULL, NULL),
(14, 2, 'Customer firstname', 80, 'order_data_customer_firstname', NULL, NULL),
(15, 2, 'Total amount', 60, 'order_data_grand_total', NULL, NULL),
(16, 2, 'Discount amount', 50, 'order_data_discount_amount', NULL, NULL),
(17, 2, 'Shipping amout', 40, 'order_data_shipping_amount', NULL, NULL),
(18, 2, 'Order amount', 30, 'order_data_subtotal', NULL, NULL),
(19, 2, 'Qty', 20, 'order_data_total_qty_ordered', NULL, NULL),
(20, 2, 'Order id', 10, 'order_data_increment_id', NULL, NULL),
(21, 3, 'Item option value', 100, 'item_option_data_value', NULL, NULL),
(22, 3, 'Item option label', 90, 'item_option_data_label', NULL, NULL),
(23, 3, 'Item discount amount', 80, 'item_data_discount_amount', NULL, NULL),
(24, 3, 'Item amount', 60, 'item_data_row_total', NULL, NULL),
(25, 3, 'Item tax amount', 70, 'item_data_tax_amount', NULL, NULL),
(26, 3, 'Order Id', 10, 'order_data_increment_id', NULL, NULL),
(27, 3, 'Item id', 20, 'item_data_product_id', NULL, NULL),
(28, 3, 'Item sku', 30, 'item_data_sku', NULL, NULL),
(29, 3, 'Item name', 40, 'item_data_name', NULL, NULL),
(30, 3, 'Item qty', 50, 'item_data_qty_ordered', NULL, NULL),
(31, 3, 'Item price', 60, 'item_data_price', NULL, NULL),
(32, 2, 'Tax amount', 35, 'order_data_tax_amount', NULL, NULL);

EOT;
$installer->run($installString);
$installer->endSetup();