<?php

// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

function addtocart()
{
    $cart = WC()->cart;
//    var_dump(count($cart)); exit();


    if (count($cart->cart_contents) > 2) {
        echo json_encode(['limit'], 320);
        wp_die();
        //return;
    }

    global $woocommerce;
    $product_id = intval($_POST['product_id']);
    $room = get_post_meta($product_id, '_product_beds_id', true);
    $custom_price = floatval($_POST['custom_price']);


     $date_from = DateTime::createFromFormat('Y-m-d', $_POST['date_from']) ?:
         DateTime::createFromFormat('Y/m/d', $_POST['date_from']) ?:
             DateTime::createFromFormat('d/m/Y', $_POST['date_from']) ?:
                 DateTime::createFromFormat('m/d/Y', $_POST['date_from']) ?:
                     DateTime::createFromFormat('d-m-Y', $_POST['date_from']) ?:
                         DateTime::createFromFormat('m-d-Y', $_POST['date_from']);

     $date_from = $date_from->format('Y-m-d');

    $date_to = DateTime::createFromFormat('Y-m-d', $_POST['date_to']) ?:
        DateTime::createFromFormat('Y/m/d', $_POST['date_to']) ?:
            DateTime::createFromFormat('d/m/Y', $_POST['date_to']) ?:
                DateTime::createFromFormat('m/d/Y', $_POST['date_to']) ?:
                    DateTime::createFromFormat('d-m-Y', $_POST['date_to']) ?:
                        DateTime::createFromFormat('m-d-Y', $_POST['date_to']);

    $date_to = $date_to->format('Y-m-d');
//    var_dump($date_from.$date_to); die();
    $cart_item_data = array('custom_price' => $custom_price, 'booked_from' => $date_from, 'booked_to' => $date_to, 'persons_adult' => $_POST['personsA'], 'persons_child' => $_POST['personsC'], 'product_id' => $room);

    //remove duplicates
    $tmp_cart_contents = $cart->get_cart_contents();
    foreach($tmp_cart_contents AS $cart_item){
        if($cart_item['product_id'] == $product_id){
            $cart->remove_cart_item($cart_item['key']);
        }
    }

    WC()->cart->add_to_cart($product_id, 1, $variation_id, $variation, $cart_item_data);
    WC()->cart->calculate_totals();
    WC()->cart->set_session();
    WC()->cart->maybe_set_cart_cookies();
    $result['shipping'] = 123;

    /*
    *     Add accommodation to table (reserved), change available and send api req with status "New"
    */
    setReserve($product_id, $_POST['date_from'], $_POST['date_to'], $_POST['personsA'], $_POST['personsC']);
    if(!empty($_POST['accompanied_dog'])){
        $accompanied_dog_key = 'accompanied_dog'.(count(WC()->cart->get_cart_contents())-1);
        setcookie($accompanied_dog_key, $_POST['accompanied_dog'], time() + 3600, '/');
        $_COOKIE[$accompanied_dog_key] = $_POST['accompanied_dog'];
        $tmp_cart_contents = $cart->get_cart_contents();
        $cart_item = end($tmp_cart_contents);
        $_POST['cart_id'] = $cart_item['key'];
        $_POST['options'] = 'accompanied_dog|'.$_POST['accompanied_dog'].'|0';
        update_options();
    }

    echo json_encode($result, 320);
    wp_die();

} //endfunction


add_action('wp_ajax_addtocart', 'addtocart');

add_action('wp_ajax_nopriv_addtocart', 'addtocart');


add_action('wp_ajax_getAvailByRoomID', 'getAvailByRoomID');

add_action('wp_ajax_nopriv_getAvailByRoomID', 'getAvailByRoomID');

