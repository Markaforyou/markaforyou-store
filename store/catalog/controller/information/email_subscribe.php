<?php 
class Controllerinformationemailsubscribe extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('information/email_subscribe');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('account/email_subscribe');
		//$this->model_account_customer->addCustomer($data);
		
		if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['action'] = $this->url->link('information/email_subscribe', '', 'SSL');
		
		
		 
	    $this->data['SubscribeMes']  = 1;
		$this->data['subscribeTraffic'] = '';
		$data = array();
    	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			
				if (isset($this->request->post['EmailSP']) && $this->request->post['EmailSP'] != 'أدخل بريدك الالكتروني' && $this->request->post['EmailSP'] != '') {
    		$this->data['EmailSP'] = $this->request->post['EmailSP'];
		} 
		if ((utf8_strlen($this->request->post['EmailSP']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['EmailSP'])) {
      		$this->error['EmailSP'] = $this->language->get('error_email');
		}
		
		
		if (isset($this->request->post['country_id']) && $this->request->post['country_id'] != '') {
      		$this->data['country_id'] = $this->request->post['country_id'];
		}
		
		
		$this->data['checkemail']  = $this->model_account_email_subscribe->CheckEmail($this->data['EmailSP']);
		if($this->data['checkemail'] == 1){
			$this->data['subscribeTraffic'] = 'لقد اشتركت لدينا من قبل !!!';
			$this->data['SubscribeMes'] = 2;
		}else{
			$data['email'] = $this->data['EmailSP'];
			$data['country'] = $this->request->post['country_id'];
			$insert = $this->model_account_email_subscribe->InsertEmail($data);
			if($insert == 1){$this->data['subscribeTraffic'] = 'تم الاشتراك بنجاح';$this->data['SubscribeMes'] = 2;}
		}
		
		   
		
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/email_subscribe'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/email_subscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/email_subscribe.tpl';
		} else {
			$this->template = 'default/template/information/contact.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render());		
  	}

  	

}
?>
