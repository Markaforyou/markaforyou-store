<?php
class ControllerFeedGoogleSitemapAdv extends Controller {
   public function index() {
	  if ($this->config->get('google_sitemap_adv_status')) {
	  
	  	$pages = array();
	  	if($this->config->get('google_sitemap_adv_pages')){
	  		$pages = $this->config->get('google_sitemap_adv_pages');	
		}
		 $output  = '<?xml version="1.0" encoding="UTF-8"?>';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 $this->load->model('catalog/product');
		 
		 
		 if(in_array('prod',$pages)){
			 $products = $this->model_catalog_product->getProducts();
			 
			 foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</loc>';
				if($this->config->get('google_sitemap_freq_prod')){
					$output .= '<changefreq>'.$this->config->get('google_sitemap_freq_prod').'</changefreq>';
				}else{
					$output .= '<changefreq>weekly</changefreq>';
				}
				if($this->config->get('google_sitemap_pri_prod')){
					$output .= '<priority>'.$this->config->get('google_sitemap_pri_prod').'</priority>';
				}else{
					$output .= '<priority>1.0</priority>';
				}
				$output .= '</url>';   
			 }
		 
		 }
		 $this->load->model('catalog/category');
		 
		 if(in_array('cat',$pages)){
			 $categories = $this->model_catalog_category->getCategories();
			 $output .= $this->getCategories(0);
		 }
		 
		 $this->load->model('catalog/manufacturer');
		 
		 if(in_array('manu',$pages)){
		 
			 $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			 
			 foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer['manufacturer_id'])) . '</loc>';
				
				if($this->config->get('google_sitemap_freq_manu')){
					$output .= '<changefreq>'.$this->config->get('google_sitemap_freq_manu').'</changefreq>';
				}else{
					$output .= '<changefreq>weekly</changefreq>';
				}
				
				if($this->config->get('google_sitemap_pri_manu')){
					$output .= '<priority>'.$this->config->get('google_sitemap_pri_manu').'</priority>';
				}else{
					$output .= '<priority>0.7</priority>';
				}
				
				
				
				
				$output .= '</url>';   
				
				$products = $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));
				
				foreach ($products as $product) {
				   $output .= '<url>';
				   $output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id'])) . '</loc>';
				   
				   if($this->config->get('google_sitemap_freq_manu')){
					   $output .= '<changefreq>'.$this->config->get('google_sitemap_freq_manu').'</changefreq>';
				   }else{
					 $output .= '<changefreq>weekly</changefreq>';
				   }
				   if($this->config->get('google_sitemap_pri_manu')){
						$output .= '<priority>'.$this->config->get('google_sitemap_pri_manu').'</priority>';
					}else{
						$output .= '<priority>1.0</priority>';
					}
				   
				   $output .= '</url>';   
				}         
			 }
		 
		 }
		 
		 if(in_array('info',$pages)){
		 
			 $this->load->model('catalog/information');
			 
			 $informations = $this->model_catalog_information->getInformations();
			 
			 foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('information/information', 'information_id=' . $information['information_id'])) . '</loc>';
				if($this->config->get('google_sitemap_freq_info')){
					$output .= '<changefreq>'.$this->config->get('google_sitemap_freq_info').'</changefreq>';
				 }else{
					$output .= '<changefreq>weekly</changefreq>';
				 }
				
				if($this->config->get('google_sitemap_pri_info')){
					$output .= '<priority>'.$this->config->get('google_sitemap_pri_info').'</priority>';
				}else{
					$output .= '<priority>0.5</priority>';
				}
				$output .= '</url>';   
			 }
		}	 
			 $output .= '</urlset>';
		 
		 
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
   }
   
   protected function getCategories($parent_id, $current_path = '') {
	  $output = '';
	  
	  $results = $this->model_catalog_category->getCategories($parent_id);
	  
	  foreach ($results as $result) {
		 if (!$current_path) {
			$new_path = $result['category_id'];
		 } else {
			$new_path = $current_path . '_' . $result['category_id'];
		 }

		 $output .= '<url>';
		 $output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('product/category', 'path=' . $new_path)) . '</loc>';
		 
		 if($this->config->get('google_sitemap_freq_cat')){
			$output .= '<changefreq>'.$this->config->get('google_sitemap_freq_cat').'</changefreq>';
		 }else{
			$output .= '<changefreq>weekly</changefreq>';
		 }
		 
		 if($this->config->get('google_sitemap_pri_cat')){
			$output .= '<priority>'.$this->config->get('google_sitemap_pri_cat').'</priority>';
		}else{
			$output .= '<priority>0.7</priority>';
		}
		 
		 $output .= '</url>';         

		 $products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));
		 
		 foreach ($products as $product) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id'])) . '</loc>';
			
			
			if($this->config->get('google_sitemap_freq_cat')){
				$output .= '<changefreq>'.$this->config->get('google_sitemap_freq_cat').'</changefreq>';
			 }else{
				$output .= '<changefreq>weekly</changefreq>';
			 }
			
			if($this->config->get('google_sitemap_pri_cat')){
				$output .= '<priority>'.$this->config->get('google_sitemap_pri_cat').'</priority>';
			}else{
				$output .= '<priority>1.0</priority>';
			}
			$output .= '</url>';   
		 }   
		 
		   $output .= $this->getCategories($result['category_id'], $new_path);
	  }

	  return $output;
   }      
}
?>