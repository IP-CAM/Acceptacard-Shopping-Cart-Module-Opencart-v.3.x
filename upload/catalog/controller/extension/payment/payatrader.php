<?php
class ControllerExtensionPaymentPayatrader extends Controller {

	private $version = '1.1';
	public function index() {
		
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$data['action'] = $this->config->get('payatrader_posturl');

		$data['site_code'] 						= $this->config->get('payatrader_site_code');
		$data['site_url']  						= HTTPS_SERVER;
		$data['posturl']   						= $this->url->link('extension/payment/payatrader/shakehand','','SSL');
		$data['returnurl'] 						= HTTPS_SERVER."payatrader_success.php";
		$data['traderdisplayname'] 				= $this->config->get('payatrader_traderdispalname');
		$data['customer_name'] 					= $order_info['payment_firstname']. ' '. $order_info['payment_lastname'];
		$data['customer_email'] 					= $order_info['email'];
		$data['customer_telephone'] 				= $order_info['telephone'];
		$data['customer_postcode'] 				= $order_info['payment_postcode'];
		$data['customer_house_name_or_number'] 	= $order_info['payment_address_1'].' '.$order_info['payment_address_2'].' , '.$order_info['payment_city'].' , '.$order_info['payment_zone'].' , '.$order_info['payment_country'];
		$data['transaction_value_pence'] 			= number_format($this->currency->getValue('GBP') * $order_info['total'],2,'.','')*100;
		$data['order_number']						= $order_info['order_id'];
		$data['email_alert']						= $this->config->get('payatrader_email_alert');
		$data['module_version']						= $this->version;

		$data['oc_version'] = VERSION;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/payatrader')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/payatrader', $data);
		} else {
			return $this->load->view('default/template/extension/payment/payatrader', $data);
		}		
	}
	
	public function shakehand() {
		if (isset($_REQUEST['transaction_status'])) {
			$data = $_REQUEST;
			$response = print_r($_REQUEST,true);
			$this->log->write('ShakeHand Success Response for Payatrader:{{'.$response.'}}');	
			if($data && isset($data['transaction_status'])){	
				if(isset($data['order_number']))
					$order_id = $data['order_number'];

				$this->load->model('checkout/order');
				$this->load->model('extension/payment/payatrader');
				
				$order = $this->model_checkout_order->getOrder($order_id);
				$amount = $order['total'];
				
				$transaction = array();
				
				if(isset($data['transaction_value_pence']))
					$transaction['description'] = $data['transaction_value_pence'];
				
				if(isset($data['order_number']))
					$transaction['order_id'] = $data['order_number'];
					
				if(isset($data['date_time']))	
					$transaction['date_added'] = $data['date_time'];
				
				$transaction['customer_id'] = $order['customer_id'];
				$transaction['amount'] = $amount;

				if(isset($data['transaction_status']) && $data['transaction_status'] == 'A'){
					$status = $this->model_extension_payment_payatrader->addTransaction($transaction);
				}
			}
		} else {
			$response = print_r($_REQUEST,true);
			$this->log->write('ShakeHand Failure Response for Payatrader:{{'.$response.'}}');
		}
	}
	
	public function thanks() {
		if (($this->request->server['REQUEST_METHOD'] == 'GET') && isset($this->request->get['ono'])) {
			$ono =  $this->request->get['ono'];
			$this->load->model('extension/payment/payatrader');
			$this->load->model('checkout/order');
			$transaction = $this->model_extension_payment_payatrader->getTransaction($ono);
			/* print_r($transaction); */
			if(isset($transaction['customer_transaction_id']) && $transaction['customer_transaction_id']){
				$this->model_extension_payment_payatrader->deleteTransaction($transaction['customer_transaction_id']);
				$this->model_checkout_order->addOrderHistory($ono, $this->config->get('payatrader_order_status_id'));
				$response = print_r($this->request->get,true);
				$this->log->write('A Thanks Success Response for Payatrader:{{'.$response.'}}');
				$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));	
			}else{
				$response = print_r($this->request->get,true);
				$this->log->write('D Thanks Success Response for Payatrader:{{'.$response.'}}');
				$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));	
			}
		}
	}
}
?>