function getAvailByRoomID()
{

    global $wpdb;

    $room = get_post_meta($_POST['product_id'], '_product_beds_id', true);

    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=1 and `avaliable`=1";

    $res = $wpdb->get_results($sql, ARRAY_A);

    $timeArr = array();

    foreach ($res as $re) {

        array_push($timeArr, $re['date']);

    }


    $sql_asc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` asc";

    $sql_desc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` desc";

    $res_asc = $wpdb->get_results($sql_asc, ARRAY_A);

    $res_desc = $wpdb->get_results($sql_desc, ARRAY_A);


    $exclude_arr = [];

    $day_plus = 0;

    foreach ($res_asc as $re) {

        $day = date('Y-m-d', strtotime($re['date']));

        if ($day != $day_plus) {

            $day_w = date('w', strtotime($re['date']));

            if ($day_w == '4' || $day_w == '0') {

                array_push($timeArr, $day);

            }

        }

        $day_plus = date('Y-m-d', strtotime($re['date'] . ' +1 day'));

    }


    $day_minus = 0;

    foreach ($res_desc as $re) {

        $day = date('Y-m-d', strtotime($re['date']));

        if ($day != $day_minus) {

            $day_w = date('w', strtotime($re['date']));

            if ($day_w == '4' || $day_w == '0') {

                array_push($timeArr, $day);

            }

        }

        $day_minus = date('Y-m-d', strtotime($re['date'] . ' -1 day'));

    }

    echo json_encode($timeArr, 320);


}


/*

    CUSTOM FUNCTION TO DRAW PRICELIST TABLE BY ROOMID

*/

// add_action('wp_ajax_getAvailByRoomIDTest', 'getAvailByRoomIDTest');

// add_action('wp_ajax_nopriv_getAvailByRoomIDTest', 'getAvailByRoomIDTest');


// function getAvailByRoomIDTest() {


//     require_once(BEDS_DIR . '/includes/class.action.php');

//     $act = new \beds_booking\Action_beds_booking();


//     global $wpdb;


//     // Retrieve data from AJAX request

//     $product_id = $_POST['product_id'];

//     $date_start = $_POST['date_start'];

//     $date_end = $_POST['date_end'];


//     // Get room ID associated with the product

//     $room = get_post_meta($product_id, '_product_beds_id', true);


//     // Initialize arrays for each case

//     $case1_available_dates = [];

//     $case2_notavailable_dates = [];

//     $case3_notavailable_dates = [];


//     // Query to get dates within the given range and categorize them based on availability and booking status

//     $sql = $wpdb->prepare("

//         SELECT `date`, `avaliable`, `isBooked`

//         FROM `beds_calendar`

//         WHERE `roomId` = %d

//         AND `date` BETWEEN %s AND %s

//     ", $room, $date_start, $date_end);


//     // die();


//     $results = $wpdb->get_results($sql, ARRAY_A);


//     // Process results and categorize dates

//     foreach ($results as $result) {

//         $date = $result['date'];

//         $available = $result['avaliable'];

//         $isBooked = $result['isBooked'];


//         if ($available == 1 && $isBooked == 0) {

//             $case1_available_dates[] = $date;

//         } elseif ($available == 1 && $isBooked == 1) {

//             $case2_notavailable_dates[] = $date;

//         } elseif ($available == 0 && $isBooked == 0) {

//             $case3_notavailable_dates[] = $date;

//         }

//     }


//     // echo '<pre>'; print_r($case1_available_dates); echo '</pre>'; die();


//     /*

//         Function to split dates into week chunks (Sunday to Saturday)

//     */

//     function split_into_weeks($dates) {

//         $weeks = [];

//         $week = [];


//         foreach ($dates as $date) {

//             $day_of_week = date('w', strtotime($date));


//             if ($day_of_week == 0 && !empty($week)) {

//                 // If it's Sunday and we have collected dates, start a new week

//                 $weeks[] = $week;

//                 $week = [];

//             }


//             $week[] = $date;


//             if ($day_of_week == 6) {

//                 // If it's Saturday, finalize the current week

//                 $weeks[] = $week;

//                 $week = [];

//             }

//         }


//         // Add any remaining dates as the last week

//         if (!empty($week)) {

//             $weeks[] = $week;

//         }


//         return $weeks;

//     }


//     // Split each array into week chunks

//     $case1_available_weeks = split_into_weeks($case1_available_dates);

//     $case1_available_count = count($case1_available_dates);


//     $case2_notavailable_weeks = split_into_weeks($case2_notavailable_dates);

//     $case2_notavailable_count = count($case2_notavailable_dates);


//     $case3_notavailable_weeks = split_into_weeks($case3_notavailable_dates);

//     $case3_notavailable_count = count($case3_notavailable_dates);


//     // Prepare response with counts

