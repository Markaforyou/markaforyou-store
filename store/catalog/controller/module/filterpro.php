<?php

class ControllerModuleFilterPro extends Controller {

    protected function index($setting) {
        //die($setting['type']);
        if ($setting['type'] == 1) {
            $this->language->load('product/filter');
            $this->data['text_display'] = $this->language->get('text_display');
            $this->data['text_list'] = $this->language->get('text_list');
            $this->data['text_grid'] = $this->language->get('text_grid');
            $this->data['text_sort'] = $this->language->get('text_sort');
            $this->data['text_limit'] = $this->language->get('text_limit');

            $sort = 'p.sort_order';
            $order = 'ASC';
            $limit = $this->config->get('config_catalog_limit');

            $url = '';

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $this->data['sorts'] = array();

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('product/filter', 'sort=p.sort_order&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('product/filter', 'sort=pd.name&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('product/filter', 'sort=pd.name&order=DESC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('product/filter', 'sort=p.price&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('product/filter', 'sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href' => $this->url->link('product/filter', 'sort=rating&order=DESC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href' => $this->url->link('product/filter', 'sort=rating&order=ASC' . $url)
                );
            }

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('product/filter', 'sort=p.model&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('product/filter', 'sort=p.model&order=DESC' . $url)
            );

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= 'sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->data['limits'] = array();

            $this->data['limits'][] = array(
                'text' => $this->config->get('config_catalog_limit'),
                'value' => $this->config->get('config_catalog_limit'),
                'href' => $this->url->link('product/filter', $url . '&limit=' . $this->config->get('config_catalog_limit'))
            );

            $this->data['limits'][] = array(
                'text' => 25,
                'value' => 25,
                'href' => $this->url->link('product/filter', $url . '&limit=25')
            );

            $this->data['limits'][] = array(
                'text' => 50,
                'value' => 50,
                'href' => $this->url->link('product/filter', $url . '&limit=50')
            );

            $this->data['limits'][] = array(
                'text' => 75,
                'value' => 75,
                'href' => $this->url->link('product/filter', $url . '&limit=75')
            );

            $this->data['limits'][] = array(
                'text' => 100,
                'value' => 100,
                'href' => $this->url->link('product/filter', $url . '&limit=100')
            );


            $this->data['sort'] = $sort;
            $this->data['order'] = $order;
            $this->data['limit'] = $limit;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro_container.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/filterpro_container.tpl';
            } else {
                $this->template = 'default/template/module/filterpro_container.tpl';
            }
        } else {
            $this->language->load('module/filterpro');

            $this->data['text_tax'] = $this->language->get('text_tax');
            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_wishlist'] = $this->language->get('button_wishlist');
            $this->data['button_compare'] = $this->language->get('button_compare');
            $this->data['text_price_range'] = $this->language->get('text_price_range');
            $this->data['text_manufacturers'] = $this->language->get('text_manufacturers');
            $this->data['text_attributes'] = $this->language->get('text_attributes');
            $this->data['text_all'] = $this->language->get('text_all');
            $this->data['clear_filter'] = $this->language->get('clear_filter');

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['pds_sku'] = $this->language->get('pds_sku');
            $this->data['pds_upc'] = $this->language->get('pds_upc');
            $this->data['pds_location'] = $this->language->get('pds_location');
            $this->data['pds_model'] = $this->language->get('pds_model');
            $this->data['pds_brand'] = $this->language->get('pds_brand');
            $this->data['pds_stock'] = $this->language->get('pds_stock');
            $this->data['symbol_right'] = $this->currency->getSymbolRight();
            $this->data['symbol_left'] = $this->currency->getSymbolLeft();

            $this->data['setting'] = $setting;

            if (VERSION == '1.5.0') {
                $filterpro_setting = unserialize($this->config->get('filterpro_setting'));
            } else {
                $filterpro_setting = $this->config->get('filterpro_setting');
            }

            $category_id = 0;
												$category_main_id = 0;
            $this->data['path'] = "";
            if (isset($this->request->get['path'])) {
                $this->data['path'] = $this->request->get['path'];
                $parts = explode('_', (string) $this->request->get['path']);
                $category_main_id = $parts[0];
                $category_id = array_pop($parts);
            }

            $data = array(
                'filter_category_id' => $category_id,
                'filter_sub_category' => false
            );

            $this->load->model('catalog/category');
            $product_total = $this->model_catalog_product->getTotalProducts($data);

            if (!$product_total) {
                return;
            }

            $this->data['category_id'] = $category_id;
            $this->data['category_main_id'] = $category_main_id;

            $this->load->model('module/filterpro');

            $this->data['manufacturers'] = false;
            if ($filterpro_setting['display_manufacturer'] != 'none') {
                $this->data['manufacturers'] = $this->model_module_filterpro->getManufacturersByCategoryId($category_id);
                $this->data['display_manufacturer'] = $filterpro_setting['display_manufacturer'];
            }

            $this->data['options'] = $this->model_module_filterpro->getOptionsByCategoryId($category_id);
            //echo "<pre>";
            //die(var_dump($this->data['options']));
												
            foreach ($this->data['options'] as $i => $option) {
																		if (!isset($filterpro_setting['display_option_' . $option['option_id']])) {
																								$filterpro_setting['display_option_' . $option['option_id']] = 'none';
																		}
																		$display_option = $filterpro_setting['display_option_' . $option['option_id']];
																		if ($display_option != 'none') {
																								$this->data['options'][$i]['display'] = $display_option;
																		} else {
																								unset($this->data['options'][$i]);
																		}
            }
												
												$this->data['attributes'] = array();
            /*
												 * $this->data['attributes'] = $this->model_module_filterpro->getAttributesByCategoryId($category_id);
            foreach ($this->data['attributes'] as $j => $attribute_group) {
                foreach ($attribute_group['attribute_values'] as $attribute_id => $attribute) {
                    if (!isset($filterpro_setting['display_attribute_' . $attribute_id])) {
                        $filterpro_setting['display_attribute_' . $attribute_id] = 'none';
                    }
                    $display_attribute = $filterpro_setting['display_attribute_' . $attribute_id];
                    if ($display_attribute != 'none') {
                        if ($display_attribute == 'slider') {
                            $values = $this->data['attributes'][$j]['attribute_values'][$attribute_id]['values'];
                            $first = $values[0]; */
                            // $this->data['attributes'][$j]['attribute_values'][$attribute_id]['suffix'] = preg_replace("/^[0-9]*/", '', $first);
/*
                            $values = array_map('intVal', $values);
                            $values = array_unique($values);
                            sort($values);
                            //if ($values[0] > 0) {
                            //array_unshift($values, 0);
                            //}
                            $this->data['attributes'][$j]['attribute_values'][$attribute_id]['values'] = $values;
                        }
                        $this->data['attributes'][$j]['attribute_values'][$attribute_id]['display'] = $display_attribute;
                    } else {
                        unset($this->data['attributes'][$j]['attribute_values'][$attribute_id]);
                        if (!$this->data['attributes'][$j]['attribute_values']) {
                            unset($this->data['attributes'][$j]);
                        }
                    }
                }
            }
 * */
            $this->data['price_slider'] = $filterpro_setting['price_slider'];
            if ($this->data['options'] || $this->data['manufacturers'] || $this->data['attributes'] || $this->data['price_slider']) {
                $this->document->addScript('catalog/view/javascript/jquery/jquery.tmpl.min.js');
                $this->document->addScript('catalog/view/javascript/jquery/jquery.deserialize.js');
                $this->document->addScript('catalog/view/javascript/filterpro.min.js');
                $this->document->addStyle('catalog/view/theme/shoppica2/stylesheet/filterpro.css');
                $this->document->addStyle('catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');
												}
												
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/filterpro.tpl';
            } else {
                $this->template = 'default/template/module/filterpro.tpl';
            }
        }
        $this->render();
    }

