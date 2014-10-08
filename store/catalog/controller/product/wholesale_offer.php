<?php 
class ControllerProductWholesaleOffer extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('product/wholesale_offer');
        $this->load->model('catalog/wholesale_offer');	
    	$this->document->setTitle($this->language->get('heading_title')); 
		
		
		//$this->load->model('account/email_subscribe');
	   $this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');			
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_continue'] = $this->language->get('button_continue');
	
		$this->load->model('catalog/review');

		$this->data['tab_description'] = $this->language->get('tab_description');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
		$this->data['tab_related'] = $this->language->get('tab_related');
		//$this->data['action'] = $this->url->link('information/email_subscribe', '', 'SSL');
		
		$this->data['product_id'] = $this->request->get['product_id'];
		$this->data['manufacturer'] = $product_info['manufacturer'];
		$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
		$this->data['model'] = $product_info['model'];
		$this->data['reward'] = $product_info['reward'];
		$this->data['points'] = $product_info['points'];
		
		
		
		if ($product_info['quantity'] <= 0) {
			$this->data['stock'] = $product_info['stock_status'];
		} elseif ($this->config->get('config_stock_display')) {
			$this->data['stock'] = $product_info['quantity'];
		} else {
			$this->data['stock'] = $this->language->get('text_instock');
		}
		
		$this->load->model('tool/image');
		
		if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}
			
			if ($product_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}
			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_wholesale_offer->getProductImages($this->request->get['product_id']);
			
			/*foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}	
			*/
			
			
			
			
		 


      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/wholesale_offer'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/wholesale_offer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/wholesale_offer.tpl';
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