//     /*$response = [

//         'case1_available_weeks' => $case1_available_weeks,

//         'case1_available_count' => count($case1_available_dates),

//         'case2_notavailable_weeks' => $case2_notavailable_weeks,

//         'case2_notavailable_count' => count($case2_notavailable_dates),

//         'case3_notavailable_weeks' => $case3_notavailable_weeks,

//         'case3_notavailable_count' => count($case3_notavailable_dates)

//     ];*/


//     // Return response as JSON

//     // echo json_encode($response);


//     // echo '<pre>'; print_r($case1_available_weeks); echo '</pre>'; die();


//     $tableDiv ='<table id="inner_prc_tb">

//                 <thead>

//                     <tr>

//                         <th>Vecka</th> <!-- Week -->

//                         <th>Datum</th> <!-- Date -->

//                         <th>Dagar</th> <!-- Days -->

//                         <th>Pris</th> <!-- Award -->

//                     </tr>

//                 </thead>';

//     $tableDiv .='<tbody>';


//     $cnt = 1;


//     if( !empty($case1_available_weeks) ) {


//         for ($i = 0; $i < count($case1_available_weeks); $i++) {


//             $firstDate = $case1_available_weeks[$i][0];

//             $lastDate = $case1_available_weeks[$i][count($case1_available_weeks[$i]) - 1];


//             $formattedFirstDate = date('d.m', strtotime($firstDate));

//             $formattedLastDate = date('d.m', strtotime($lastDate));


//             $dateRange = $formattedFirstDate . '-' . $formattedLastDate;


//             $noOfDays = count($case1_available_weeks[$i]);


//             $price_by_period = $act->getRoomPriceByDays($noOfDays,$firstDate, $lastDate, $product_id);


//             $tableDiv .= '<tr>

//                             <td>Vecka ' . $cnt . '</td>

//                             <td>' . $dateRange . '</td>

//                             <td>' . count($case1_available_weeks[$i]) . '</td>

//                             <td>' . $price_by_period . '</td>

//                         </tr>';


//             $cnt++;

//         }

//     } else {


//         $tableDiv .= '<tr>

//                             <td colspan="4" style="text-align: center;">Sorry, Price not available.</td>

//                         </tr>';

//     }


//     $tableDiv .='</tbody>

//             </table>';


//     // send response

//     echo $tableDiv;


//     wp_die();

// }


function getAvailByRoomIDexcl($post_id)
{

    global $wpdb;

    $room = get_post_meta($post_id, '_product_beds_id', true);

    $sql_asc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` asc";

    $sql_desc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` desc";

    $res_asc = $wpdb->get_results($sql_asc, ARRAY_A);

    $res_desc = $wpdb->get_results($sql_desc, ARRAY_A);


    $exclude_arr = [];

    $day_plus = 0;

    foreach ($res_asc as $re) {

        $day = date('Y-m-d', strtotime($re['date']));

        if ($day != $day_plus) {

            $day_w = date('w', strtotime($re['date']));

            if ($day_w == '4' || $day_w == '0') {

                array_push($exclude_arr, $day);

            }

        }

        $day_plus = date('Y-m-d', strtotime($re['date'] . ' +1 day'));

    }


    $day_minus = 0;

    foreach ($res_desc as $re) {

        $day = date('Y-m-d', strtotime($re['date']));

        if ($day != $day_minus) {

            $day_w = date('w', strtotime($re['date']));

            if ($day_w == '4' || $day_w == '0') {

                array_push($exclude_arr, $day);

            }

        }

        $day_minus = date('Y-m-d', strtotime($re['date'] . ' -1 day'));

    }

    return $exclude_arr;

}


function getPeriodByRoomID()
{

    global $wpdb;

    $room = get_post_meta($_POST['product_id'], '_product_beds_id', true);

    $sql = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0";

    $res = $wpdb->get_results($sql, ARRAY_A);

    $timeArr = array();

    foreach ($res as $re) {

        array_push($timeArr, $re['date']);

    }


    $excl = getAvailByRoomIDexcl($_POST['product_id']);


    foreach ($timeArr as $key => $value) {

        if (in_array($value, $excl)) {

            unset($timeArr[$key]);

        }

    }


    echo json_encode($timeArr, 320);


}

