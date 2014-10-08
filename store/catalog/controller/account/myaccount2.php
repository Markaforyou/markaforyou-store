<?php

class ControllerAccountMyaccount2 extends Controller {

    public function index() {
        
        if (!$this->customer->isLogged()) {
            
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }else{
            $cust_id = $this->customer->getId();
        }
        if(!$cust_id){
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/myaccount');
        $this->load->model('account/myaccount2');
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $cust_code = $this->model_account_myaccount2->getCustomerProductCode($this->customer->getId());
        $this->data['cust_code']=$cust_code;
        $this->data['orders'] = array();
        if(!$customer_info || $customer_info['customer_group_id']!=6 || !$cust_code){
            $this->redirect($this->url->link('account/account', '', 'SSL'));
        }
        
        $this->document->setTitle($this->language->get('heading_title'));
                
        //$this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['heading_title'] = $customer_info['firstname'].' '.$customer_info['lastname'];

        $this->data['column_title'] = $this->language->get('column_title');
        $this->data['column_count'] = $this->language->get('column_count');
        $this->data['column_per'] = $this->language->get('column_per');
        $this->data['column_sales'] = $this->language->get('column_sales');
        $this->data['column_process'] = $this->language->get('column_process');
        $this->data['column_stillwait'] = $this->language->get('column_stillwait');
        $this->data['column_cancel'] = $this->language->get('column_cancel');
        $this->data['column_return'] = $this->language->get('column_return');
        $this->data['column_total_order'] = $this->language->get('column_total_order');
        $this->data['text_update'] = $this->language->get('text_update');
        $this->data['text_contact'] = $this->language->get('text_contact');

        $this->data['text_overview'] = $this->language->get('text_overview');
        $this->data['text_statistics'] = $this->language->get('text_statistics');
        $this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
        $this->data['text_total_sale'] = $this->language->get('text_total_sale');
        $this->data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
        $this->data['text_total_order'] = $this->language->get('text_total_order');
        $this->data['text_total_customer'] = $this->language->get('text_total_customer');
        $this->data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
        $this->data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
        $this->data['text_total_affiliate'] = $this->language->get('text_total_affiliate');
        $this->data['text_total_affiliate_approval'] = $this->language->get('text_total_affiliate_approval');
        $this->data['text_day'] = $this->language->get('text_day');
        $this->data['text_week'] = $this->language->get('text_week');
        $this->data['text_month'] = $this->language->get('text_month');
        $this->data['text_year'] = $this->language->get('text_year');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_total_order'] = $this->language->get('text_total_order');
        $this->data['text_mystock'] = $this->language->get('text_mystock');
        $this->data['text_option'] = $this->language->get('text_option');
        $this->data['text_last_option'] = $this->language->get('text_last_option');
        $this->data['text_orders'] = $this->language->get('text_orders');

        $this->data['column_product'] = $this->language->get('column_product');
        $this->data['column_order'] = $this->language->get('column_order');
        $this->data['column_customer'] = $this->language->get('column_customer');
        $this->data['column_shipping_city'] = $this->language->get('column_shipping_city');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_date_added'] = $this->language->get('column_date_added');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['column_firstname'] = $this->language->get('column_firstname');
        $this->data['column_lastname'] = $this->language->get('column_lastname');
        $this->data['column_action'] = $this->language->get('column_action');
        
        $this->data['column_department']=$this->language->get('column_department');
        $this->data['column_department_parts']=$this->language->get('column_department_parts');
        $this->data['column_department_parts_qty']=$this->language->get('column_department_parts_qty');
        $this->data['column_department_parts_sold_qty']=$this->language->get('column_department_parts_sold_qty');

        $this->data['entry_range'] = $this->language->get('entry_range');



        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/myaccount', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
            );

        $this->data['total_sale'] = $this->currency->format($this->model_account_myaccount2->getTotalSales(), $this->config->get('config_currency'));
        $this->data['total_sale_year'] = $this->currency->format($this->model_account_myaccount2->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'));
        $this->data['total_order'] = $this->model_account_myaccount2->getTotalOrders();

        if (isset($this->request->get['startdate']) && strlen($this->request->get['startdate']) > 9) {
            $date1 = date('Y-m-d', strtotime($this->request->get['startdate']));
        } else {
            $date1 = date('Y-m-d',  strtotime(date('Y-m').'-01'));
        }
        if (isset($this->request->get['enddate']) && strlen($this->request->get['enddate']) > 9) {
            $date2 = date('Y-m-d', strtotime($this->request->get['enddate']));
        } else {
            $date2 = date('Y-m-d');
        }
        $startdate = $date1;
        $enddate = $date2;
        
        $filter=$this->request->get['filter'];
        if(!in_array($filter ,array('month','week','day'))){
            $filter = 'week';
        }
        
        //$diff = (strtotime($enddate) - strtotime($startdate)) / 24 / 3600;

        $this->data['startdate'] = $startdate;
        $this->data['enddate'] = $enddate;
        //$this->data['filter']=$filter;
        
        $data = array(
            'startdate' => $startdate,
            'enddate' => $enddate,
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'cust_code' =>$cust_code,
            //'filter'=>$filter
                );
        
        $res = $this->model_account_myaccount2->getProductOrders($data);
        
        $this->data['remain_options'] = $res['remain_options'];
        $this->data['remain_options_header'] = array();
        foreach($res['remain_options'] as $key=>$row){
            foreach ($row['desc'] as $key2=>$row2){ 
               $this->data['remain_options_header'][$key2]=$row2;
            }
        }
        
        $this->data['sold_options'] = $res['sold_options'];
        $this->data['sold_options_header'] = array();
        foreach($res['sold_options'] as $key=>$row){
            foreach ($row['desc'] as $key2=>$row2){ 
               $this->data['sold_options_header'][$key2]=$row2;
            }
        }
        //die(var_dump($this->data['options_header']));
        //echo "<pre>";
        //die(var_dump($res['sold_options']));
        $orders_list = $res['orders_list'];
        
        $this->data['total_order'] = count($res['order_count']);
        
        $res2 = $orders_list;
        $this->data['department']=$this->model_account_myaccount2->getSummary($data);
        //die(var_dump($this->data['department']));
        $this->data['total_review_approval'] = $customer_info['firstname'].' '.$customer_info['lastname'];
        
        $last_index = array_pop($res2);
        $this->data['total_affiliate'] = date('Y-m-d',strtotime($orders_list[0]['date_added']));
        $this->data['total_affiliate_approval'] = date('Y-m-d',  strtotime($last_index['date_added']));
        
        $this->data['sales']=array();
        //echo "<pre>";
        //die(var_dump($orders_list));
        foreach ($orders_list as $result) {
            $action = array();

            $this->data['orders'][] = array(
                'pname'=>$result['pname'],
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'shipping_city' => $result['shipping_city'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])) . ' ' . date($this->language->get('time_format'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'action' => $action
            );
        }
        
        //$this->data['text_filter'] = $this->language->get("text_{$filter}ly");
        //$this->data['chart'] = $this->model_account_myaccount2->getChartData($data);
        //echo"<pre>";die(var_dump($this->data['chart']));
        if ($this->config->get('config_currency_auto')) {
            //$this->load->model('localisation/currency');
            //$this->model_localisation_currency->updateCurrencies();
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/myaccount2.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/myaccount2.tpl';
        } else {
            $this->template = 'default/template/account/account2.tpl';
        }
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    public function chart() {
        $this->language->load('common/home');

        $data = array();

        $data['order'] = array();
        $data['customer'] = array();
        $data['xaxis'] = array();

        $data['order']['label'] = $this->language->get('text_order');
        $data['customer']['label'] = $this->language->get('text_customer');

        if (isset($this->request->get['range'])) {
            $range = $this->request->get['range'];
        } else {
            $range = 'month';
        }

        switch ($range) {
            case 'day':
                for ($i = 0; $i < 24; $i++) {
                    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '" . (int) $this->config->get('config_complete_status_id') . "' AND (DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int) $i . "') GROUP BY HOUR(date_added) ORDER BY date_added ASC");

                    if ($query->num_rows) {
                        $data['order']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['order']['data'][] = array($i, 0);
                    }

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int) $i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");

                    if ($query->num_rows) {
                        $data['customer']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['customer']['data'][] = array($i, 0);
                    }

                    $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
                }
                break;
            case 'week':
                $date_start = strtotime('-' . date('w') . ' days');

                for ($i = 0; $i < 7; $i++) {
                    $date = date('Y-m-d', $date_start + ($i * 86400));

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '" . (int) $this->config->get('config_complete_status_id') . "' AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");

                    if ($query->num_rows) {
                        $data['order']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['order']['data'][] = array($i, 0);
                    }

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");

                    if ($query->num_rows) {
                        $data['customer']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['customer']['data'][] = array($i, 0);
                    }

                    $data['xaxis'][] = array($i, date('D', strtotime($date)));
                }

                break;
            default:
            case 'month':
                for ($i = 1; $i <= date('t'); $i++) {
                    $date = date('Y') . '-' . date('m') . '-' . $i;

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '" . (int) $this->config->get('config_complete_status_id') . "' AND (DATE(date_added) = '" . $this->db->escape($date) . "') GROUP BY DAY(date_added)");

                    if ($query->num_rows) {
                        $data['order']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['order']['data'][] = array($i, 0);
                    }

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DAY(date_added)");

                    if ($query->num_rows) {
                        $data['customer']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['customer']['data'][] = array($i, 0);
                    }

                    $data['xaxis'][] = array($i, date('j', strtotime($date)));
                }
                break;
            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '" . (int) $this->config->get('config_complete_status_id') . "' AND YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");

                    if ($query->num_rows) {
                        $data['order']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['order']['data'][] = array($i, 0);
                    }

                    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");

                    if ($query->num_rows) {
                        $data['customer']['data'][] = array($i, (int) $query->row['total']);
                    } else {
                        $data['customer']['data'][] = array($i, 0);
                    }

                    $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
                }
                break;
        }

        $this->response->setOutput(json_encode($data));
    }

    public function login() {
        $route = '';

        if (isset($this->request->get['route'])) {
            $part = explode('/', $this->request->get['route']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }
        }

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return $this->forward('common/login');
        }

        if (isset($this->request->get['route'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            $config_ignore = array();

            if ($this->config->get('config_token_ignore')) {
                $config_ignore = unserialize($this->config->get('config_token_ignore'));
            }

            $ignore = array_merge($ignore, $config_ignore);

            if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return $this->forward('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return $this->forward('common/login');
            }
        }
    }

    public function permission() {
        if (isset($this->request->get['route'])) {
            $route = '';

            $part = explode('/', $this->request->get['route']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }

            $ignore = array(
                'common/home',
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
                return $this->forward('error/permission');
            }
        }
    }

}

?>