    private function array_clean(array $haystack) {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->array_clean($value);
                if (!count($haystack[$key])) {
                    unset($haystack[$key]);
                }
            } elseif (is_string($value)) {
                $value = trim($value);
                if (!$value) {
                    unset($haystack[$key]);
                }
            }
        }
        return $haystack;
    }

    public function getProducts() {
        //		$cache = md5(http_build_query($this->request->post));
        //		$json = $this->cache->get('filterpro.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $cache);
        //		if(!$json) {

        $this->language->load('module/filterpro');

        if (VERSION == '1.5.0') {
            $filterpro_setting = unserialize($this->config->get('filterpro_setting'));
        } else {
            $filterpro_setting = $this->config->get('filterpro_setting');
        }


        $page = 1;
        if (isset($this->request->post['page'])) {
            $page = (int) $this->request->post['page'];
        }

        if (isset($this->request->post['sort'])) {
            $sort = $this->request->post['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->post['order'])) {
            $order = $this->request->post['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->post['limit'])) {
            $limit = $this->request->post['limit'];
        } else {
            $limit = $this->config->get('config_catalog_limit');
        }


        $this->load->model('module/filterpro');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $manufacturer = false;
        if (isset($this->request->post['manufacturer'])) {
            $manufacturer = $this->array_clean($this->request->post['manufacturer']);
            if (!count($manufacturer)) {
                $manufacturer = false;
            }
        }

        $option_value = false;
        if (isset($this->request->post['option_value'])) {
            $option_value = $this->array_clean($this->request->post['option_value']);
            if (!count($option_value)) {
                $option_value = false;
            }
        }

        $attribute_value = false;
        if (isset($this->request->post['attribute_value'])) {
            $attribute_value = $this->array_clean($this->request->post['attribute_value']);
            if (!count($attribute_value)) {
                $attribute_value = false;
            }
        }

        $data = array(
            'option_value' => $option_value,
            'manufacturer' => $manufacturer,
            'attribute_value' => $attribute_value,
            'attr_slider' => isset($this->request->post['attr_slider']) ? $this->request->post['attr_slider'] : false,
            'category_id' => $this->request->post['category_id'],
            'min_price' => $this->request->post['min_price'],
            'max_price' => $this->request->post['max_price'],
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order
        );

        $product_total = $this->model_module_filterpro->getTotalProducts($data);

        $totals_manufacturers = $this->model_module_filterpro->getTotalManufacturers($data);

        $totals_options = $this->model_module_filterpro->getTotalOptions($data);

        $totals_attributes = $this->model_module_filterpro->getTotalAttributes($data);

        $products = $this->model_module_filterpro->getProducts($data);
        //echo "<pre>";
        //die(var_dump($products));
        //die(var_dump($data));

        $result = array();

        $min_price = false;
        $max_price = false;

        if (isset($this->request->post['getPriceLimits']) && $this->request->post['getPriceLimits']) {
            $priceLimits = $this->model_module_filterpro->getPriceLimits($data);
            $min_price = $priceLimits['min_price'];
            $max_price = $priceLimits['max_price'];
        }

        foreach ($products as $key => $product) {

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = false;
            }
            $soldout = $this->model_tool_image->resize('sold_out.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
            $last_img = "";
            if ($product['quantity'] == 1) {
                $last_img = $this->model_tool_image->resize('last_one.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
            }
            /* elseif($product['quantity']==2){
              $last_img = $this->model_tool_image->resize('last_tow.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
              }elseif($product['quantity']==3){
              $last_img = $this->model_tool_image->resize('last_three.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
              } */ elseif ($product['quantity'] < 4 && $product['quantity'] > 1) {
                $last_img = $this->model_tool_image->resize('last_qty.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
            }


            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if ((float) $product['nl_price'] && (float) $product['special']) {
                if ((float) $product['nl_price'] < (float) $product['special']) {
                    $product['special'] = $product['nl_price'];
                }
            } elseif ((float) $product['nl_price']) {
                $product['special'] = $product['nl_price'];
            }

            if ((float) $product['special']) {
                $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $product['special'] ? $product['special'] : $product['price']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int) $product['rating'];
            } else {
                $rating = false;
            }

            $description = function_exists('utf8_substr') ? utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..' :
                    substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..';

            if ($product['quantity'] <= 0) {
                $rstock = $product['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $rstock = $product['quantity'];
            } else {
                $rstock = $this->language->get('text_instock');
            }

            $result[] = array(
                'product_id' => $product['product_id'],
                'sku' => $filterpro_setting['sku_display'] ? $product['sku'] : false,
                'model' => $filterpro_setting['model_display'] ? $product['model'] : false,
                'brand' => $filterpro_setting['brand_display'] ? $product['manufacturer'] : false,
                'location' => $filterpro_setting['location_display'] ? $product['location'] : false,
                'upc' => $filterpro_setting['upc_display'] ? $product['upc'] : false,
                'stock' => $filterpro_setting['stock_display'] ? $rstock : false,
                'quantity' => $product['quantity'],
                'clearance' => $product['clearance'],
                'eid_offer' => $product['eid_offer'],
                'image' => $image,
                'last_img' => $last_img,
                'thumb' => $image,
                'soldout' => $soldout,
                'special' => $special,
                'tax' => $tax,
                'rating' => $rating,
                'name' => $product['name'],
                'description' => $description,
                'price' => $price,
                'href' => $this->url->link('product/product', (isset($this->request->post['path']) ? 'path=' . $this->request->post['path'] : '') . '&product_id=' . $product['product_id'])
            );
        }
        //die(var_dump($result));
        $pagination = new Pagination();
        $pagination->total = $product_total; //count($result);//$product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = 'page={page}';

        $min_price = $this->currency->convert($min_price, $this->config->get('config_currency'), $this->currency->getCode());
        $max_price = $this->currency->convert($max_price, $this->config->get('config_currency'), $this->currency->getCode());
        $json = json_encode(array('result' => $result, 'min_price' => $min_price, 'max_price' => $max_price, 'pagination' => $pagination->render(),
            'totals_data' => array('manufacturers' => $totals_manufacturers, 'options' => $totals_options, 'attributes' => $totals_attributes)));
        //			$this->cache->set('filterpro.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $cache, $json);
        //		}

        $this->response->setOutput($json);
    }

}

?>