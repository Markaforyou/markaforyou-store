<?php

class ControllerCommonUberSEO extends Controller {
	
	private $routes = array();
	private $aliases = array();
	private $loaded = false;
	private $lang = '';
	private $custom_aliases = array();
	
	public function index() {
		// Set language code
		$this->lang = $this->config->get('config_language');
		
		if($this->config->get('config_seo_url')) {
			if($this->config->get('uber_seo_alias_override')) {
				$result = $this->db->query("SELECT `query`, `keyword` FROM `" . DB_PREFIX . "url_alias` ORDER BY `query` DESC");
				if($result) {
					foreach($result->rows as $row) {
						list($key, $id) = explode('=', $row['query']);
						$this->custom_aliases[$key][$id] = $row['keyword'];
					}
				}
			}
		}
		
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		
		// Check URL has been rewritten
		if(!empty($this->request->get['_route_'])) {
			
			// Fix for language switcher
			if(!empty($this->request->post['redirect']) && !empty($this->request->post['language_code']) && $this->request->post['language_code'] != $this->lang) {
				$this->request->post['redirect'] = preg_replace('~/' . preg_quote($this->lang, '~') . '/~', '/' . $this->request->post['language_code'] . '/', $this->request->post['redirect']);				
			}
			
			// Get URL parts
			$u = explode('/', trim($this->request->get['_route_'], '/'));
			
			// Add language prefix if desired
			if($this->config->get('uber_seo_add_language')) {
				$lang = array_shift($u);
				if($lang !== NULL && $lang != $this->config->get('config_language') && strlen($lang) == 2 && empty($this->request->post['language_code'])) {
					if($this->languageExists($lang)) {
						$this->session->data['language'] = $lang;
						$this->redirect($this->config->get('config_url') . $this->request->get['_route_']);
					}
				}
			}
			
			if(count($u)) {
				
				// Switch for special URL cases
				switch($u[0]) {
					// Products page
					case 'p':
						if(!empty($u[1])) {
							$parts = explode(':', $u[1]);
							if($parts) {
								$product_id = (int) $parts[0];
								
								if($product_id) {
									if(!empty($parts[1]) && !empty($parts[2])) {
										if($parts[1] == 'c') {
											$this->request->get['path'] = $parts[2];
										}elseif($parts[1] == 'm') {
											$this->request->get['manufacturer_id'] = (int) $parts[2];
										}
									}
									$this->request->get['product_id'] = $product_id;
									$this->request->get['route'] = 'product/product';
								}
							}
						}
						break;
					// Categories page
					case 'c':
						if(!empty($u[1])) {
							$this->request->get['route'] = 'product/category';
							$this->request->get['path'] = $u[1];
						}
						break;
					// Manufacturers page
					case 'm':
						if(!empty($u[1])) {
							$this->request->get['route'] = 'product/manufacturer/product';
							$this->request->get['manufacturer_id'] = (int)$u[1];
						}
						break;
					// Information page
					case 'i':
						if(!empty($u[1])) {
							$this->request->get['route'] = 'information/information';
							$this->request->get['information_id'] = (int)$u[1];
						}
						break;
					
					// All other routes page
					default:
						if($this->config->get('uber_seo_nicer_routes')) {
							if(empty($this->request->get['route'])) {
								$this->request->get['route'] = 'common/home';
							}
							$this->request->get['route'] = rtrim(implode('/', $u), '/');
							
							$this->getAliases();
							$key = array_search($this->request->get['route'], $this->aliases);
							if($key !== false) {
								$this->request->get['route'] = $this->routes[$key];
							}
						}
						break;
				}
			}
			if(!empty($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			} elseif(count($u)) {
				return $this->forward('error/not_found');
			}
		}
		
		if(empty($this->request->get['route'])) {
			if($this->config->get('uber_seo_nicer_routes')) {
				$this->getAliases();
				$id = array_search('', $this->aliases);
				if($id !== false) {
					return $this->forward($this->routes[$id]);
				}
			}
		}
	}
	
	public function rewrite($link) {
		if(!$this->config->get('config_seo_url')) return $link;
		$url = false;

		$data = parse_url($link);
		parse_str(html_entity_decode($data['query'], ENT_QUOTES, 'UTF-8'), $query);
		
		$route = empty($query['route']) ? false : trim(strtolower($query['route']));
		$product_id = empty($query['product_id']) ? 0 : (int) $query['product_id'];
		
		$categories = array();
		if(!empty($query['path'])) {
			$this->load->model('catalog/category');
			
			$paths = explode('_', $query['path']);
			
			foreach($paths as &$path) {
				$path = (int) $path;
				if(!empty($this->custom_aliases['category_id'][$path])) {
					$categories[$path] = $this->custom_aliases['category_id'][$path];
				} else {
					$category = $this->model_catalog_category->getCategory($path);
					if($category) {
						$categories[$path] = $this->cleanName($category['name']);
					}
				}
			}
		}
		
		$manufacturer = false;
		if(!empty($query['manufacturer_id'])) {
			$mid = (int) $query['manufacturer_id'];
			if(!empty($this->custom_aliases['manufacturer_id'][$mid])) {
				$manufacturer = $this->custom_aliases['manufacturer_id'][$mid];
			} else {
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($mid);
				if($manufacturer) {
					$manufacturer = $this->cleanName($manufacturer['name']);
				}
			}
		}
		
		$path = implode('_', array_keys($categories));
		
		switch($route) {
			case 'product/product':
				$this->load->model('catalog/product');
				$product = $this->model_catalog_product->getProduct($product_id);
				if($product) {
					if(!empty($this->custom_aliases['product_id'][$product_id])) {
						$product_name = $this->custom_aliases['product_id'][$product_id];
					} else {
						$product_name = $this->cleanName($product['name']);
					}
					
					$url = '/p/' . $product_id;
					if($path) {
						$url .= ':c:' . $path;
						foreach($categories as $c) {
							$url .= '/' . $c;
						}
					}
					if($manufacturer) {
						$url .= ':m:' . $mid . '/' . $manufacturer;
					}
					
					$url .= '/' . $product_name . '/';
					unset($query['product_id']);
					unset($query['route']);
					unset($query['manufacturer_id']);
					unset($query['path']);
				}
				break;
			case 'product/category':
				if(!empty($categories)) {
					$url = '/c/' . $path;
					foreach($categories as $c) {
							$url .= '/' . $c;
					}
					$url .= '/';
					unset($query['route']);
					unset($query['path']);
				}
				break;
			case 'product/manufacturer/product':
				if($manufacturer) {
					$url = '/m/' . $mid . '/' . $manufacturer . '/';
						unset($query['route']);
						unset($query['manufacturer_id']);
				}
				break;
			case 'information/information':
				$info_id = empty($query['information_id']) ? 0 : (int)$query['information_id'];
				if($info_id) {
					if(!empty($this->custom_aliases['information_id'][$info_id])) {
						$name = $this->custom_aliases['information_id'][$info_id];
					} else {
						$this->load->model('catalog/information');
						$info = $this->model_catalog_information->getInformation($info_id);
						$name = $info ? $this->cleanName($info['title']) : false;
					}
					
					if($name) {
						$url = '/i/' . $info_id . '/' . $name . '/';
						unset($query['route']);
						unset($query['information_id']);
					}
				}
				break;
			default:
				if(empty($route)) $route = 'common/home';
				
				if($this->config->get('uber_seo_nicer_routes')) {
					$this->getAliases();
					$key = array_search($route, $this->routes);
					if($key !== false) {
						$url = '/' . trim($this->aliases[$key], '/');
					} else {
						$url = '/' . trim($route, '/');
					}
				
					if($url != '/') $url = rtrim($url, '/');
					
					unset($query['route']);
				}
				break;
		}
		
		if($url) {
			
			if(!empty($query)) {
				$url .= '?' . str_replace('%7Bpage%7D', '{page}', http_build_query($query));
			}
			if($this->config->get('uber_seo_add_language')) {
				$url = '/' . $this->lang  . $url;
			}
			$link = $data['scheme'] . '://' . $data['host'] . (empty($data['port']) ? '' : $data['port']) . preg_replace('~/index\.php$~', '', $data['path']) .  $url;
		}
		return $link;
	}
	
	private function cleanName($name) {
		$name = html_entity_decode($name);
		$name = strtolower(preg_replace('/[\s-]+/', '-', $name));
		$name = str_replace('&', 'and', $name);
		$name = preg_replace('/[^a-z0-9_-]/', '', $name);
		return $name;
	}
	
	private function getAliases() {
		if($this->loaded) return;

		$urls = explode("\n", trim($this->config->get('uber_seo_urls'), "\n "));
		if(empty($urls[0])) $urls = array();

		foreach($urls as &$url) {
			$url = explode('|', $url);
			if(!is_array($url) || count($url) !== 2){
				unset($url);
			} else {
				$this->routes[] = $url[0];
				$this->aliases[] = $url[1];
			}
			
		}
		
		$this->loaded = true;
	}
	
	private function languageExists($code) {
		$result = $this->db->query("SELECT `code` FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($code) . "' AND `status` = 1");
		return (bool) $result->num_rows;
	}
}