add_action('wp_ajax_getPeriodByRoomID', 'getPeriodByRoomID');

add_action('wp_ajax_nopriv_getPeriodByRoomID', 'getPeriodByRoomID');


function filter_products()
{

    $params1 = explode('/', $_POST['param1']);

    $params2 = explode('/', $_POST['param2']);

    $sovrum = trim($_POST['sovrum']);

    $skidlift = trim($_POST['skidlift']);

    $sovrum = intval($sovrum);

    $skidlift = intval($skidlift);

    $date_start = get_option('date_start');

    $date_end = get_option('date_end');

    $date_period1 = new DateTime($date_start);

    $date_period2 = new DateTime($date_end);

    $period1 = $date_period1->format('d.m');

    $period2 = $date_period2->format('d.m');

    $datetime1 = date_create($date_start);

    $datetime2 = date_create($date_end);

    $interval = date_diff($datetime1, $datetime2);

    $days = $interval->d;

    $adult = get_option('adult');

    $area = get_option('area');

    $product_ids = get_option('product_ids');

    $product_ids_no_avaliable = get_option('product_ids_no_avaliable');

    $url = $_POST['url'];


    require_once(BEDS_DIR . '/includes/class.action.php');

    $act = new \beds_booking\Action_beds_booking();

    $meta_arr = [];

    $ajax = true;

    // global $wpdb;

    // $table = $wpdb->prefix . 'postmeta';

    // $params = array_merge($params1,$params2);

    // $product_ids_filtred = [];

    // var_dump($params);

    // foreach($params as $param){

    //     foreach($product_ids as $product_id){

    //         $results = $wpdb->get_results( "SELECT post_id FROM $table WHERE post_id = '$product_id' AND meta_key in ('_product_bastu','_product_diskmaskin','_product_hundtillåtet','_product_tvättmaskin')");

    //         if($results[0]->post_id != NULL){

    //             array_push($product_ids_filtred, $results[0]->post_id);

    //         }

    //     }

    // }

    // var_dump($product_ids_filtred);

    $all_pars = [];

    $get_par1 = [];

    $get_par2 = [];

    foreach ($params1 as $param1) {

        if ($param1) {

            $value = array('key' => $param1, 'value' => 'yes', 'compare' => '=');

            array_push($meta_arr, $value);

        }

    }

    foreach ($params2 as $param2) {

        if ($param2) {

            $value = array('key' => $param2, 'value' => 'yes', 'compare' => '=');

            array_push($meta_arr, $value);

        }

    }

    if ($sovrum) {

        $sovrum_value = array('key' => '_product_sovrum', 'value' => $sovrum, 'type' => 'numeric', 'compare' => '>=');

        array_push($meta_arr, $sovrum_value);

    }

    if ($skidlift) {

        $skidlift_value = array('key' => '_product_skidlift', 'value' => $skidlift, 'type' => 'numeric', 'compare' => '<=');

        array_push($meta_arr, $skidlift_value);

    }

    $params1_string = implode(";", $params1);

    $params2_string = implode(";", $params2);

    $get_pars = 'date_start=' . $date_start . '&date_end=' . $date_end . '&adult=' . $adult . '&sovrum=' . $sovrum . '&skidlift=' . $skidlift . '&parstring1=' . $params1_string . '&parstring2=' . $params2_string;

//     var_dump($meta_arr);

//     die();

    $per_pages = 10;

    $current_page = 1;

//    if(isset($_GET['npage'])){

//        $current_page = $_GET['npage'];

//    }else{

//        $current_page = 1;

//    }

//

//    $get = $_GET;

//    $get_parrs = [];

//    foreach($get as $key => $value){

//        array_push($get_parrs,$key.'='.$value);

//    }

//    $get_parrs = implode('&',$get_parrs);

//    $ajax = false;


    require_once BEDS_DIR . '/tamplates/products.php';


    // $result['sovrum'] = trim($_POST['sovrum']);

    // $result['skidlift'] = trim($_POST['skidlift']);

    // $result['param1'] = explode('/', $_POST['param1']);

    // $result['param2'] = explode('/', $_POST['param2']);


    // echo json_encode($result, 320);


    wp_die();


} //endfunction


add_action('wp_ajax_filter_products', 'filter_products');

