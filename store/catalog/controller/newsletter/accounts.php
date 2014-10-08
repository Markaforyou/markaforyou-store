<?php

/*
  @author	Dmitriy Kubarev
  @link	http://www.simpleopencart.com
  @link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
 */

require_once(DIR_SYSTEM . 'library/simple/simple.php');

class ControllerNewsletterAccounts extends Controller {

    private $error = array();

    public function index() {
        //if ($this->customer->isLogged()) {
        //    $this->redirect($this->url->link('account/account', '', 'SSL'));
        //}
        //$this->redirect('/');
        $this->language->load('newsletter/accounts');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('newsletter/accounts');

        //$this->data['categories'] = $this->model_newsletter_accounts->get_cats()->rows;
        //$this->data['categories'] = $this->model_newsletter_accounts->get_cats()->rows;
        //echo "<pre>";
        //die(var_dump($this->data['categories']));
        $this->data['simple_registration_captcha'] = $this->config->get('simple_registration_captcha');
        $this->data['simple_registration_subscribe'] = $this->config->get('simple_registration_subscribe');
        $this->data['simple_registration_subscribe_init'] = $this->config->get('simple_registration_subscribe_init');
        $this->data['simple_registration_view_customer_type'] = $this->config->get('simple_registration_view_customer_type');

        $this->data['simple_registration_view_email_confirm'] = $this->config->get('simple_registration_view_email_confirm');

        $this->data['simple_type_of_selection_of_group'] = $this->config->get('simple_type_of_selection_of_group');
        $this->data['simple_type_of_selection_of_group'] = !empty($this->data['simple_type_of_selection_of_group']) ? $this->data['simple_type_of_selection_of_group'] : 'select';

        $this->data['error_warning'] = '';
        $this->data['error_captcha'] = '';

        $this->simple = new Simple($this->registry);

        $this->data['customer_groups'] = array();

        $this->data['customer_group_id'] = $this->config->get('config_customer_group_id');

        if ($this->data['simple_registration_view_customer_type']) {
            $this->data['customer_groups'] = $this->simple->get_customer_groups();

            if (isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $this->data['customer_groups'])) {
                $this->data['customer_group_id'] = $this->request->post['customer_group_id'];
            }
        }

        $this->data['simple_create_account'] = !empty($this->request->post['simple_create_account']);

