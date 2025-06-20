<?php

/**

 * Plugin Name: Booking by Beds24 API

 * Description: Plugin for booking system beds24

 * Version: 0.0.1

 */

define('BEDS_DIR', __DIR__);

define("BEDS_URL", plugins_url().'/beds24-booking/');

//region Debug
if(!function_exists('is_dev')){
    function is_dev(){
        $allowed = [
            'de4c64603cc02f862ad068610a21a00f'
        ];
        return in_array(md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), $allowed);
    }

}
if(!function_exists('print_array')) {
    function print_array($arr = [])
    {
        printf('<pre>%s</pre>', print_r($arr, true));
    }
}
if(!function_exists('dd')){
    function dd($arr = []){
        print_array($arr);
        die();
    }
}
if(!function_exists('dd_dev')){
    function dd_dev($arr = []){
        if(is_dev()){
            dd($arr);
        }
    }
}
//endregion Debug

if(!function_exists('get_months_by_period')){
    function get_months_by_period(){
        return [
            'winter' => [
                1 => __('Januari'),
                2 => __('Februari'),
                3 => __('Mars'),
                4 => __('April'),
                5 => __('Maj'),

                11 => __('November'),
                12 => __('December'),
            ],
            'summer' => [
                6 => __('Juni'),
                7 => __('Juli'),
                8 => __('Augusti'),
                9 => __('September'),
                10 => __('Oktober'),
            ]
        ];
    }
}

require_once (BEDS_DIR.'/includes/loader_new.php');
// require_once (BEDS_DIR.'/includes/loader.php');

function sdts_test(){
    if(!is_dev()) return;


    $order = wc_get_order(5151);
    //$order = wc_get_order(5150);
    //$order = wc_get_order(5154);
    //$order = wc_get_order(5154);
    //$order = wc_get_order(5234);
    $url = $order->get_checkout_order_received_url();
    // $url = home_url( '/order-received/' . $order_id . '/?key=' . $order->get_order_key() );
    dd_dev($url);

}
//add_action('init', 'sdts_test');

function cssClassToStyle($classes){

    $classesArr = is_array($classes) ? $classes : explode(' ', $classes);

    $classList = [
        'mail-table-summary-th-1' => "font-size: 16px;border-radius: 5px 0 0 5px; font-weight: 700; line-height: 28px; color: #000; padding: 3px 8px; text-align: left; background-color: #e5e5e5;",
        'mail-table-summary-th-2' => "padding: 8px; text-align: left;border-radius:0 5px 5px 0; background-color: #e5e5e5;",

        'mail-table-summary-tdh' => "font-weight: 600;",
        'mail-table-summary-tdl' => "",
        'mail-table-summary-tdr' => "font-size: 16px; text-align: right;",
        'mail-table-summary-td-total' => "font-size: 16px; font-weight: 700; padding-top: 20px;",
        'mail-table-summary-td' => "font-size: 14px; font-weight: 400; line-height: 22px; padding: 8px;",

        'pt0' => "padding-top: 0px;",
        'pb0' => "padding-bottom: 0px;",
        'lh10' => "line-height: 10px;",

        //'mail-p' => 'margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;',
        'mail-logo-td' => 'padding: 10px 10px 50px 10px;',
        'mail-p-title' => "font-size: 16px; font-weight: 700; line-height: 28px;",
        'mail-p' => "font-size: 14px; font-weight: 400; line-height: 22px;",
        'mail-text-td' => 'padding: 5px 30px;',
        'mail-text-td2' => 'padding: 5px 38px;',
        'mail-text-tdp' => 'padding: 0 30px;'
    ];

    $str = '';
    foreach($classesArr AS $class){
        if(!empty($classList[$class])){
            $str .= ' '.$classList[$class];
        }
    }

    return trim($str);
}