add_action('wp_ajax_nopriv_filter_products', 'filter_products');


function resolvedManualOrder()

{

    if (isset($_POST['order_id'])) {


        $val = '[' . json_encode(array('success' => true)) . ']';


        update_post_meta($_POST['order_id'], 'request_api_res', $val);

    }

}

add_action('wp_ajax_resolvedManualOrder', 'resolvedManualOrder');

add_action('wp_ajax_nopriv_resolvedManualOrder', 'resolvedManualOrder');


function getAllowDatesLitepicker()

{

    global $wpdb;

    $dateAllisB = "select `date` from `beds_calendar` where isBooked=1";

    $dateAllisB = $wpdb->get_results($dateAllisB, ARRAY_A);


    $r = array();

    foreach ($dateAllisB as $item) {

        array_push($r, $item['date']);

    }


    $dateAll = "select `date` from `beds_calendar`";

    $dateAll = $wpdb->get_results($dateAll, ARRAY_A);


    foreach ($dateAll as $key => $item) {

        if (in_array($item['date'], $r)) {

            unset($dateAll[$key]);

        }

    }

    $fin = array();


    foreach ($dateAll as $item) {

        if (!in_array($item['date'], $fin)) {

            array_push($fin, $item['date']);

        }

    }

    /*$testAr = array(
                   'r' => $r,
                   'fin' => $fin,
                 );*/

    // echo json_encode($testAr);
    echo json_encode($fin);

    wp_die();

}

add_action('wp_ajax_getAllowDatesLitepicker', 'getAllowDatesLitepicker');

add_action('wp_ajax_nopriv_getAllowDatesLitepicker', 'getAllowDatesLitepicker');


//function getAllowDatesLitepicker() {

//  global $wpdb;

//  $query = "SELECT `date` FROM `beds_calendar` WHERE `isBooked` = 0";

//  $dates = $wpdb->get_results($query, ARRAY_A);

//

//  $fin = array_map(function($item) {

//      return $item['date'];

//  }, $dates);

//    $fin = array_values(array_unique($fin));

//  echo json_encode($fin);

//    wp_die();

//

////  update_option('available_dates_litepicker', json_encode($fin));

//}


/*function post_getAllowDatesLitepicker() {

    // add data renew

//    global $wpdb;

//    $query = "SELECT `date` FROM `beds_calendar` WHERE `isBooked` = 0";

//    $dates = $wpdb->get_results($query, ARRAY_A);

//

//    $fin = array_map(function($item) {

//        return $item['date'];

//    }, $dates);

//$fin = array_values(array_unique($fin));

// end data renew



    //old

//    update_option('available_dates_litepicker', json_encode($fin));

  $availableDates = get_option('available_dates_litepicker');

  echo $availableDates;



//    echo json_encode($fin);

  wp_die();

}

add_action('wp_ajax_getAllowDatesLitepicker', 'post_getAllowDatesLitepicker');

add_action('wp_ajax_nopriv_getAllowDatesLitepicker', 'post_getAllowDatesLitepicker');*/


add_action('wp_ajax_getHouseList', 'getHouseList');

add_action('wp_ajax_nopriv_getHouseList', 'getHouseList');

function getHouseList()