        $this->data['customer_fields'] = $this->simple->load_fields(Simple::SET_REGISTRATION, array('group' => $this->data['customer_group_id']));
        //echo "<pre>";
        //die(var_dump($this->data['customer_fields']));
        /////qasem
        $this->data['email_confirm_error'] = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {

            if (!isset($_POST['registration']['main_email']) || strlen($_POST['registration']['main_email']) <= 0) {
                $this->data['customer_fields']['main_email']['error'] = $this->language->get('error_exists');
                $error = true;
            }
            //die(var_dump($_POST));
            $data = array(
                'firstname' => $_POST['registration']['main_firstname'],
                'email' => $_POST['registration']['main_email'],
                'telephone' => $_POST['registration']['main_telephone'],
                'chk0' => isset($_POST['cats']['chk0']) ? "1" : "0",
                'chk1' => isset($_POST['cats']['chk1']) ? "1" : "0",
                'chk2' => isset($_POST['cats']['chk2']) ? "1" : "0",
                'chk3' => isset($_POST['cats']['chk3']) ? "1" : "0",
                'chk4' => isset($_POST['cats']['chk4']) ? "1" : "0",
                'chk5' => isset($_POST['cats']['chk5']) ? "1" : "0",
            );
            $this->data['message'] = "تم التسجيل بنجاح";
            $this->data['firstname'] = $data['firstname'];
            $this->data['email'] = $data['email'];
            $this->data['telephone'] = $data['telephone'];


            $this->model_newsletter_accounts->addcust($data);

            $file = $this->get_html();
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setFrom(($this->config->get('config_email'))); //."MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=UTF-8" . "\r\n"));
            $mail->setSender($this->config->get('config_name'));
            $mail->setHtml($file);
            $mail->setSubject("شكرا لتسجيلك في مجلة ماركة فوريو للتجارة الالكترونيه ");
            $mail->setText("شكرا لتسجيلك معنا");
            $mail->setTo($_POST['registration']['main_email']);
            $mail->send();
            //echo "<pre>";
            //die(var_dump($mail));
            $this->redirect($this->url->link('newsletter/accounts/success', '', 'SSL'));
        }
        $this->data['customer_fields'] = array(
            "main_firstname" => array(
                "id" => "main_firstname",
                "from" => "registration",
                "label" => "الإسم ( لا يشترط الإسم الكامل)",
                "required" => FALSE,
                "object_type" => 0,
                "type" => "text",
                "value" => $this->data['firstname'],
                "values" => array(),
                "error" => "الإسم الأول يجب أن يكون بين 2 إلى 60 حرف",
                "save_to" => "firstname",
                "save_label" => false,
                "mask" => "",
                "placeholder" => "",
                "autocomplete" => false,
                "reload" => false,
                "date_min" => "",
                "date_max" => "",
                "date_start" => "",
                "date_end" => "",
                "date_only_business" => "",
                "place" => ""),
            "main_email" => array(
                "id" => "main_email",
                "from" => "registration",
                "label" => "البريد الإلكتروني",
                "required" => true,
                "object_type" => 0,
                "type" => "text",
                "value" => $this->data['email'],
                "values" => array(),
                "error" => "البريد الإلكتروني خطأ",
                "save_to" => "email",
                "save_label" => false,
                "mask" => "", "placeholder" => "",
                "autocomplete" => false, "reload" => false,
                "date_min" => "",
                "date_max" => "",
                "date_start" => "",
                "date_end" => "",
                "date_only_business" => "", "place" => ""),
            "main_telephone" => array(
                "id" => "main_telephone",
                "from" => "registration",
                "label" => "رقم الجوال ( يبدأ بالرقم الدولي مثلاً السعودية 966550462222)",
                "required" => FALSE, "object_type" => 0,
                "type" => "text",
                "value" => $this->data['telephone'],
                "values" => array(),
                "error" => "رقم الهاتف/الجوال يجب أن يبدأ بالرقم الدولي مثلاً السعودية 966",
                "save_to" => "telephone",
                "save_label" => false,
                "mask" => "",
                "placeholder" => "",
                "autocomplete" => false,
                "reload" => false,
                "date_min" => "",
                "date_max" => "",
                "date_start" => "",
                "date_end" => "",
                "date_only_business" => "",
                "place" => "")
        );
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('newsletter/accounts', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $this->data['text_your_details'] = $this->language->get('text_your_details');
        $this->data['text_company_details'] = $this->language->get('text_company_details');
        $this->data['text_newsletter'] = $this->language->get('text_newsletter');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_select'] = $this->language->get('text_select');



        $this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['entry_customer_type'] = $this->language->get('entry_customer_type');

        $this->data['entry_email_confirm'] = $this->language->get('entry_email_confirm');
        $this->data['error_email_confirm'] = $this->language->get('error_email_confirm');

        $this->data['action'] = $this->url->link('newsletter/accounts', '', 'SSL');

        $this->data['simple_registration_agreement_checkbox'] = false;
        $this->data['simple_registration_agreement_checkbox_init'] = 0;

        $this->data['text_agree'] = '';

        /* if ($this->config->get('simple_registration_agreement_id')) {
          $this->load->model('catalog/information');

          $information_info = $this->model_catalog_information->getInformation($this->config->get('simple_registration_agreement_id'));

          if ($information_info) {
          $this->data['simple_registration_agreement_checkbox'] = $this->config->get('simple_registration_agreement_checkbox');
          $this->data['simple_registration_agreement_checkbox_init'] = $this->config->get('simple_registration_agreement_checkbox_init');

          $current_theme = $this->config->get('config_template');

          $text = ($current_theme == 'shoppica' || $current_theme == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
          $this->data['text_agree'] = sprintf($this->language->get($text), $this->url->link('information/information/info', 'information_id=' . $this->config->get('simple_registration_agreement_id'), 'SSL'), $information_info['title'], $information_info['title']);
          }
          } */

        if (isset($this->request->post['agree'])) {
            $this->data['agree'] = $this->request->post['agree'];
        } else {
            $this->data['agree'] = $this->data['simple_registration_agreement_checkbox_init'];
        }

        if (isset($this->request->post['subscribe'])) {
            $this->data['subscribe'] = $this->request->post['subscribe'];
        } else {
            $this->data['subscribe'] = $this->data['simple_registration_subscribe_init'];
        }

        $this->data['email_confirm'] = isset($this->request->post['email_confirm']) ? trim($this->request->post['email_confirm']) : '';

        //echo $this->data['language_code'] = $this->simple->get_language_code();

        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsletter/accounts.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/newsletter/accounts.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->redirect('/');
            //$this->template = 'default/template/account/simpleregister.tpl';
            //$this->data['template'] = 'default';
        }

