<?php 
class ModelExtensionPaymentPayatrader extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/payatrader');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payatrader_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payatrader_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payatrader_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		if($status){
			if($this->currency->getCode()!= 'GBP' && !$this->config->get('payatrader_forceshow'))
				$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'payatrader',
        		'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payatrader_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	public function addTransaction($data=array()){
		if(count($data)>0){
			$sql = "INSERT INTO `" . DB_PREFIX . "customer_transaction` SET ";
			foreach($data as $key=>$value){
				if($value){
					$sql .= " `".$key."`='".$this->db->escape($value)."',";
				}
			}
			
			$sql = rtrim($sql,',');
			$query = $this->db->query($sql);
			if($query)
				return true;
			else
				return false;
		}
		return true;
	}
	
	public function getTransaction($order_id=0){
		if($order_id){
			$sql = "SELECT * FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id`='".$order_id."' ORDER BY `date_added` DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query)
				return $query->row;
			else
				return false;
		}
		return false;
	}
	
	public function deleteTransaction($customer_transaction_id=0){
		if($customer_transaction_id){
			$sql = "DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_transaction_id`='".$customer_transaction_id."'";
			$query = $this->db->query($sql);
			if($query)
				return true;
			else
				return false;
		}
		return false;
	}
}
?>