{

    global $wpdb;

    require_once(BEDS_DIR . '/includes/class.action.php');

    $act = new \beds_booking\Action_beds_booking();


    $res = $wpdb->get_results('select roomId,nameRoom,id,latitude,longitude from `beds_properties`', ARRAY_A);


    foreach ($res as $key => $val) {

        $t = $wpdb->prefix . 'postmeta';

        $id = $val['roomId'];

        $postID = $wpdb->get_var("select post_id from $t where meta_key='_product_beds_id' and meta_value=$id");

        $res[$key]['img'] = get_the_post_thumbnail_url($postID, 'post-thumbnail');


        $price_by_period = $act->getRoomPriceByDays($_GET['d'], $_GET['d_s'], $_GET['d_e'], $postID);

        $res[$key]['productID'] = $postID;

        $res[$key]['price'] = $price_by_period;


        /*

         *             <li class="icon-gray"><span><?php echo $_product_boyta; ?> m<sup>2</sup></span></li>

                */

        ob_start();

        $hundtillatet = get_post_meta($postID, '_product_hundtillåtet', true);

        $wi_fi = get_post_meta($postID, '_product_wi_fi', true);

        $bastu = get_post_meta($postID, '_product_bastu', true);

        $oppen_spis = get_post_meta($postID, '_product_oppen_spis', true);

        $sovrum = get_post_meta($postID, '_product_sovrum', true);

        $_product_boyta = get_post_meta($postID, '_product_boyta', true);

        $peoples = intval(get_post_meta($postID, '_product_peoples', true));

        $child = (int)get_post_meta($postID, '_children', true);

        ?>

        <ul class="search-item-icons" style="line-height: 2em;">

            <li class="icon-gray"><span><?php echo $peoples . '&nbsp;';
                    if ($child != 0 or !empty($child)) {
                        echo '(+' . $child . ')&nbsp;';
                    } ?></span><i class="fas fa-user-friends"></i></li>

            <li class="icon-gray"><span><?php echo $sovrum; ?></span><img style="vertical-align: bottom;height:19px;"
                                                                          src="<?php echo BEDS_URL; ?>assets/svg/hotel-bed.svg">
            </li>

            <?php


            if ($hundtillatet) {

                echo '<li class="icon-red"><svg style=" margin: -4px 0; " width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">

                <path d="M15.1862 12.2328L15.1834 12.2403H10.9831C10.3734 12.2403 9.80583 12.4238 9.33241 12.7382H7.99222C7.44296 12.7382 6.99611 12.2915 6.99611 11.7423C6.99611 11.1932 7.44296 10.7464 7.99222 10.7464H9.48638C9.76143 10.7464 9.98443 10.5234 9.98443 10.2484C9.98443 9.97344 9.76143 9.75049 9.48638 9.75049H7.99222C6.89371 9.75049 6 10.644 6 11.7423C6 12.8406 6.89371 13.7341 7.99222 13.7341H8.39448C8.13881 14.1745 7.99222 14.6857 7.99222 15.2306V21.469C7.99222 21.744 8.21521 21.967 8.49027 21.967H10.4825C10.7575 21.967 10.9805 21.744 10.9805 21.469V17.2666L15.5294 17.6751V21.5022C15.5294 21.7772 15.7524 22.0002 16.0275 22.0002H18.0197C18.2948 22.0002 18.5178 21.7772 18.5178 21.5022V16.7712L19.1315 13.7032L15.1862 12.2328Z" fill="#F2A4A9"/>

                <path d="M22.5016 9.25222H20.9987C20.9179 8.4921 20.2744 7.89114 19.4817 7.89114H18.646L18.3411 6.39727C18.2413 5.90864 17.5605 5.85546 17.3863 6.32311L15.5332 11.2991L19.3287 12.7138L19.4235 12.2399H20.5094C21.8825 12.2399 22.9997 11.123 22.9997 9.75017C22.9997 9.47517 22.7767 9.25222 22.5016 9.25222Z" fill="#F2A4A9"/>

                </svg></li>';

            }

            if ($wi_fi) {

                echo '<li class="icon-red"><svg style=" margin: -4px 0; " width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">

                <path d="M21.8749 14.0016C21.7272 14.0016 21.5857 13.9439 21.4813 13.8411C19.6165 12.0052 17.1372 10.9941 14.5 10.9941C11.8628 10.9941 9.38352 12.0052 7.51874 13.8411C7.41435 13.9439 7.27278 14.0017 7.12516 14.0017C6.97754 14.0017 6.83596 13.9439 6.73158 13.8411L5.16304 12.2969C4.94565 12.0829 4.94565 11.7359 5.16304 11.5219C6.42335 10.2811 7.89306 9.32029 9.5314 8.6662C11.1136 8.0345 12.7853 7.71423 14.5 7.71423C16.2147 7.71423 17.8864 8.03454 19.4686 8.6662C21.1069 9.32029 22.5767 10.2811 23.837 11.5219C24.0543 11.7359 24.0543 12.0829 23.837 12.2969L22.2685 13.8411C22.1641 13.9439 22.0225 14.0016 21.8749 14.0016Z" fill="#F2A4A9"/>

                <path d="M18.6246 17.189C18.4769 17.189 18.3353 17.1312 18.231 17.0284C17.2341 16.047 15.9087 15.5064 14.4988 15.5064C13.089 15.5064 11.7636 16.047 10.7667 17.0284C10.6623 17.1312 10.5208 17.189 10.3731 17.189C10.2255 17.189 10.0839 17.1312 9.97952 17.0284L8.41105 15.4842C8.1937 15.2702 8.1937 14.9232 8.41109 14.7092C10.0372 13.1082 12.1992 12.2266 14.4989 12.2266C16.7985 12.2266 18.9606 13.1082 20.5867 14.7092C20.804 14.9232 20.804 15.2702 20.5867 15.4842L19.0182 17.0284C18.9138 17.1312 18.7722 17.189 18.6246 17.189Z" fill="#F2A4A9"/>

                <path d="M14.5006 21.2858C13.2283 21.2858 12.1934 20.2769 12.1934 19.0368C12.1934 17.7967 13.2284 16.7878 14.5006 16.7878C15.7728 16.7878 16.8078 17.7967 16.8078 19.0368C16.8078 20.2769 15.7728 21.2858 14.5006 21.2858Z" fill="#F2A4A9"/>

                </svg></li>';

            }

            if ($bastu) {

                echo '<li class="icon-red"><img style="vertical-align: inherit;" src="' . BEDS_URL . 'assets/img/66.svg"></li>';

            }


            if ($oppen_spis) {

                echo '<li class="icon-red" ><img style="vertical-align: inherit;width:18px;" src="' . BEDS_URL . 'assets/img/7.svg"></li>';

            }

            ?>

        </ul>

        <?php


        $icons = ob_get_clean();

        $res[$key]['icons'] = $icons;

        $res[$key]['link'] = get_the_permalink($postID) . '?date_start=' . $_GET['d_s'] . '&date_end=' . $_GET['d_e'] . '&adult=&number-adult=' . $_GET['n_a'] . '&number-child=' . $_GET['c'];


    }

    echo json_encode($res);

}