        $this->simple->add_static($this->data['template'], 'simpleregister');

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

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }

    public function captcha() {
        $this->load->library('captcha');

        $captcha = new Captcha();

        $this->session->data['captcha'] = $captcha->getCode();

        $captcha->showImage();
    }

    private function validate() {
        $error = false;

        if (!isset($_POST['registration']['main_email']) || !$this->valid_email($_POST['registration']['main_email'])) {
            $this->data['customer_fields']['main_email']['error'] = $this->language->get('error_exists');
            $error = true;
        }

        return !$error;
    }

    private function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function geo() {
        $this->load->model('tool/simplegeo');

        $term = $this->request->get['term'];

        $this->response->setOutput(json_encode($this->model_tool_simplegeo->getGeoList($term)));
    }

    public function success() {
        $this->language->load('newsletter/accounts');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('newsletter/accounts');


        $this->data['simple_registration_captcha'] = $this->config->get('simple_registration_captcha');
        $this->data['simple_registration_subscribe'] = $this->config->get('simple_registration_subscribe');
        $this->data['simple_registration_subscribe_init'] = $this->config->get('simple_registration_subscribe_init');
        $this->data['simple_registration_view_customer_type'] = $this->config->get('simple_registration_view_customer_type');

        $this->data['simple_registration_view_email_confirm'] = $this->config->get('simple_registration_view_email_confirm');

        $this->data['simple_type_of_selection_of_group'] = $this->config->get('simple_type_of_selection_of_group');
        $this->data['simple_type_of_selection_of_group'] = !empty($this->data['simple_type_of_selection_of_group']) ? $this->data['simple_type_of_selection_of_group'] : 'select';

        $this->data['error_warning'] = '';
        $this->data['error_captcha'] = '';

        $this->simple = new Simple($this->registry);
        $this->data['customer_groups'] = array();
        $this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
        if ($this->data['simple_registration_view_customer_type']) {
            $this->data['customer_groups'] = $this->simple->get_customer_groups();

            if (isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $this->data['customer_groups'])) {
                $this->data['customer_group_id'] = $this->request->post['customer_group_id'];
            }
        }


        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('newsletter/accounts', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $this->data['text_your_details'] = $this->language->get('text_your_details');
        $this->data['text_company_details'] = $this->language->get('text_company_details');
        $this->data['text_newsletter'] = $this->language->get('text_newsletter');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_success'] = $this->language->get('text_success');

        $this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['entry_customer_type'] = $this->language->get('entry_customer_type');

        $this->data['entry_email_confirm'] = $this->language->get('entry_email_confirm');
        $this->data['error_email_confirm'] = $this->language->get('error_email_confirm');
        
        $this->data['action'] = $this->url->link('newsletter/accounts', '', 'SSL');

        $this->data['simple_registration_agreement_checkbox'] = false;
        $this->data['simple_registration_agreement_checkbox_init'] = 0;

        $this->data['text_agree'] = '';


        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsletter/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/newsletter/success.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->redirect('/');
            //$this->template = 'default/template/account/simpleregister.tpl';
            //$this->data['template'] = 'default';
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

    public function get_html() {
        return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>ماركة فور يو</title><script type="text/javascript" charset="utf-8">function MM_preloadImages() { var d = document;
                if (d.images) {
                    if (!d.MM_p)
                        d.MM_p = new Array();
                    var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
                    for (i = 0; i < a.length; i++)
                        if (a[i].indexOf("#") != 0) {
                            d.MM_p[j] = new Image;
                            d.MM_p[j++].src = a[i];
                        }
                }
            }
        </script>
        <style type="text/css">
            /*Hotmail and Yahoo specific code*/  
            .ReadMsgBody {width: 100%;}
            .ExternalClass {width: 100%;}
            .yshortcuts {color: #ffffff;}
            .yshortcuts a span {color: #ffffff;border-bottom: none !important;background: none !important;}
            /*Hotmail and Yahoo specific code*/ 
            body {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
                -webkit-font-smoothing: antialiased;
                margin: 0 !important;padding: 0 !important;width: 100% !important;}

            @media only screen and (max-width: 599px) {
                body {width: auto !important;}
                table[class="table610"] {max-width: 610px !important;width: 100% !important;}
                table[class=table600] {max-width: 600px !important;width: 100% !important;}
                td[class="erase"] {width: 0 !important;display: none !important;}
                td[class="hide"] {width: 15px !important;}
                td[class="issue"] {width: 80px;}
                td[class="bnr_hide"] {width: 6px;}
                td[class="banner_lft"] {max-width: 297px !important;width: 100% !important;display: block;margin: 0 auto;}
                td[class="banner_rgt"] {max-width: 297px !important;width: 100% !important;display: block;margin: 0 auto;}
                img[class="imag146"] {max-width: 146px !important;height: auto;width: 100% !important;display: block !important;}
                img[class="img260"] {max-width: 260px !important;height: auto;width: 100% !important;display: block !important;}
                td[class="box_lft"] {max-width: 290px !important;width: 100% !important;display: block;margin: 10px auto;
                }
                td[class="box_ght"] {max-width: 290px !important;width: 100% !important;display: block;margin: 0 auto;}
                td[class="box_01"] {max-width: 292px !important;width: 100% !important;display: block;margin: 10px auto !important;
                }
                td[class="box_02"] {max-width: 292px !important;width: 100% !important;display: block;margin: 0 auto !important;}
                img[class="img_190"] {max-width: 190px !important;width: 100% !important;height: auto;display: block;}
                td[class="button"] {max-width: 100px !important;margin: 0 auto;overflow: hidden !important;display: block !important;}

                @media only screen and (max-width: 479px) {
                    body {width: auto !important;}
                    table[class="table610"] {max-width: 610px !important; width: 100% !important;}
                    table[class=table600] { max-width: 600px !important; width: 100% !important;}
                    td[class="hide"] { width:0!important; display:none!important;}
                    td[class="bnr_hide"] { width:0; display:none;}
                    td[class="issue"] { width:0; display:none;}
                    td[class="banner_lft"] { max-width: 297px !important; width: 100% !important; display: block; margin:0 auto;}
                    td[class="banner_rgt"] { max-width: 297px !important; width: 100% !important; display: block; margin:0 auto; padding-top:5px!important;}
                    img[class="imag146"] { max-width: 146px !important; height:auto; width: 100% !important; display: block !important;}
                    img[class="img260"] { max-width: 260px !important; height:auto; width: 100% !important; display: block !important;}
                    td[class="box_01"] { max-width: 292px !important; width: 100% !important; display: block; margin:10px auto;}
                    td[class="box_02"] { max-width: 292px !important; width: 100% !important; display: block; margin:0 auto;}
                    td[class="boxx_1"] { max-width: 190px !important; width: 100% !important; display: block; margin:10px auto;}
                    img[class="img_190"] { max-width: 190px !important; width: 100% !important; height:auto; display: block;}
                    td[class="social_group"] { max-width: 200px !important; width: 100% !important; display: block; margin:10px auto !important;}
                    td[class="callus"] { max-width: 300px !important; width: 100% !important; display: block; text-align:center !important; margin:5px auto !important;
                                         padding:0 !important;}
                    td[class="friend"] { display: block; text-align:center !important;}
                    td[class="button"] { max-width: 100px !important; margin:0 auto; overflow:hidden !important; display:block !important;}
                }
            </style>

        </head>
        <body bgcolor="#ffffff" style="margin:0px; padding:0px;">
            <table width="101%" border="0" cellspacing="0" cellpadding="0">
                <!--header goes here-->  <!--header end here-->
                <tr>
                    <td height="232">
                        <table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="table610">
                            <tr>
                                <td width="5" height="235"></td>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr></tr>
                                        <!--part1 goes here-->
                                        <tr>
                                            <td height="125">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td height="10" width="115" style="border-bottom:1px solid #C7C7CB;"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="10"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td align="center" valign="middle"><img style="display:block;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/marka.png" alt="" width="70" height="11"></td>
                                                                    <td valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td height="10" width="115" style="border-bottom:1px solid #C7C7CB;"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="10"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr><td height="2"></td></tr>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td valign="top" >
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td height="45" align="center" style="font-family: Tahoma, Geneva, sans-serif; font-size: 16px; line-height: 22px; color: #000000;"><strong> ماركة فور يو للتجارة الإلكترونية</strong></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="44" align="center" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; line-height: 22px; color: #000000;"><strong>شكراً لتسجيلكم في ماركة فور يو</strong><br>
                                                                                    موقع متخصص في عرض وبيع المنتجات العالمية إلكترونياً، من خلال توفير مجموعة واسعة من المنتجات ووضعها أمام عملائنا الأعزاء ليتسنى لهم مواكبة التطور العالمي في مجال التجارة الإلكترونية كإحدى أهم الخيارات التي تؤكد بأن العالم أصبح بالفعل قرية صغيرة</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="40" align="center" valign="middle" style="font-family: Tahoma, Geneva, sans-serif; font-size: 14px; line-height: 22px; color: #000000; padding-top: 4px;"><span style="font-family: Tahoma, Geneva, sans-serif; font-size: 18px; line-height: 22px; color: #FF0000; font-weight: bold;"><strong>                            التوصيل مجاناً لكافة انحاء المملكة و الدفع بعد التسليم</strong></span></td>
                                                                            </tr>
                                                                            <tr></tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--part1 end here-->
                                    </table>
                                </td>
                                <td width="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="303" valign="middle" bgcolor="#eeeeee">
                        <table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="table610">
                            <tr>
                                <td width="5" height="230"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="230">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="294" height="270" valign="top" bgcolor="#FFFFFF" class="box_01" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;"><a href="http://www.markaforyou.com/store/ar/c/83_87/%D9%85%D9%84%D8%A7%D8%A8%D8%B3/%D9%85%D9%84%D8%A7%D8%A8%D8%B3-%D9%86%D9%88%D9%85-%D9%84%D8%A7%D9%86%D8%AC%D8%B1%D9%8A/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/lingire.png" width="300" height="270" alt="اطقم نوم ولانجري" title="اطقم نوم ولانجري" style="border:none"></a></td>
                                                                    <td width="1" class="hide"></td>
                                                                    <td class="box_02" width="305" bgcolor="#FFFFFF" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;" valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td width="1" height="268"></td>
                                                                                <td width="301" height="270" align="right" valign="top"><a href="http://www.markaforyou.com/store/ar/c/83_86/%D9%85%D9%84%D8%A7%D8%A8%D8%B3/%D8%A8%D9%8A%D8%AC%D8%A7%D9%85%D8%A7%D8%AA/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/bejama.png" width="300" height="270" alt="بيجامات تركية" title="بيجامات تركية" style="border:none"></a></td>
                                                                                <td width="1"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr></tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="308">
                        <table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="table610">
                            <tr>
                                <td width="5" height="230"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="230">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="295" height="270" align="center" valign="top" bgcolor="#FFFFFF" class="box_01" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;"><a href="http://www.markaforyou.com/store/ar/c/80/%D8%A7%D9%83%D8%B3%D8%B3%D9%88%D8%A7%D8%B1%D8%A7%D8%AA/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/acc.png" width="300" height="270" alt="إكسسورات نسائية" title="إكسسورات نسائية" style="border:none"></a></td>
                                                                    <td width="1" bgcolor="#FFFFFF" class="hide"></td>
                                                                    <td class="box_02" width="304" bgcolor="#FFFFFF" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;" valign="top">
                                                                        <table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td width="1" height="268"></td>
                                                                                <td width="301" align="right" valign="top"><a href="http://www.markaforyou.com/store/ar/c/108/%D8%A8%D9%8A%D8%AC%D8%A7%D9%85%D8%A7%D8%AA-%D8%B1%D8%AC%D8%A7%D9%84%D9%8A%D8%A9/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/male-bejama.png" width="300" height="270" alt="بيجامات رجالية تركية" title="بيجامات رجالية تركية" style="border:none"></a></td>
                                                                                <td width="1"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr></tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="317" align="center" valign="middle" bgcolor="#eeeeee">
                        <table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="table610">
                            <tr>
                                <td width="5" height="230"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="230">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="295" height="270" align="left" valign="top" bgcolor="#FFFFFF" class="box_01" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;"><a href="http://www.markaforyou.com/store/ar/c/106/%D8%B4%D9%86%D8%B7-%D9%86%D8%B3%D8%A7%D8%A6%D9%8A%D8%A9/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/bag.png" width="300" height="270" alt="شنط يد نسائية" title="شنط يد نسائية" style="border:none"></a></td>
                                                                    <td width="1" bgcolor="#FFFFFF" class="hide"></td>
                                                                    <td class="box_02" width="304" bgcolor="#FFFFFF" style="border-left:1px solid #DDDDDD; border-right:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; border-top:1px solid #DDDDDD;" valign="top"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td width="1" height="268"></td>
                                                                                <td width="301" align="right" valign="top"><a href="http://www.markaforyou.com/store/ar/c/65/مفارش-السرير/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank"><img src="https://markaforyou.com/mailshots/mailshots/welcome-letter/images/bedcovers.png" width="300" height="270" alt="مفارش سرير تركية" title="مفارش سرير تركية" style="border:none"></a></td>
                                                                                <td width="1"></td>
                                                                            </tr>
                                                                        </table></td>
                                                                </tr>
                                                            </table></td>
                                                    </tr>
                                                    <tr></tr>
                                                </table></td>
                                        </tr>
                                    </table></td>
                                <td width="5"></td>
                            </tr>
                        </table></td>
                </tr>


                <!--footer goes here-->
                <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td bgcolor="#b66829" style="padding-top:8px; padding-bottom:8px;"><table width="610" border="0" cellspacing="0" cellpadding="0"  class="table610" align="center">
                                        <tr>
                                            <td width="5"></td>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <!--social logo goes here-->
                                                        <td width="200" class="social_group"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="32">
                                                                        <a href="https://www.facebook.com/marka4you?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank">
                                                                            <img style="display:block; max-width:32px; max-height:32px;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/fb.jpg" alt="facebook" title="facebook" width="32" height="32" border="0" />
                                                                        </a>
                                                                    </td>
                                                                    <td width="10"></td>
                                                                    <td width="32">
                                                                        <a href="https://twitter.com/MarkaForYou?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank">
                                                                            <img style="display:block; max-width:32px; max-height:32px;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/twitter.jpg" alt="twitter" title="twitter" width="32" height="32" border="0" />
                                                                        </a>
                                                                    </td>
                                                                    <td width="10"></td>
                                                                    <td width="32">
                                                                        <a href="http://www.youtube.com/channel/UCvOoSUqGod6zQLcteILxhgw?feature=watch&utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank">
                                                                            <img style="display:block; max-width:32px; max-height:32px;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/youtube.jpg" alt="youtube" title="youtube" width="32" height="32" border="0" />
                                                                        </a>
                                                                    </td>
                                                                    <td width="10"></td>
                                                                    <td width="32">
                                                                        <a href="http://www.linkedin.com/company/3334140?trk=prof-exp-company-name&utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank">
                                                                            <img style="display:block; max-width:32px; max-height:32px;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/linkedin.jpg" alt="linkedin" title="linkedin" width="32" height="32" border="0" />
                                                                        </a>
                                                                    </td>
                                                                    <td width="10"></td>
                                                                    <td width="32">
                                                                        <a href="http://www.pinterest.com/search/?q=markaforyou&utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank">
                                                                            <img style="display:block; max-width:32px; max-height:32px;" src="https://markaforyou.com/mailshots/mailshots/MAR-5/images/pin.png" alt="pinterest" title="pinterest" width="32" height="32" border="0"/>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table></td>
                                                        <!--social logo end here-->
                                                        <td width="1" class="hide"></td>
                                                        <!--callus goes here-->
                                                        <td width="399" class="callus" style="font-family: arial, Helvetica, sans-serif,Trebuchet MS; font-size: 19px; line-height: 18px; color: #FFFFFF; padding-left: 5px; text-align: right;">
                                                            <strong>للاستفسار و خدمة الجملة اتصل على 00966547777526</strong>
                                                        </td>
                                                        <!--callus end here-->
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="5"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="70" bgcolor="#2c2c2c"><table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="table610">
                                        <tr>
                                            <td width="5" height="66"></td>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td height="8"></td>
                                                    </tr>
                                                    <!--address goes here-->
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                                <tr>
                                                                    <td align="center" style="font-family:arial, Helvetica, sans-serif, Trebuchet MS; font-size:12px; line-height:20px; color:#ffffff; padding-left:15px; padding-right:15px; padding-top:8px; padding-bottom:6px;">Email-us:&nbsp;<strong style="text-decoration:none"><a style="text-decoration:none;color: #FFF;" href="mailto:info@markaforyou.com" target="_top"> info@markaforyou.com</a></strong> | &nbsp;Web:&nbsp;<a href="http://www.markaforyou.com/?utm_source=Newsletter&utm_medium=email&utm_content=Zabuzayyad&utm_campaign=welcome-letter" target="_blank" style="color:#ffffff; text-decoration:none;"><strong>www.markaforyou.com</strong></a></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <!--address end here-->
                                                    <tr>
                                                        <td height="22" align="center">
                                                            <font color="#FFFFFF">جميع الحقوق محفوظة ماركة فور يو 2014</font>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="5"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--footer end here-->
            </table>
        </body>
    </html>
';
    }

}

?>