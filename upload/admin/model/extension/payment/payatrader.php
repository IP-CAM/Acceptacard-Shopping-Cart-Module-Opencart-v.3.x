<?php
class ModelExtensionPaymentPayatrader extends Model {

	protected $_searchTitle = 'Acceptacard Modification for Paya Card Services';
	
	public function install($version = '1.0.0') {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "payatrader_remote_order` (
			  `payatrader_remote_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_ref` CHAR(50) NOT NULL,
			  `order_ref_previous` CHAR(50) NOT NULL,
			  `pasref` VARCHAR(50) NOT NULL,
			  `pasref_previous` VARCHAR(50) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `capture_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `settle_type` INT(1) DEFAULT NULL,
			  `rebate_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `authcode` VARCHAR(30) NOT NULL,
			  `account` VARCHAR(30) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`payatrader_remote_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "payatrader_remote_order_transaction` (
			  `payatrader_remote_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `payatrader_remote_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'void') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`payatrader_remote_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		return true;
	}

//	public function uninstall($version = '1.0.0') {
//	    $this->db->query("DROP TABLE `" . DB_PREFIX . "payatrader_remote_order`");
//		$this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE `name` LIKE '%".$this->_searchTitle."%' AND `version` = '".$version."'");
//	}
}