add_action('wp_ajax_setFavSess', 'setFavSess');

add_action('wp_ajax_nopriv_setFavSess', 'setFavSess');

function setFavSess()

{

    session_start();

    if (!empty($_SESSION['wishlist'])) {

        $ids = $_SESSION['wishlist'];

        $list = explode(',', $ids);

        if (in_array($_POST['product_id'], $list)) {

            if (($key = array_search($_POST['product_id'], $list)) !== false) {

                unset($list[$key]);

            }

            $ids = implode(',', $list);

            $_SESSION['wishlist'] = $ids;

            echo 'DEL';

        } else {

            $ids .= ',' . $_POST['product_id'];

            $_SESSION['wishlist'] = $ids;

            echo "ADD";

        }

    } else {

        $_SESSION['wishlist'] = $_POST['product_id'];


        echo "ADD";

    }


}


add_action('wp_ajax_delFavSess', 'delFavSess');

add_action('wp_ajax_nopriv_delFavSess', 'delFavSess');

function delFavSess()

{

    session_start();

    if (!empty($_SESSION['wishlist'])) {

        $ids = $_SESSION['wishlist'];

        $list = explode(',', $ids);

        if (in_array($_POST['product_id'], $list)) {

            if (($key = array_search($_POST['product_id'], $list)) !== false) {

                unset($list[$key]);

            }

            $ids = implode(',', $list);

            $_SESSION['wishlist'] = $ids;

            echo 'DEL';

        } else {

            echo 'ERR';

        }


    }


}

