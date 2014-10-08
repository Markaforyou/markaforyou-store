<?php

class ControllerCommonColumnRight extends Controller {

    protected function index() {
        $this->load->model('design/layout');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('catalog/information');
        $this->load->model('account/email_subscribe');
        $this->load->model('localisation/country');
        $this->language->load('subscripe/subscripe');

        if (isset($this->request->get['route'])) {
            $route = (string) $this->request->get['route'];
        } else {
            $route = 'common/home';
        }

        $layout_id = 0;

        if ($route == 'product/category' && isset($this->request->get['path'])) {
            $path = explode('_', (string) $this->request->get['path']);

            $layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
        }

        if ($route == 'product/product' && isset($this->request->get['product_id'])) {
            $layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
        }

        if ($route == 'information/information' && isset($this->request->get['information_id'])) {
            $layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
        }

        if (!$layout_id) {
            $layout_id = $this->model_design_layout->getLayout($route);
        }

        if (!$layout_id) {
            $layout_id = $this->config->get('config_layout_id');
        }

        $module_data = array();

        $this->load->model('setting/extension');

        $extensions = $this->model_setting_extension->getExtensions('module');

        foreach ($extensions as $extension) {
            $modules = $this->config->get($extension['code'] . '_module');

            if ($modules) {
                foreach ($modules as $module) {
                    if ($module['layout_id'] == $layout_id && $module['position'] == 'column_right' && $module['status']) {
                        $module_data[] = array(
                            'code' => $extension['code'],
                            'setting' => $module,
                            'sort_order' => $module['sort_order']
                        );
                    }
                }
            }
        }

        $this->data['text_select']=$this->language->get('text_select');
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
        /*if ($this->request->server['REQUEST_METHOD'] == 'POST'){

            if (isset($this->request->post['EmalS']) && $this->request->post['EmalS'] != 'أدخل بريدك الالكتروني' && $this->request->post['EmalS'] != '') {
                $this->data['EmalS'] = $this->request->post['EmalS'];
            }else{
                $this->data['EmalS'] = "";
            }
            //if ((utf8_strlen($this->request->post['EmalS']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['EmalS'])) {
                
            //    $this->error['EmalS'] = $this->language->get('error_email');
            //}
            if((utf8_strlen($this->data['EmalS']) > 96) || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->data['EmalS'])){
                $this->error['EmalS'] = $this->language->get('error_email');
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
            $this->data['checkemail'] = $this->model_account_email_subscribe->CheckEmail($this->data['EmalS']);
            if ($this->data['checkemail'] == 1) {
                $this->data['subscribeTraffic'] = 'لقد اشتركت لدينا من قبل !!!';
                $this->data['SubscribeMes'] = 2;
            } else {
                $Subdata['email'] = $this->data['EmalS'];
                $Subdata['country'] = $this->request->post['country_id'];
                $insert = $this->model_account_email_subscribe->InsertEmail($Subdata);
                if ($insert == 1) {
                    $this->data['subscribeTraffic'] = 'تم الاشتراك بنجاح';
                    $this->data['SubscribeMes'] = 2;
                }
            }*/
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

             \*\/
        }*/

        $sort_order = array();

        foreach ($module_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $module_data);

        $this->data['modules'] = array();

        foreach ($module_data as $module) {
            $module = $this->getChild('module/' . $module['code'], $module['setting']);

            if ($module) {
                $this->data['modules'][] = $module;
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_right.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/column_right.tpl';
        } else {
            $this->template = 'default/template/common/column_right.tpl';
        }

        $this->render();
    }

}

?>