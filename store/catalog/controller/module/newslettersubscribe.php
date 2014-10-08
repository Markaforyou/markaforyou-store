<?php  
class ControllerModuleNewslettersubscribe extends Controller {
  	private $error = array();
	
	public function index($setting){

		if ( $this->config->get('newslettersubscribe_subscribemodule')==1) {
        $language_id=$this->config->get('config_language_id');
		
      	$this->data['heading_title'] = $this->config->get('newslettersubscribe_fheading_title_'.$language_id.'_line');
		      	
		$this->data['entry_name'] = $this->config->get('newslettersubscribe_fentry_name_'.$language_id.'_line');
      	$this->data['entry_email'] = $this->config->get('newslettersubscribe_fentry_email_'.$language_id.'_line');
	   	$this->data['entry_button'] = $this->config->get('newslettersubscribe_fentry_button_'.$language_id.'_line');
		$this->data['entry_unbutton'] = $this->config->get('newslettersubscribe_fentry_unbutton_'.$language_id.'_line');
		$this->data['subscribebutton'] = $this->config->get('newslettersubscribe_fentry_unbutton_'.$language_id.'_line');
		$this->data['popupline1'] = $this->config->get('newslettersubscribe_popupline1_'.$language_id.'_line');
		$this->data['popupline2'] = $this->config->get('newslettersubscribe_popupline2_'.$language_id.'_line');	
		$this->data['text_subscribe'] = $this->config->get('newslettersubscribe_ftext_subscribe_'.$language_id.'_line');
		
		
		
		$this->data['popupheaderimage'] = "image/".$this->config->get('newslettersubscribe_'.$language_id.'_popupheaderimage');	

      	$this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');		
		$this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');	
		for($l=1;$l<=6;$l++){
      			
	
	$this->data['option_fields_'][$l] = $this->config->get('newslettersubscribe_optionfield_'.$language_id.$l.'');


}	

$this->data['popupdisplay'] = $this->config->get('newslettersubscribe_popupdisplay');
$this->data['popupdelay'] = $this->config->get('newslettersubscribe_popupdelay');

$this->data['force'] = $this->config->get('newslettersubscribe_force');
		$this->id = 'newslettersubscribe';
		
		if ($setting['type']=='thickbox'){
	$this->data['home'] = $this->url->link('module/newslettersubscribe/subscribefancybox');
	$route = 'module/newslettersubscribe/subscribefancybox';	
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribethickbox.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribethickbox.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribethickbox.tpl';
		}}
		
	if ($setting['type']=='normal'){
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribe.tpl';
		}}
		
		if ($setting['type']=='popup'){	
	$this->data['home'] = $this->url->link('module/newslettersubscribe/subscribefancybox');
	$route = 'module/newslettersubscribe/subscribefancybox';			
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribepopup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribepopup.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribepopup.tpl';
		}}
		
		if ($setting['type']=='footer2'){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribefooter2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribefooter2.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribefooter2.tpl';
		}}
		
		if ($setting['type']=='footer'){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribefooter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribefooter.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribefooter.tpl';
		}}

		if ($setting['type']=='slideleft'){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribeslideleft.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribeslideleft.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribeslideleft.tpl';
		}}
		
		
		if ($setting['type']=='slideright'){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribeslideright.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribeslideright.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribeslideright.tpl';
		}}

		$this->render();

	   $this->load->model('account/newslettersubscribe');
	   //check db
	   $this->model_account_newslettersubscribe->check_db();
	   
	   
	   }
	}
	

	
	public function subscribe(){
	
	$language_id=$this->config->get('config_language_id');
	$this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
		
	 
	
			$prefix_eval = "";
		
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
	  $this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
		
		
		$this->load->model('account/newslettersubscribe');
		
	  		if ( $this->validate()) {
		
			 if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){

		   


	
			
				 $this->model_account_newslettersubscribe->subscribe($this->request->post);
	echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->data['subscribed'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');
	
			 
		   }else{
				echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->data['alreadyexist'].'</span>");$("'.$prefix_eval.' #subscribe")[0].reset();');	 
				
		  }
		   
	  }else{
	    	
			echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->error['warning'].'</span>")');
		}
		

	}
	
public function subscribefancybox(){

$language_id=$this->config->get('config_language_id');
	$this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
		
	 
	
			$prefix_eval = "";
		
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
	  $this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
		
		
		$this->load->model('account/newslettersubscribe');
		
	  		if ( $this->validate()) {
		
			if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){

	
				 $this->model_account_newslettersubscribe->subscribe($this->request->post);
$out = array('message' => $this->data['subscribed'], 'type' => 'success');
	
			 
		   }else{
				
				$out = array('message' => $this->data['alreadyexist'], 'type' => 'warning');
		  }
		   
	  }else{
	    	
			$out = array('message' => $this->error['warning'], 'type' => 'warning');
		}
		$this->response->addHeader('Content-type: application/json');
		$this->response->setOutput($out ? json_encode($out) : '');
		
	}
