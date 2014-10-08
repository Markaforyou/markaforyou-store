<?php

class ControllerCommonRegisterdiv extends Controller {

    public function index() {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/registerdiv.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/registerdiv.tpl';
        } else {
            $this->template = 'default/template/common/registerdiv.tpl';
        }
//        die($this->template);
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));

        $this->data['heading_title'] = $this->config->get('config_title');

        $html='<style type="text/css" charset="utf-8">.main_div{width:422px;height:373px;background-color:#EAEAEA;}.div_1{width:422px;height:8px;float:left;display:inline block;background-color:#C95B00;}.div_logo{width:211px;height:67px;float:left;display:inline block;margin-left:112px;margin-top:1px;}.div_text{width:400px;height:66px;float:left;margin-left:11px;margin-top:2px;text-align:center;font-size: 12px;}.div_2{width:391px;height:8px;float:left;background-color:#C95B00;margin-left:14px;margin-top:12px;}.text_field_1 {width: 120px;height: 19px;float: left;margin-left: 64px;margin-top: 8px;font-size: 12px;font-family: Georgia, "Times New Roman", Times, serif;text-align: right;}.text_field_2 {width:183px;height:28px;float:left;margin-left:0px;margin-top:4px;font-size:12px;font-family:Georgia, "Times New Roman", Times, serif}.check_box_1 {width:145px;height:45px;float:left;margin-left:35px;margin-top:8px;}.check_box_2 {width:105px;height:45px;float:left;margin-left:2px;margin-top:8px;}.button {width:103px;height:28px;float:left;margin-left:28px;margin-top:6px;font-size:24px;}.div_11{width:422px;height:8px;float:left;margin-top:17px;background-color:#C95B00;}</style>'
                . '<div class="noty_bar s_notify noty_layout_topRight noty_alert noty_closable" id="noty_alert_1400071868849" style="display: block;">'
                . '<div class="main_div">'
                . '<div class="div_1"></div>'
                . '<div class="div_logo">'
                . '<img src="/store/image/data/logo.png" width="211" height="67" alt="markaforyou.com" title="markaforyou.com" />'
                . '</div>'
                . '<div class="div_text">'
                . '<center>'
                . '<font face="Times New Roman, Times, serif">'
                . '<strong>'
                . '<span style="font-size:16px">ماركة فور يو للتجارة الإلكترونية</span>'
                . '<br />'
                . ' وهو موقع متخصص في عرض وبيع المنتجات العالمية إلكترونياً، من خلال توفير مجموعة واسعة من المنتجات ووضعها أمام عملائنا الأعزاء ليتسنى لهم مواكبة التطور العالمي في مجال التجارة الإلكترونية كإحدى الخيارات التي تؤكد بأن العالم أصبح بالفعل قرية صغيرة'
                . '</strong>'
                . '</font>'
                . '</center></div><div class="div_2"></div><form action="#" method="post" >'
                . '<div class="text_field_1"><strong> الاسم الاول :لا يشترط كاملاً</strong></div>'
                . '<div class="text_field_2"><input type="text" name="firstname" /></div>'
                . '<div class="text_field_1"><strong>البريد الإلكتروني</strong></div>'
                . '<div class="text_field_2"><input type="text" name="firstname"></div>'
                . '<div class="text_field_1"><strong>رقم الجوال : يبدأ بالرقم الدولي</strong></div>'
                . '<div class="text_field_2"><input type="text" name="firstname"/></div>'
                . '<div class="check_box_1"><input type="checkbox" name="vehicle" value="Bike" />'
                . ' شنط نسائية<br /><input type="checkbox" name="vehicle" value="Car" /> ملابس نسائية ولانجري </div>'
                . '<div class="check_box_2"><input type="checkbox" name="vehicle" value="Bike" />'
                . ' ملابس رجالية<br/><input type="checkbox" name="vehicle" value="Car" />'
                . ' اكسسورات </div><div class="check_box_2">'
                . '<input type="checkbox" name="vehicle" value="Bike" /> ملابس رجالية<br />'
                . '<input type="checkbox" name="vehicle" value="Car" /> اكسسورات </div>'
                . '<div class="button"><input type="submit" style="background-color:#A85000; height:30px; width:103px; font-size:18px" value="اشترك الان" />'
                . '</div><div class="div_11"></div></form></div></div>';
        //echo ($html);
        $this->response->setOutput($this->render());
    }
}