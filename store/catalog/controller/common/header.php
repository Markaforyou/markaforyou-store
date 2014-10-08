<?php

class ControllerCommonHeader extends Controller {

    protected function index() {
        
        $this->data['title'] = $this->document->getTitle();
        
        $this->load->model('account/email_subscribe');
        $this->load->model('localisation/country');
        $this->language->load('subscripe/subscripe');
        
        $this->data['text_select']=$this->language->get('text_select');
        $this->data['text_enteremail'] = $this->language->get('text_enteremail');
        $this->data['text_txtsubscribe']=$this->language->get('text_txtsubscribe');
        $this->data['btn_subscribe']=$this->language->get('btn_subscribe');
        
        if (isset($this->request->post['country_id'])) {
            $this->data['country_id'] = $this->request->post['country_id'];
        } elseif (isset($this->session->data['shipping_country_id'])) {
            $this->data['country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $this->data['country_id'] = $this->config->get('config_country_id');
        }
        
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        $this->data['SubscribeMes']=0;
        $this->data['SubscribeError'] = 1;
        $this->data['subscribeTraffic'] = '';
        if ($this->request->server['REQUEST_METHOD'] == 'POST'){
            if (isset($this->request->post['EmalS']) && $this->request->post['EmalS'] != "أدخل بريدك الالكتروني" && $this->request->post['EmalS'] != "") {
                $this->data['EmalS'] = $this->request->post['EmalS'];
            }else{
                $this->data['EmalS'] = "";
            }
            //if ((utf8_strlen($this->request->post['EmalS']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['EmalS'])) {
                
            //    $this->error['EmalS'] = $this->language->get('error_email');
            //}
            
            if((utf8_strlen($this->data['EmalS']) > 96)){
                if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->data['EmalS'])){
                    $this->error['EmalS'] = $this->language->get('error_email');
                }
            }
            
            if (isset($this->request->post['country_id'])) {
                $this->data['country_id'] = $this->request->post['country_id'];
            } elseif (isset($this->session->data['shipping_country_id'])) {
                $this->data['country_id'] = $this->session->data['shipping_country_id'];
            } else {
                $this->data['country_id'] = $this->config->get('config_country_id');
            }


            $this->data['SubscribeMes'] = 1;
            $this->data['subscribeTraffic'] = '';
            $Subdata = array();
            //die(var_dump($this->data['EmalS']));
            $this->data['checkemail'] = $this->model_account_email_subscribe->CheckEmail($this->data['EmalS']);
            if ($this->data['checkemail'] == 1) {
                $this->data['subscribeTraffic'] = $this->language->get('already_subscribe');
                $this->data['SubscribeMes'] = 2;
                $this->request->post['newsletter']=1;
                $this->request->post['emailposted']=1;
            } else {
                $Subdata['email'] = $this->data['EmalS'];
                $Subdata['country'] = $this->request->post['country_id'];
                $insert = $this->model_account_email_subscribe->InsertEmail($Subdata);
                if ($insert == 1) {
                    $this->data['subscribeTraffic'] = $this->language->get('succefully_subscribe');
                    $this->data['SubscribeMes'] = 2;
                    $this->request->post['newsletter']=1;
                    $this->request->post['emailposted']=1;
                }
            }
            //Check if subscribe
            /* $SubscribeQuery = mysql_query("select * from Emails_Sebscribe where email_subscribe = '".$this->data['EmalS']."'");
              if(mysql_num_rows($SubscribeQuery) > 0){

              $this->data['SubscribeError'] = 2;
              $this->data['subscribeTraffic'] = 'لقد اشتركت لدينا من قبل !!!';
              }


              if($this->data['SubscribeError'] == 1){
              mysql_query("insert into Emails_Sebscribe (email_subscribe,country_id) values('".$this->data['EmalS']."','".$this->data['country_id']."')");
              $this->data['subscribeTraffic'] = 'تم الاشتراك بنجاح';
              $this->data['SubscribeError'] = 2;

              }

             */
        }
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->data['base'] = $server;
        $this->data['description'] = $this->document->getDescription();
        $this->data['keywords'] = $this->document->getKeywords();
        $this->data['links'] = $this->document->getLinks();
        $this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        $this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        $this->data['name'] = $this->config->get('config_name');


        if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
        } else {
            $this->data['icon'] = '';
        }

        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $this->data['logo'] = '';
        }

        $this->load->model('account/myaccount');
        $cust_code = $this->model_account_myaccount->getCustomerProductCode($this->customer->getId());
        
        $this->language->load('common/header');

        $this->data['text_home'] = $this->language->get('text_home');
        $this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
        $this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
        $this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
        if(strlen($cust_code)){
            $this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/myaccount', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
        }
        $this->data['text_account'] = $this->language->get('text_account');
        $this->data['text_checkout'] = $this->language->get('text_checkout');
        $this->data['text_contact'] = $this->language->get('text_contact');
        $this->data['text_aboutus_header'] = $this->language->get('text_aboutus_header');
        $this->data['text_woffers'] = $this->language->get('text_woffers');
        $this->data['text_eidoffers'] = $this->language->get('text_eidoffers');
        $this->data['text_clearance'] = $this->language->get('text_clearance');
        $this->data['clearance'] = $this->url->link('product/category', 'path=' . 120 );
        
        

        $this->data['home'] = $this->url->link('common/home');
        $this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $this->data['logged'] = $this->customer->isLogged();
        $this->data['account'] = $this->url->link('account/account', '', 'SSL');
        if(strlen($cust_code)){
            $this->data['account'] = $this->url->link('account/myaccount', '', 'SSL');
        }
        $this->data['shopping_cart'] = $this->url->link('checkout/cart');
        $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $this->data['contact'] = $this->url->link('information/contact', '', 'SSL');
        $this->data['aboutus_header'] = $this->url->link('i/4/about-us/', '', 'SSL');

        // Daniel's robot detector
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", trim($this->config->get('config_robots')));

            foreach ($robots as $robot) {
                if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
                    $status = false;

                    break;
                }
            }
        }

        // A dirty hack to try to set a cookie for the multi-store feature
        $this->load->model('setting/store');

        $this->data['stores'] = array();

        if ($this->config->get('config_shared') && $status) {
            $this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();

            $stores = $this->model_setting_store->getStores();

            foreach ($stores as $store) {
                $this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
            }
        }

        // Search		
        if (isset($this->request->get['search'])) {
            $this->data['search'] = $this->request->get['search'];
        } else {
            $this->data['search'] = '';
        }

        // Menu
        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        foreach ($categories as $category) {
            if($category['category_id']==120){
                $this->data['clearance'] = $this->url->link('product/category', 'path=' . $category['category_id'] );
            }
                
            if ($category['top']) {
                // Level 2
                $children_data = array();

                $children = $this->model_catalog_category->getCategories($category['category_id']);

                foreach ($children as $child) {
                    $data = array(
                        'filter_category_id' => $child['category_id'],
                        'filter_sub_category' => true
                    );

                    //$product_total = $this->model_catalog_product->getTotalProducts($data);

                    $children_data[] = array(
                        'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }

                // Level 1
                $this->data['categories'][] = array(
                    'name' => $category['name'],
                    'children' => $children_data,
                    'column' => $category['column'] ? $category['column'] : 1,
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }

        $this->children = array(
            'module/language',
            'module/currency',
            'module/cart'
        );

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {
            $this->template = 'default/template/common/header.tpl';
        }

        $this->render();
    }

}

?>
