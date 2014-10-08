<?php  
class ControllerModulecallme extends Controller {
	protected function index() {
		$this->language->load('module/callme');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_name'] = $this->language->get('text_name');
    	$this->data['text_phone'] = $this->language->get('text_phone');
		$this->data['text_callme'] = $this->language->get('text_callme');
		$this->data['text_success'] = $this->language->get('text_success');		
		$this->data['text_errorName'] = $this->language->get('text_errorName');		
		$this->data['text_errorPhone'] = $this->language->get('text_errorPhone');				

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/callme.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/callme.tpl';
		} else {
			$this->template = 'default/template/module/callme.tpl';
		}
		
		$this->render();
	}
	
	public function send(){
		$this->language->load('module/callme');
		$to = "aurelien.signe@gmail.com";//$this->config->get('config_alert_emails');
		$from = "aurelien.signe@gmail.com";//$this->config->get('config_email');
		$subject = $this->language->get('text_subject');
		$message = $this->language->get('text_message')."\n\n";		
		$message .= $this->language->get('text_name')." ".$_POST['name']."\n\n";
		$message .= $this->language->get('text_phone')." ".$_POST['phone'];
		$mailAdmin = new Mail();
		$mailAdmin->protocol = $this->config->get('config_mail_protocol');
		$mailAdmin->parameter = $this->config->get('config_mail_parameter');
		$mailAdmin->hostname = $this->config->get('config_smtp_host');
		$mailAdmin->username = $this->config->get('config_smtp_username');
		$mailAdmin->password = $this->config->get('config_smtp_password');
		$mailAdmin->port = $this->config->get('config_smtp_port');
		$mailAdmin->timeout = $this->config->get('config_smtp_timeout');				
		$mailAdmin->setTo($this->config->get('config_alert_emails'));
		$mailAdmin->setFrom($this->config->get('config_email'));
		$mailAdmin->setSender($this->config->get('config_name'));
		$mailAdmin->setSubject($this->language->get('text_subject'));
		$mailAdmin->setText($message);
				
		if($mailAdmin->send()){
			echo "success";
		}
		else{
			echo "error";
		}
		exit();
	}
}
?>