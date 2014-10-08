<?php
class ControllerPaymentCod extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cod.tpl';
		} else {
			$this->template = 'default/template/payment/cod.tpl';
		}	
		
		$this->render();
	}
	/**
	 * This function modified to retirive the order inofrmation in order to get the total value for COD limit
	 * @name Mohammad Abuelezz < aboel3zz at maktoob dot com>
	 */
	public function confirm() {

		$json = array();
		$error = '';
		try {
			$this -> load -> model('checkout/order');

			$order_id = $this -> session -> data['order_id'];
			// get the order information by order ID
			$order_info = $this->model_checkout_order -> getOrder($order_id);
			$cod_limit = $this->config->get('cod_limit');
			// Here is the code to increase the limit value for COD
			$query = $this -> db -> query("SELECT `value` FROM " . DB_PREFIX . "setting WHERE `key` = 'cod_current_value'");
			$cod_current_value = (int)$query -> row['value'];
			$cod_new_value = $cod_current_value + $order_info['total'];

			// make double check for the COD limit, for hacking purpose
			if ($cod_limit >= $cod_new_value) {
				$query = $this -> db -> query("UPDATE " . DB_PREFIX . "setting SET `value` = {$cod_new_value} WHERE `key` = 'cod_current_value'");
				
				// if notification set	
				$notification_level = $this->config-> get('cod_notification');				
				
				if(0 < $notification_level) {
					$current_cod_percentage = ($cod_new_value / $cod_limit) * 100;										
					if($current_cod_percentage >= $notification_level) {
						//Send a notification email to admin
						
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');						
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->config->get('config_name'));
						$mail->setSubject('إجمالي المبيعات منذ بداية 5 ميلادي');
						$mail->setText('إجمالي المبيعات منذ بداية شهر 5 ميلادي:' . $cod_new_value);		
						$mail->setTo($this->config->get('config_email'));
						// $mail->send();
						
						// Send to additional alert emails if new account email is enabled
						$emails = explode(',', $this->config->get('config_alert_emails'));
						
						foreach ($emails as $email) {
							if (strlen($email) > 0 && preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
								$mail->setTo($email);
								// $mail->send();
							}
						}																	
					} // else nothing
					
				}					
			} else {
				$json['error'] = 'Sorry your order limit is over the allowed COD limit';
			}
						
			// End of COD limit increase
			$this->model_checkout_order -> confirm($order_id, $this -> config -> get('cod_order_status_id'));

		} catch (Exception $e) {
			$json['error'] = $e -> getMessage();
		}

		$this -> load -> library('json');
		$this -> response -> setOutput(Json::encode($json));
		return;
	}

}
?>