public function subscribefooter(){
	 $language_id=$this->config->get('config_language_id');
	$this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
		
	 
	
			$prefix_eval = "";
		
	  $this->data['subscribed'] = $this->config->get('newslettersubscribe_fsubscribe_'.$language_id.'_line');
	  $this->data['alreadyexist'] = $this->config->get('newslettersubscribe_falreadyexist_'.$language_id.'_line');
		
		
		$this->load->model('account/newslettersubscribe');
		
	  		if ( $this->validate()) {
		
			 if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){

		   
				 $this->model_account_newslettersubscribe->subscribe($this->request->post);
	echo('$("'.$prefix_eval.' #subscribef_result").html("'.$this->data['subscribed'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');
	
			 
		   }else{
				echo('$("'.$prefix_eval.' #subscribef_result").html("<span class=\"error\">'.$this->data['alreadyexist'].'</span>");$("'.$prefix_eval.' #subscribe")[0].reset();');	 
				
		  }
		   
	  }else{
	    	
			echo('$("'.$prefix_eval.' #subscribef_result").html("<span class=\"error\">'.$this->error['warning'].'</span>")');
		}
		

	}
		public function unsubscribe(){
	  	 $language_id=$this->config->get('config_language_id');
	   
	$this->data['unsubscribed'] = $this->config->get('newslettersubscribe_funsubscribe_'.$language_id.'_line');
	  $this->data['notexist'] = $this->config->get('newslettersubscribe_fnotexist_'.$language_id.'_line');
		$this->data['error_invalid'] = $this->config->get('newslettersubscribe_ferror_invalid_'.$language_id.'_line');
		
	      $prefix_eval = "";
	 

	  
	  $this->load->model('account/newslettersubscribe');
	  
	  if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
            
		   
			   
			     if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
		     echo('$("'.$prefix_eval.' #subscribe_result").html("'. $this->data['notexist'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
		   }else{

				 $this->model_account_newslettersubscribe->unsubscribe($this->request->post);
				 echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->data['unsubscribed'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');

			
			  
		   }
		   
	  }else{
	    echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->data['error_invalid'].'</span>")');
	  }
	}
	
	
		public function unsubscribefooter(){
 $language_id=$this->config->get('config_language_id');
	   
	$this->data['unsubscribed'] = $this->config->get('newslettersubscribe_funsubscribe_'.$language_id.'_line');
	  $this->data['notexist'] = $this->config->get('newslettersubscribe_fnotexist_'.$language_id.'_line');
		$this->data['error_invalid'] = $this->config->get('newslettersubscribe_ferror_invalid_'.$language_id.'_line');
		
	      $prefix_eval = "";
	
	  $this->load->model('account/newslettersubscribe');
	  
	  if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
            
		   
			   
			     if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
		     echo('$("'.$prefix_eval.' #subscribef_result").html("'. $this->data['notexist'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
		   }else{
			   
			  
				 $this->model_account_newslettersubscribe->unsubscribe($this->request->post);
				 echo('$("'.$prefix_eval.' #subscribef_result").html("'.$this->data['unsubscribed'].'");$("'.$prefix_eval.' #subscribe")[0].reset();');

			  
		   }
		   
	  }else{
	    echo('$("'.$prefix_eval.' #subscribef_result").html("<span class=\"error\">'.$this->data['error_invalid'].'</span>")');
	  }
	}
	
	private function validate() {

     $language_id=$this->config->get('config_language_id');
	 $error_invalid= $this->config->get('newslettersubscribe_ferror_invalid_'.$language_id.'_line');
	  $error_name= $this->config->get('newslettersubscribe_fnameinvalid_'.$language_id.'_line');
	   $requiredoption= $this->config->get('newslettersubscribe_foptioninvalid_'.$language_id.'_line');
		



if   ($this->config->get('newslettersubscribe_option_field')>=1) {
for($l=1;$l<=$this->config->get('newslettersubscribe_option_field');$l++){
      			
	
	if ((utf8_strlen($this->request->post['option'.$l.'']) < 1) || (utf8_strlen($this->request->post['option'.$l.'']) > 32)) {

      		$this->error['warning'] = sprintf($requiredoption, $this->config->get('newslettersubscribe_optionfield_'.$language_id.$l.''));
	

}	
}
}
	
 if ((utf8_strlen($this->request->post['subscribe_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['subscribe_email'])) {
      		$this->error['warning'] = $error_invalid;
    	}

    	
    	if ((utf8_strlen($this->request->post['subscribe_name']) < 1) || (utf8_strlen($this->request->post['subscribe_name']) > 32)) {
      		$this->error['warning'] = $error_name;
    	}

	
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}


}
?>