add_action('wp_ajax_update_personsA', 'update_personsA');
add_action('wp_ajax_nopriv_update_personsA', 'update_personsA');
function update_personsA()
{

    $cart_item_key = trim($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    foreach (WC()->cart->get_cart() as $cart_item_id => $cart_item) {
        if ($cart_item_key == $cart_item_id) {
            $cart_item['persons_adult'] = $quantity;
            WC()->cart->cart_contents[$cart_item_key] = $cart_item;
        }
    }
    WC()->cart->set_session();
    WC()->cart->calculate_totals();
}

add_action('wp_ajax_update_personsC', 'update_personsC');
add_action('wp_ajax_nopriv_update_personsC', 'update_personsC');
function update_personsC()
{

    $cart_item_key = trim($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    foreach (WC()->cart->get_cart() as $cart_item_id => $cart_item) {
        if ($cart_item_key == $cart_item_id) {
            $cart_item['persons_child'] = $quantity;
            WC()->cart->cart_contents[$cart_item_key] = $cart_item;
        }
    }
    WC()->cart->set_session();
    WC()->cart->calculate_totals();
}

add_action('wp_ajax_update_options', 'update_options');
add_action('wp_ajax_nopriv_update_options', 'update_options');
function update_options()
{

    $cart_item_key = trim($_POST['cart_id']);
    $options = trim($_POST['options']);
    $options_arr = explode(";", $options);

//     $quantity = intval($_POST['quantity']);

    if (isset($array['Two'])) {
        unset($array['Two']);
    }

    delete_options($cart_item_key);

    foreach (WC()->cart->get_cart() as $cart_item_id => $cart_item) {
        if ($cart_item_key == $cart_item_id) {
            $sum = 0;
            foreach ($options_arr as $item) {
                $item_arr = explode("|", $item);
                $item_price = floatval($item_arr[1]);
                $cart_item[$item_arr[0]] = $item_price;
                $sum = $sum + $item_price;
            }
            $cart_item['options_sum'] = $sum;
            WC()->cart->cart_contents[$cart_item_key] = $cart_item;
        }
    }
    WC()->cart->set_session();
    WC()->cart->calculate_totals();
}
//

//add_action('wp_ajax_depositOrderAdds','depositOrderAdds');

//add_action('wp_ajax_nopriv_depositOrderAdds','depositOrderAdds');

//function depositOrderAdds()

//{

//

//    //============================================= deposit order ======================================

//

//

//        $order_data                        = array();

//        $order_data[ 'post_type' ]         = 'shop_order';

//        $order_data[ 'post_status' ]       = 'wc-' . apply_filters( 'woocommerce_default_order_status', 'pending' );

//        $order_data[ 'ping_status' ]       = 'closed';

//        $order_data[ 'post_author' ]       = 1;

//        $order_data[ 'post_password' ]     = uniqid( 'order_' );

//        $order_data[ 'post_title' ]        = sprintf( __( 'Order &ndash; %s', 'woocommerce' ), strftime( _x( '%b %d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'woocommerce' ), strtotime( $post_date ) ) );

//        $order_data[ 'post_parent' ]       = 0; // parent post id

//        $order_data[ 'post_content' ]      = "";

//        $order_data[ 'comment_status' ]    = "open";

//        $order_data[ 'post_name' ]         = sanitize_title( sprintf( __( 'Order &ndash; %s', 'woocommerce' ), strftime( _x( '%b %d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'woocommerce' ), strtotime( $post_date) ) ) );

////        $order_deposit_id = wp_insert_post( apply_filters( 'woocommerce_new_order_data', $order_data ), true );

//

//

//    //=============================================== main order ==============================================

//

////        $order_main_id = wp_insert_post( apply_filters( 'woocommerce_new_order_data', $order_data ), true );

//

//    //================================================ SET DATA TO DEPOSIT ==================================

//

////    var_dump($_POST['cart_id']);

////

////    foreach ( WC()->cart->get_cart() as $cart_item ){

////        var_dump($cart_item);

////    }

//}

//

//add_action('wp_ajax_set_order_to_deposit','set_order_to_deposit');

//add_action('wp_ajax_nopriv_set_order_to_deposit','set_order_to_deposit');

//function set_order_to_deposit($cart)

//{

//    session_start();

//    if ( is_admin() && ! defined( 'DOING_AJAX' ) )

//        return;

//

////    var_dump($cart);

////    if ( !WC()->cart->is_empty() ):

////        WC()->cart->set_totals($_SESSION['deposit']);

////        WC()->cart->calculate_totals();

////    endif;

//}

//add_action('wp_ajax_set_order_to_total','set_order_to_total');

//add_action('wp_ajax_nopriv_set_order_to_total','set_order_to_total');

//function set_order_to_total($cart_object)

//{

//    session_start();

//    if ( is_admin() && ! defined( 'DOING_AJAX' ) )

//        return;

//

////    if ( !WC()->cart->is_empty() ):

////        $cart_object->cart_contents_total = (float)$_SESSION['total'];

////

////    endif;

//}

