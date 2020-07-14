<?php
class ControllerExtensionPaymentPayatrader extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('extension/payment/payatrader');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payatrader', $this->request->post);
            $this->load->model('extension/payment/payatrader');
			if($this->request->post['payatrader_status']) {
				$this->model_extension_payment_payatrader->install();
			} else {
				$this->model_extension_payment_payatrader->uninstall();
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['edit_title'] = $this->language->get('edit_title');
		$data['text_enabled'] 	= $this->language->get('text_enabled');
		$data['text_disabled'] 	= $this->language->get('text_disabled');
		$data['text_all_zones'] 	= $this->language->get('text_all_zones');
		$data['text_yes'] 		= $this->language->get('text_yes');
		$data['text_no'] 		= $this->language->get('text_no');
		
		
		$data['entry_site_code'] = $this->language->get('entry_site_code');
		$data['entry_posturl'] = $this->language->get('entry_posturl');
		$data['entry_traderdispalname'] = $this->language->get('entry_traderdispalname');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_email_alert']	=	$this->language->get('entry_email_alert');
		$data['entry_forceshow'] = $this->language->get('entry_forceshow');
		
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

  		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$data['error_site_code'] = $this->error['merchant'];
		} else {
			$data['error_site_code'] = '';
		}

 		if (isset($this->error['security'])) {
			$data['error_posturl'] = $this->error['security'];
		} else {
			$data['error_posturl'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/payatrader', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('extension/payment/payatrader', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		if (isset($this->request->post['payatrader_site_code'])) {
			$data['payatrader_site_code'] = $this->request->post['payatrader_site_code'];
		} else {
			$data['payatrader_site_code'] = $this->config->get('payatrader_site_code');
		}

		if (isset($this->request->post['payatrader_posturl'])) {
			$data['payatrader_posturl'] = $this->request->post['payatrader_posturl'];
		} else {
			$data['payatrader_posturl'] = $this->config->get('payatrader_posturl');
		}
		
		if (isset($this->request->post['payatrader_traderdispalname'])) {
			$data['payatrader_traderdispalname'] = $this->request->post['payatrader_traderdispalname'];
		} else {
			$data['payatrader_traderdispalname'] = $this->config->get('payatrader_traderdispalname');
		}
		
		if (isset($this->request->post['payatrader_email_alert'])) {
			$data['payatrader_email_alert'] = $this->request->post['payatrader_email_alert'];
		} else {
			$data['payatrader_email_alert'] = $this->config->get('payatrader_email_alert');
		}
		
		if (isset($this->request->post['payatrader_forceshow'])) {
			$data['payatrader_forceshow'] = $this->request->post['payatrader_forceshow'];
		} else {
			$data['payatrader_forceshow'] = $this->config->get('payatrader_forceshow'); 
		}
		
		if (isset($this->request->post['payatrader_total'])) {
			$data['payatrader_total'] = $this->request->post['payatrader_total'];
		} else {
			$data['payatrader_total'] = $this->config->get('payatrader_total'); 
		} 
				
		if (isset($this->request->post['payatrader_order_status_id'])) {
			$data['payatrader_order_status_id'] = $this->request->post['payatrader_order_status_id'];
		} else {
			$data['payatrader_order_status_id'] = $this->config->get('payatrader_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payatrader_geo_zone_id'])) {
			$data['payatrader_geo_zone_id'] = $this->request->post['payatrader_geo_zone_id'];
		} else {
			$data['payatrader_geo_zone_id'] = $this->config->get('payatrader_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payatrader_status'])) {
			$data['payatrader_status'] = $this->request->post['payatrader_status'];
		} else {
			$data['payatrader_status'] = $this->config->get('payatrader_status');
		}
		
		if (isset($this->request->post['payatrader_sort_order'])) {
			$data['payatrader_sort_order'] = $this->request->post['payatrader_sort_order'];
		} else {
			$data['payatrader_sort_order'] = $this->config->get('payatrader_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/payatrader', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/payatrader')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payatrader_site_code']) {
			$this->error['merchant'] = $this->language->get('error_site_code');
		}

		if (!$this->request->post['payatrader_posturl']) {
			$this->error['security'] = $this->language->get('error_posturl');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>