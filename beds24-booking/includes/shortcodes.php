<?php
/**
 * add shortcode for Litepicker
 */

add_shortcode('litepicker','litepicker');
function litepicker()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script",'',[],false,false);
    wp_enqueue_script("beds-script_home-script",'',[],false,false);

    require_once(BEDS_DIR . '/views/start-form.php');

    return ob_get_clean();

}

add_shortcode('litepicker_list','litepicker_list');
function litepicker_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script",'',[],false,false);
    wp_enqueue_script("beds-register-script");

    $args = array( 'hide_empty' => 0 );
    $terms = get_terms('product_tag', $args );

    require_once(BEDS_DIR . '/views/res-form.php');

    return ob_get_clean();

}

//==================================================

add_shortcode('litepicker_res_list_new','litepicker_res_list_new');
function litepicker_res_list_new()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script",'',[],false,false);
    wp_enqueue_script("beds-register-script");


    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();

    global $wpdb;
    $table = 'beds_calendar';
    if(!empty($_GET['date_start'])){
        $date_start = $_GET['date_start'];
        $dateTime = DateTime::createFromFormat('Y-m-d', $date_start) ?:
            DateTime::createFromFormat('Y/m/d', $date_start) ?:
                DateTime::createFromFormat('d/m/Y', $date_start) ?:
                    DateTime::createFromFormat('m/d/Y', $date_start) ?:
                        DateTime::createFromFormat('d-m-Y', $date_start) ?:
                            DateTime::createFromFormat('m-d-Y', $date_start);
        $date_start = $dateTime->format('Y-m-d');
    }else{
        $date_start = '';
    }
    if(!empty($_GET['date_end'])){
        $date_end = $_GET['date_end'];
        $dateTime1 = DateTime::createFromFormat('Y-m-d', $date_end) ?:
            DateTime::createFromFormat('Y/m/d', $date_end) ?:
                DateTime::createFromFormat('d/m/Y', $date_end) ?:
                    DateTime::createFromFormat('m/d/Y', $date_end) ?:
                        DateTime::createFromFormat('d-m-Y', $date_end) ?:
                            DateTime::createFromFormat('m-d-Y', $date_end);
        $date_end = $dateTime1->format('Y-m-d');
    }else{
        $date_end = '';
    }

    $results = $wpdb->get_results( "SELECT roomId FROM $table WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY roomId");
    $meta_query_array_avaliable = [];
    $meta_query_array_noavaliable = [];
    $i=0;
    foreach ($results as $result) {
        $room_id = intval($result->roomId);
        $check = check_date_noavaible($room_id,$date_start,$date_end);
        $avail = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$room_id' and `date` = '$date_start'");
        $availEnd = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$room_id' and `date` = '$date_end'");
        if(!empty($check) ){
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");

            array_push($meta_query_array_noavaliable, $products);
        }else{
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");
            if ($avail == 0 or $availEnd == 0){

                array_push($meta_query_array_noavaliable, $products);

            } else{
                $datetime1 = date_create($date_start);
                $datetime2 = date_create($date_end);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->days;
                $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $products[0]->post_id);
                if ($price_by_period == 0){
                    array_push($meta_query_array_noavaliable, $products);
                } else {
                    array_push($meta_query_array_avaliable, $products);
                }

            }
        }
        $i++;
    }
    $product_ids = [];
    $product_ids_no_avaliable = [];

    foreach($meta_query_array_avaliable as $product){
        if (isset($product[0])) {
            $product_id = intval($product[0]->post_id);
            array_push($product_ids, $product_id);
        }
    }
    foreach($meta_query_array_noavaliable as $product){
        if (isset($product[0])){
            $product_id = intval($product[0]->post_id);
            array_push($product_ids_no_avaliable,$product_id);
        }

    }
    if(!empty($meta_query_array_avaliable)){
        // Creates DateTime objects
        $date_period1 = new DateTime($date_start);
        $date_period2 = new DateTime($date_end);
        $period1 = $date_period1->format('d.m');
        $period2 = $date_period2->format('d.m');
        $datetime1 = date_create($date_start);
        $datetime2 = date_create($date_end);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->days;
        $animals = $_GET['animals'] ?? '';
        $adult = $_GET['number-adult'] ?? 1;
        $child = $_GET['number-child'] ?? 0;

        $table = $wpdb->prefix . 'postmeta';
        $maxAdult = $wpdb->get_var("select max(meta_value) as maxadult from $table WHERE meta_key='_product_peoples'");
//        $maxChild = $wpdb->get_var("select max(meta_value) as maxadult from $table WHERE meta_key='_children'");
        $results_maxChild = $wpdb->get_results("
    SELECT meta_value 
    FROM $table 
    WHERE meta_key='_children'
", ARRAY_A);

        $maxChild = 0;

        foreach ($results_maxChild as $row) {
            $value = $row['meta_value'];
            if (is_numeric($value)) {
                $maxChild = max($maxChild, (int)$value);
            }
        }

//        echo $maxChild;
//var_dump($maxAdult);
//        var_dump($maxChild);
//        update_option('product_ids',$product_ids);
//        update_option('product_ids_no_avaliable',$product_ids_no_avaliable);
//        update_option('date_start',$date_start);
//        update_option('date_end',$date_end);
//        update_option('adult',$adult);
//        update_option('child',$child);
        if(isset($_GET['area'])){
            update_option('area',$_GET['area']);
            $area = $_GET['area'];
        }else{
            update_option('area','');
            $area = '';
        }
        ?>
        <div class="hotels">
            <?php

            $get = $_GET;


            $get_parrs = [];
            foreach($get as $key => $value){
                array_push($get_parrs,$key.'='.$value);
            }
            $get_parrs = implode('&',$get_parrs);
            $ajax = false;

            if(isset($_GET['parstring1'])){
                $params1 = explode(';', $_GET['parstring1']);
            }else{
                $params1 = '';
            }

            if(isset($_GET['parstring2'])){
                $params2 = explode(';', $_GET['parstring2']);
            }else{
                $params2 = '';
            }

            if(isset($_GET['sovrum'])){
                $sovrum = trim($_GET['sovrum']);
            }else{
                $sovrum = '';
            }

            if(isset($_GET['skidlift'])){
                $skidlift = trim($_GET['skidlift']);
            }else{
                $skidlift = '';
            }

            $meta_arr = [];

            // Додавання умов з $params1
            if (is_array($params1)) {
                foreach ($params1 as $param1) {
                    if ($param1) {
                        $meta_arr[] = ['key' => $param1, 'value' => 'yes', 'compare' => '='];
                    }
                }
            }

            // Умова для тварин
            if (isset($_GET['animals']) || $animals == 'on') {
                $meta_arr[] = ['key' => '_product_hundtillåtet', 'value' => 'yes', 'compare' => '='];
            }

            // Умова для дітей і дорослих
           /* if ($child > 0) {
//                if ($child <= $maxChild) {
                    $meta_arr[] = ['key' => '_children', 'value' => [$child, $maxChild], 'type' => 'numeric', 'compare' => 'BETWEEN'];
//                } else {
//                    $meta_arr[] = ['key' => '_children', 'value' => $maxChild, 'type' => 'numeric', 'compare' => '='];
//                    $child = $child - $maxChild;
//                    $adult = $adult + $child;
//                }

                $meta_arr[] = ['key' => '_product_peoples', 'value' => [$adult+$child, $maxAdult], 'type' => 'numeric', 'compare' => 'BETWEEN'];
            } elseif ($adult > 0) {
                $meta_arr[] = ['key' => '_product_peoples', 'value' => $adult, 'type' => 'numeric', 'compare' => '>='];
            }*/

           /* if ($child > 0) {
                // Основна умова для будинків, де є поле `_children`
                $meta_arr[] = [
                    'relation' => 'OR',
                    [
                        'relation' => 'AND',
                        [
                            'key' => '_children',
                            'value' => [$child, $maxChild],
                            'type' => 'numeric',
                            'compare' => 'BETWEEN'
                        ],
                        [
                            'key' => '_product_peoples',
                            'value' => [$adult + $child, $maxAdult],
                            'type' => 'numeric',
                            'compare' => 'BETWEEN'
                        ],
                    ],
                    // Для будинків, де `_children` порожнє
                    [
                        'relation' => 'AND',
                        [
                            'key' => '_children',
                            'value' => ['a:0:{}', ''], // Порожні значення
                            'compare' => 'IN'
                        ],
                        [
                            'key' => '_product_peoples',
                            'value' => [$adult + $child, $maxAdult],
                            'type' => 'numeric',
                            'compare' => 'BETWEEN'
                        ],
                    ]
                ];
            } elseif ($adult > 0) {
                // Умова тільки для дорослих
                $meta_arr[] = [
                    'key' => '_product_peoples',
                    'value' => $adult,
                    'type' => 'numeric',
                    'compare' => '>='
                ];
            }*/


            // Додавання умов з $params2
            if (is_array($params2)) {
                foreach ($params2 as $param2) {
                    if ($param2) {
                        $meta_arr[] = ['key' => $param2, 'value' => 'yes', 'compare' => '='];
                    }
                }
            }

            // Умова для кількості спальних кімнат
            if ($sovrum) {
                $meta_arr[] = ['key' => '_product_sovrum', 'value' => intval($sovrum), 'type' => 'numeric', 'compare' => '>='];
            }

            // Умова для відстані до лижних підйомників
            if ($skidlift) {
                $meta_arr[] = ['key' => '_product_skidlift', 'value' => intval($skidlift), 'type' => 'numeric', 'compare' => '<='];
            }


            //            $meta_arr = [];
//            $meta_arr_fix = [];
//            if(is_array($params1)){
//                foreach ($params1 as $param1) {
//                    if($param1){
//                        $value = array('key' => $param1,'value' => 'yes','compare' => '=');
//                        array_push($meta_arr, $value);
//                        array_push($meta_arr_fix, $value);
//                    }
//                }
//            }
//            if (isset($_GET['animals']) or $animals=='on'){
//                $value = array('key' => '_product_hundtillåtet','value' => 'yes','compare' => '=');
//                array_push($meta_arr, $value);
//                array_push($meta_arr_fix, $value);
//
//            }
//            if ($child != 0){
//                if ($child <= $maxChild){ // 6 + 1 | 5 +2
//                    $child_val = array('key' => '_children','value' => array((int)$child, $maxChild),'type' => 'numeric','compare' => 'BETWEEN');
//                    array_push($meta_arr, $child_val); // 1-2 | 2-2
//
//                    $value = array('key'=>'_product_peoples', 'value'=>array($adult,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
//                    array_push($meta_arr, $value); // 6 | 5
//
////                    $n_a = (int)$adult + (int)$child;
////                    $all_p = array('key'=>'_product_peoples','value'=>array($n_a,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
////                    array_push($meta_arr_fix,$all_p); // 7-10 | 7 -10
//
//                } else {
//
//                    // 6 + 4
//                    $child_val = array('key' => '_children','value' => $maxChild,'type' => 'numeric','compare' => '=');
//                    array_push($meta_arr, $child_val); // 2
//
//                    $child = $child-$maxChild; //
//                    $adult = $adult+$child; //
//                    $value = array('key'=>'_product_peoples', 'value'=>array($adult,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
//                    array_push($meta_arr, $value); //8
//
////                    $n_a = (int)$adult + (int)$child;
////                    $all_p = array('key'=>'_product_peoples','value'=>$n_a,'type' => 'numeric','compare' => '>=');
////                    array_push($meta_arr_fix,$all_p); //10
//                }
//            }
//            else {
//                if ($adult != 0){
//                    $value = array('key'=>'_product_peoples', 'value'=>$adult,'type' => 'numeric','compare' => '>=');
//                    array_push($meta_arr, $value);
//                }
//            }

//            if(is_array($params2)){
//                foreach ($params2 as $param2) {
//                    if($param2){
//                        $value = array('key' => $param2,'value' => 'yes','compare' => '=');
//                        array_push($meta_arr, $value);
//                        array_push($meta_arr_fix, $value);
//
//                    }
//                }
//            }
//            if($sovrum){
//                $sovrum_value = array('key' => '_product_sovrum','value' => intval($sovrum),'type' => 'numeric','compare' => '>=');
//                array_push($meta_arr, $sovrum_value);
//                array_push($meta_arr_fix, $sovrum_value);
//
//            }
//            if($skidlift){
//                $skidlift_value = array('key' => '_product_skidlift','value' => intval($skidlift),'type' => 'numeric','compare' => '<=');
//                array_push($meta_arr, $skidlift_value);
//                array_push($meta_arr_fix, $skidlift_value);
//
//            }
            require_once BEDS_DIR . '/tamplates/products_new.php';

            require_once BEDS_DIR.'/tamplates/map.php';
            ?>
        </div>
        <?php
    }

    return ob_get_clean();
}

//==================================================

add_shortcode('litepicker_res_list','litepicker_res_list');
function litepicker_res_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script",'',[],false,false);
    // wp_enqueue_script("beds-product-script");
    wp_enqueue_script("beds-register-script");

    

    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();

    global $wpdb;
    $table = 'beds_calendar';
//    var_dump($_GET['date_start']);
    if(!empty($_GET['date_start'])){
        $date_start = $_GET['date_start'];
//        $dateTime = DateTime::createFromFormat('m/d/Y', $date_start); // Create DateTime object from MM/DD/YYYY
        $dateTime = DateTime::createFromFormat('Y-m-d', $date_start) ?:
            DateTime::createFromFormat('Y/m/d', $date_start) ?:
                DateTime::createFromFormat('d/m/Y', $date_start) ?:
                    DateTime::createFromFormat('m/d/Y', $date_start) ?:
                        DateTime::createFromFormat('d-m-Y', $date_start) ?:
                            DateTime::createFromFormat('m-d-Y', $date_start);
        $date_start = $dateTime->format('Y-m-d');
    }else{
        $date_start = '';
    }
//    var_dump($date_start);
//    var_dump($_GET['date_end']);
    if(!empty($_GET['date_end'])){
        $date_end = $_GET['date_end'];
//        $dateTime1 = DateTime::createFromFormat('m/d/Y', $date_end); // Create DateTime object from MM/DD/YYYY
        $dateTime1 = DateTime::createFromFormat('Y-m-d', $date_end) ?:
            DateTime::createFromFormat('Y/m/d', $date_end) ?:
                DateTime::createFromFormat('d/m/Y', $date_end) ?:
                    DateTime::createFromFormat('m/d/Y', $date_end) ?:
                        DateTime::createFromFormat('d-m-Y', $date_end) ?:
                            DateTime::createFromFormat('m-d-Y', $date_end);
        $date_end = $dateTime1->format('Y-m-d');
    }else{
        $date_end = '';
    }

//    var_dump($date_start);
//    var_dump($date_end);

    $results = $wpdb->get_results( "SELECT roomId FROM $table WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY roomId"); 
//var_dump($results);
    $meta_query_array_avaliable = [];
    $meta_query_array_noavaliable = [];  
    $i=0;
    foreach ($results as $result) {
        $room_id = intval($result->roomId);
        $check = check_date_noavaible($room_id,$date_start,$date_end);
        $avail = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$room_id' and `date` = '$date_start'");
        $availEnd = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$room_id' and `date` = '$date_end'");
//var_dump($check);
//        var_dump($room_id);
//        var_dump($avail.$availEnd);
        if(!empty($check) ){
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");

            array_push($meta_query_array_noavaliable, $products);
        }else{
//            var_dump($avail.
//$availEnd);
//            die();
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");
            if ($avail == 0 or $availEnd == 0){

                array_push($meta_query_array_noavaliable, $products);

            } else{
                $datetime1 = date_create($date_start);
                $datetime2 = date_create($date_end);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->days;
                $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $products[0]->post_id);
                if ($price_by_period == 0){
                    array_push($meta_query_array_noavaliable, $products);
                } else {
                    array_push($meta_query_array_avaliable, $products);
                }

            }
        }
        $i++;
     } 
    $product_ids = [];
    $product_ids_no_avaliable = []; 

//    echo '<pre>';
//    var_dump($meta_query_array_avaliable);
//    echo '</pre>';

    foreach($meta_query_array_avaliable as $product){
        if (isset($product[0])) {
            $product_id = intval($product[0]->post_id);
            array_push($product_ids, $product_id);
        }
    }
    foreach($meta_query_array_noavaliable as $product){
        if (isset($product[0])){
            $product_id = intval($product[0]->post_id);
            array_push($product_ids_no_avaliable,$product_id);
        }

    }
    if(!empty($meta_query_array_avaliable)){
        // Creates DateTime objects
        $date_period1 = new DateTime($date_start);
        $date_period2 = new DateTime($date_end);
        $period1 = $date_period1->format('d.m');
        $period2 = $date_period2->format('d.m');
        $datetime1 = date_create($date_start);
        $datetime2 = date_create($date_end);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->days;
        $animals = $_GET['animals'] ?? '';
        $adult = $_GET['number-adult'] ?? 1;
        $child = $_GET['number-child'] ?? 0;

        $table = $wpdb->prefix . 'postmeta';
        $maxAdult = $wpdb->get_var("select max(meta_value) as maxadult from $table WHERE meta_key='_product_peoples'");
        $maxChild = $wpdb->get_var("select max(meta_value) as maxadult from $table WHERE meta_key='_children'");

//        if (($adult + $child) <= $maxAdult){
//            if ($child >= $maxChild){
//                $adult = $adult + ($child - $maxChild);
//                $child = $maxChild;
//            } else{
//                $child = 0;
//            }
//        } else {
//            if ($child > $maxChild){
//                $dif = $child - $maxChild;
//                $adult +=$dif;
//                $child = $maxChild;
//            }
//        }




//        $date_end = $_GET['date_end'];
//        $date_start = $_GET['date_start'];
        update_option('product_ids',$product_ids);
        update_option('product_ids_no_avaliable',$product_ids_no_avaliable);
        update_option('date_start',$date_start);
        update_option('date_end',$date_end);        
        update_option('adult',$adult);
        update_option('child',$child);
        if(isset($_GET['area'])){
            update_option('area',$_GET['area']);
            $area = $_GET['area'];
        }else{
            update_option('area','');
            $area = '';
        }
        ?>
        <div class="hotels">
            <?php

                $per_pages = 10;
                if(isset($_GET['npage'])){
                    $current_page = $_GET['npage'];
                    update_option('current_page', $_GET['npage']);
                }else{
                    $current_page = 1;
                }
                $get = $_GET;
                if(isset($_GET['npage'])){
                    $npage = $_GET['npage'];
                }else{
                    $npage = '';    
                }
                
                $get_parrs = [];
                foreach($get as $key => $value){
                    array_push($get_parrs,$key.'='.$value);
                }
                $get_parrs = implode('&',$get_parrs);
                $ajax = false;
                
                if(isset($_GET['parstring1'])){
                    $params1 = explode(';', $_GET['parstring1']);
                }else{
                    $params1 = '';    
                }

                if(isset($_GET['parstring2'])){
                    $params2 = explode(';', $_GET['parstring2']);
                }else{
                    $params2 = '';    
                }
                
                if(isset($_GET['sovrum'])){
                    $sovrum = trim($_GET['sovrum']);
                }else{
                    $sovrum = '';    
                }

                if(isset($_GET['skidlift'])){
                    $skidlift = trim($_GET['skidlift']);
                }else{
                    $skidlift = '';    
                }

                $meta_arr = [];
                $meta_arr_fix = [];
                if(is_array($params1)){
                   foreach ($params1 as $param1) {
                        if($param1){
                            $value = array('key' => $param1,'value' => 'yes','compare' => '=');
                            array_push($meta_arr, $value);
                            array_push($meta_arr_fix, $value);
                        }
                    }    
                }
                if (isset($_GET['animals']) or $animals=='on'){
                    $value = array('key' => '_product_hundtillåtet','value' => 'yes','compare' => '=');
                    array_push($meta_arr, $value);
                    array_push($meta_arr_fix, $value);

                }
//            if ($adult != 0){
//                $value = array('key'=>'_product_peoples', 'value'=>$adult,'type' => 'numeric','compare' => '>=');
//                array_push($meta_arr, $value);
//            }
//            $meta_arr_fix = $meta_arr;
            if ($child != 0){
               if ($child <= $maxChild){ // 6 + 1 | 5 +2
                   $child_val = array('key' => '_children','value' => array((int)$child, $maxChild),'type' => 'numeric','compare' => 'BETWEEN');
                   array_push($meta_arr, $child_val); // 1-2 | 2-2

                   $value = array('key'=>'_product_peoples', 'value'=>array($adult,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
                   array_push($meta_arr, $value); // 6 | 5

                   $n_a = (int)$adult + (int)$child;
                   $all_p = array('key'=>'_product_peoples','value'=>array($n_a,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
                   array_push($meta_arr_fix,$all_p); // 7-10 | 7 -10

//                    var_dump($maxAdult);

               } else {

                   // 6 + 4
//                   var_dump('here');
                   $child_val = array('key' => '_children','value' => $maxChild,'type' => 'numeric','compare' => '=');
                   array_push($meta_arr, $child_val); // 2

                   $child = $child-$maxChild; //
                   $adult = $adult+$child; //
                   $value = array('key'=>'_product_peoples', 'value'=>array($adult,$maxAdult),'type' => 'numeric','compare' => 'BETWEEN');
                   array_push($meta_arr, $value); //8

                   $n_a = (int)$adult + (int)$child;
                   $all_p = array('key'=>'_product_peoples','value'=>$n_a,'type' => 'numeric','compare' => '>=');
                   array_push($meta_arr_fix,$all_p); //10
               }
            }
            else {
                if ($adult != 0){
                    $value = array('key'=>'_product_peoples', 'value'=>$adult,'type' => 'numeric','compare' => '>=');
                    array_push($meta_arr, $value);
                }
            }

//                if ($child != 0){
//                    $child_val = array('key' => '_children','value' => $child,'type' => 'numeric','compare' => '>=');
//                    array_push($meta_arr, $child_val);
//                }
                if(is_array($params2)){
                    foreach ($params2 as $param2) {
                        if($param2){
                           $value = array('key' => $param2,'value' => 'yes','compare' => '=');
                            array_push($meta_arr, $value);
                            array_push($meta_arr_fix, $value);

                        } 
                    }  
                }
                if($sovrum){
                    $sovrum_value = array('key' => '_product_sovrum','value' => intval($sovrum),'type' => 'numeric','compare' => '>=');
                    array_push($meta_arr, $sovrum_value);
                    array_push($meta_arr_fix, $sovrum_value);

                } 
                if($skidlift){
                    $skidlift_value = array('key' => '_product_skidlift','value' => intval($skidlift),'type' => 'numeric','compare' => '<=');
                    array_push($meta_arr, $skidlift_value);
                    array_push($meta_arr_fix, $skidlift_value);

                }

//                var_dump($product_ids);

                require_once BEDS_DIR . '/tamplates/products.php';

                require_once BEDS_DIR.'/tamplates/map.php';
            ?>
        </div>
        <?php
    }
    
    return ob_get_clean();
}

add_shortcode('page_hotel_inner','litepicker_hotel_inner');
function litepicker_hotel_inner()
{
    ob_start();
    wp_enqueue_style("beds-slick-style");
    wp_enqueue_style("beds-slick_theme-style");
    wp_enqueue_style("beds-register-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-hotel-style");

    wp_enqueue_script("beds-litepicker-script",'',[],false,false);
    wp_enqueue_script("beds-slick-script");
    wp_enqueue_script("beds-fslightbox-script");
    wp_enqueue_script("beds-product-script");

    global $product;
    global $wpdb;
    $post_id = get_the_id();
    $product = wc_get_product();
    $gallery        = $product->get_gallery_image_ids();
//    $picture = 'https://testbeds.wp4u.link/wp-content/uploads/woocommerce-placeholder.png';


    if(!empty($_GET['date_start'])){
        $date_start = $_GET['date_start'];
        $date = DateTime::createFromFormat('Y-m-d', $date_start) ?:
            DateTime::createFromFormat('Y/m/d', $date_start) ?:
                DateTime::createFromFormat('d/m/Y', $date_start) ?:
                    DateTime::createFromFormat('m/d/Y', $date_start) ?:
                        DateTime::createFromFormat('d-m-Y', $date_start) ?:
                            DateTime::createFromFormat('m-d-Y', $date_start);
//        $date->format('Y-m-d');
        $formatted_date_start = $date->format('d M.');

//         $dateTime = DateTime::createFromFormat('m/d/Y', $date_start); // Create DateTime object from MM/DD/YYYY
        $date_start = $date->format('Y-m-d');
    }else{
        $date_start = '';
    }
    if(!empty($_GET['date_end'])){
        $date_end = $_GET['date_end'];
        $date1 = DateTime::createFromFormat('Y-m-d', $date_end) ?:
            DateTime::createFromFormat('Y/m/d', $date_end) ?:
                DateTime::createFromFormat('d/m/Y', $date_end) ?:
                    DateTime::createFromFormat('m/d/Y', $date_end) ?:
                        DateTime::createFromFormat('d-m-Y', $date_end) ?:
                            DateTime::createFromFormat('m-d-Y', $date_end);
//        $date1->format('Y-m-d');
        $formatted_date_end = $date1->format('d M.');
//         $dateTime1 = DateTime::createFromFormat('m/d/Y', $date_end); // Create DateTime object from MM/DD/YYYY
        $date_end = $date1->format('Y-m-d');
    }else{
        $date_end = '';
    }
    if(isset($_GET['number-adult'])){
        $adult = $_GET['number-adult'];
    }else{
        $adult = '';
    }
    
    
    $datetime1 = date_create($date_start);
    $datetime2 = date_create($date_end);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days;
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $post_id);

    $room = get_post_meta($post_id, '_product_beds_id', true);
    $isAvaliable = $act->getIsAvailable($room,$date_start,$date_end);
    $noAvailInDB = check_date_noavaible($room,$date_start,$date_end);
//    var_dump(get_post_meta($post_id));
    $maxAdult = $act->getNumAdult($room);
    $notAvail = array(); // array with dates booked and close
    if ($isAvaliable ["success"]){
        foreach ($isAvaliable['data'][0]["availability"] as $key => $val) {
            if (!$val){
                array_push($notAvail,$key);
            }
        }
    }

    if(empty($noAvailInDB)){
        $act->updateAvailByRoom($room,$notAvail);
    }

    $excl = getAvailByRoomIDexcl($post_id);
    foreach ($notAvail as $key => $value) {
        if (in_array($value, $excl)) {
            unset($notAvail[$key]);
        }
    }

    $date_start_w = date("w", strtotime($date_start));
    $date_end_w = date("w", strtotime($date_end));
    if($date_start_w == '1' || $date_start_w == '2' || $date_start_w == '3' || $date_start_w == '5' || $date_start_w == '6'){
        array_push($notAvail, $date_start);
    }
    if($date_end_w == '1' || $date_end_w == '2' || $date_end_w == '3' || $date_end_w == '5' || $date_end_w == '6'){
        array_push($notAvail,$date_end);
    }

    $roomID = get_post_meta($post_id,'_product_beds_id',true);
//        $api = new \beds_booking\Action_beds_booking();
    $avail = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_start'");
    $availEnd = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_end'");
    $origin = date_create($date_start);
    $target = date_create($date_end);
    $interval = date_diff($origin, $target);
    $dateCount = $interval->format('%a');
    $check = check_date_noavaible($roomID,$date_start,$date_end);

//    var_dump($check);
//    var_dump($availEnd);
//    var_dump($dateCount);

    require_once BEDS_DIR.'/views/single-prod.php';

    ?>
<!--    <style>-->
<!--        .btn-start-inactiv-prod{-->
<!--            color: #ca0013 !important;-->
<!--            background-color: #eeeff1 !important;-->
<!--        }-->
<!--    </style>-->
    <?php

    return ob_get_clean();
}


add_shortcode('bottom_modal_beds','bottom_modal_beds');
function bottom_modal_beds(){
    ob_start();
    ?>
    <style>
        .beds-modal{
            color: black;
            background-color: white;
            width: 50%;
            height: auto;
            padding: 20px;
            border: 3px solid #e83939;
            border-radius: 20px;
            text-align: center;
            position: fixed;
            z-index: 999999;
            left: 25%;
            top: 40%;
        }
        .beds-modal-btn button{
            color: white;
            background: #e83939;
            font-size: 20px;
            font-weight: 500;
            border-radius: 20px;
        }
        .owf-modal{
            background: rgba(140, 140, 140, 0.59);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            z-index: 99999;
            right: 0;
            display: none;
        }
    </style>
    <div class="owf-modal" id="beds24-modal">
        <div class="beds-modal">
            <div class="beds-modal-header">
                <h3 id="modal-head-beds"></h3>
            </div>
            <div class="beds-modal-body">
                <p id="modal-text-beds"></p>
            </div>
            <div class="beds-modal-btn">
                <button>OK</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('api','api');
function api()
{
    ob_start();
//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    global $wpdb;
//
//    $res = $wpdb->get_results("select * from `wp_postmeta` WHERE `meta_key` = 'request_api_res'");
//    $notif = 0;
//
//    foreach ($res as $re) {
//        if (empty($re->meta_value)){
//            $notif++;
//        }
//        else{
//            $resApiObj = json_decode($re->meta_value)[0];
//            $apiSuccess = $resApiObj->success;
//          if (!$apiSuccess){
//              $notif++;
//          }
//        }
//    }
//    var_dump($notif);



    global $wpdb;
    $dateAllisB = "select `date` from `beds_calendar` where isBooked=1";
    $dateAllisB = $wpdb->get_results($dateAllisB,ARRAY_A);

    $r = array();
    foreach ($dateAllisB as $item) {
        array_push($r,$item['date']);
    }

    $dateAll = "select `date` from `beds_calendar`";
    $dateAll = $wpdb->get_results($dateAll,ARRAY_A);

    $newArr = array();
//var_dump($r);
    foreach ($dateAll as $key => $item) {
//        var_dump($item['date']);
        if (in_array($item['date'],$r )){
            unset($dateAll[$key]);
        }
    }

    // var_dump($dateAll);
//    $dateAll = array_unique($dateAll);
//    var_dump($dateAll);
//    $str = '';
//    $i = 0;
//    foreach ($dateAll as $item) {
//
//        if ($i == 0){
//            $str .= $item['roomId'];
//        } else {
//            $str .= ','.$item['roomId'];
//        }
//        $i++;
//    }
//
//    $dateMain = $wpdb->get_results("select date from `beds_calendar` where isBooked=0 and roomId in ('".$str."')", ARRAY_A);
//    var_dump("select date from `beds_calendar` where isBooked=0 and roomId in ('".$str."')");

    $fin = array();

    foreach ($dateAll as $item) {
        if ( ! in_array($item['date'],$fin)){
            array_push($fin,$item['date']);
        }
    }

//    $res = implode(',',$fin);
    echo json_encode($fin);
//
    ?>



    <?php


//    echo '<pre>';
//    $orderID = 198;
//    $order = wc_get_order($orderID);
//
////    var_dump();
//
////    foreach ( $order->get_items() as $item_id => $item ) {
////        $product_id = $item->get_product_id();
////        $variation_id = $item->get_variation_id();
////        $product = $item->get_product(); // see link above to get $product info
////        $product_name = $item->get_name();
////
////        $quantity = $item->get_quantity();
////        $subtotal = $item->get_subtotal();
////        $total = $item->get_total();
////        $tax = $item->get_subtotal_tax();
////        $tax_class = $item->get_tax_class();
////        $tax_status = $item->get_tax_status();
////        $allmeta = $item->get_meta_data();
////        $somemeta = $item->get_meta( '_whatever', true );
////        $item_type = $item->get_type(); // e.g. "line_item", "fee"
//////        var_dump($item_type);
////    }
//
//    foreach ($order->get_items() as $item_id => $item) {
//
//        $from = $item->get_meta('booked_from');
//
//        $to = $item->get_meta('booked_to');
//
//        $persons = $item->get_meta('persons');
//
//        $prodID = $item->get_data()['product_id'];
//
//        $roomId = get_post_meta($prodID, '_product_beds_id', true);
//
//        $data = $order->get_data();
//
//        $billing_first_name = $data['billing']['first_name'];
//
//        $billing_last_name = $data['billing']['last_name'];
//
//        $billing_address_1 = $data['billing']['address_1'];
//
//        $billing_city = $data['billing']['city'];
//
//        $billing_state = $data['billing']['state'];
//
//        $billing_postcode = $data['billing']['postcode'];
//
//        $billing_country = $data['billing']['country'];
//
//        $mail = $data['billing']['email'];
//
//        $tel = $data['billing']['phone'];
//
//        $invoiceArr = [];
//
//        foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){
//
//            $fee_name = $item_fee->get_name();
//            $fee_total = $item_fee->get_total();
//            $quantity = $item_fee->get_quantity();
//
//            array_push($invoiceArr,  [
//                "type" => "charge",
//                "qty"=>$quantity,
//                "amount"=>$fee_total,
//                "lineTotal"=>$fee_total,
//                "description"=>$fee_name
//            ]);
//
//        }
//
//
//
//        $post = [
//
//            [
//
//                'roomId' => $roomId,
//
//                "status" => "confirmed",
//
//                "arrival" => $from,
//
//                "departure" => $to,
//
//                "numAdult" => $persons,
//
//                "numChild" => 0,
//
//                "firstName" => $billing_first_name,
//
//                "lastName" => $billing_last_name,
//
//                "email" => $mail,
//
//                "mobile" => $tel,
//
//                "address" => $billing_address_1,
//
//                "city" => $billing_city,
//
//                "state" => $billing_state,
//
//                "postcode" => $billing_postcode,
//
//                "country" => $billing_country,
//
//                "invoiceItems" => $invoiceArr
//
//            ]
//
//        ];
//    }
////
//        var_dump($post);
//    setParamsToBedsAndDB(186);
//    $act->setBookingOnAPI(184 );
//
//    $room = get_post_meta(82,'_product_beds_id', true);
//    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=0";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    $timeArr = array();
//    foreach ($res as $re) {
//        array_push($timeArr,$re['date']);
//    }
//    echo json_encode($timeArr,320);
//    $room = get_post_meta(82,'_product_beds_id', true);
//    var_dump($room);
//    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=1 and `avaliable`=1";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    $timeArr = array();
//    foreach ($res as $re) {
//        array_push($timeArr,$re['date']);
//    }
//    var_dump($timeArr);
//    var_dump($room);
//    $sql = "select `avaliable`,`date` from `beds_calendar` where roomId='$room'";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    var_dump($res);
//    echo json_encode($res,320);
//    foreach ($res as $item) {
//        var_dump($item);
//
//    }
//    $act->setBookingOnAPI(156);
//    $order = wc_get_order( 156 );
//    var_dump($order);
//    var_dump(get_post_meta(156));
//    foreach ($order->get_items() as $item_id => $item ) {
//        $from = $item->get_meta('booked_from');
//        $to = $item->get_meta('booked_to');
//        $persons = $item->get_meta('persons');
//        $prodID = $item->get_data()['product_id'];
//        $roomId = get_post_meta($prodID, '_product_beds_id', true);
//        $data = $order->get_data();
//        $billing_first_name = $data['billing']['first_name'];
//        $billing_last_name  = $data['billing']['last_name'];
//        $billing_company    = $data['billing']['company'];
//        $billing_address_1  = $data['billing']['address_1'];
//        $billing_address_2  = $data['billing']['address_2'];
//        $billing_city       = $data['billing']['city'];
//        $billing_state      = $data['billing']['state'];
//        $billing_postcode   = $data['billing']['postcode'];
//        $billing_country    = $data['billing']['country'];
//        $mail = $data['billing']['email'];
//        $tel = $data['billing']['phone'];
//        var_dump($tel.$mail);
//    }

//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    $date = date('Y-m-d', strtotime('+ 1 year'));
//    var_dump($date);
//        $isAvaliable = $act->getIsAvailable('371906','2023-01-12','2023-01-19');
//    $act->checkAPICalls();
//var_dump($isAvaliable);
//var_dump($re);
//$photos = array();
//    foreach ($re->hosted as $item) {
//        if(!empty($item->url)){
//            array_push($photos,$item->url);
//        }
//    }
//var_dump(implode(',',$photos));

//    $date = date('Y-m-d', strtotime('+ 1 year'));
//    $act->setDataInCalendar($date,$date);

//    $res = $wpdb->get_var('SELECT count(`id`) from `beds_calendar`');
//    var_dump((int)$res != 0);
//    var_dump($act->setDataInTable());
//    var_dump($act->getPropDataByID(array(170082)));
    return ob_get_clean();
}

add_shortcode('filters_list','filters_list');
function filters_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-register-style");
    wp_enqueue_script('beds-filters-script');

    require_once BEDS_DIR.'/views/filters.php';

    return ob_get_clean();
}

add_shortcode('wishlist', 'wishlistFunction');
function wishlistFunction()
{
    ob_start();
    wp_enqueue_style("beds-wishlist");
    wp_enqueue_script('beds-wishlist-script');

    require_once BEDS_DIR.'/views/wishlist.php';

    return ob_get_clean();
}

add_shortcode('prislista-all','pricelistFunc');
function pricelistFunc()
{
    ob_start();
    wp_enqueue_style("beds-pricelist");
    wp_enqueue_script('beds-pricelist-script');

    require_once BEDS_DIR.'/views/pricelist.php';

    return ob_get_clean();
}

// period = winter/summer
add_shortcode('pricelist-table', function($atts){
    $atts = shortcode_atts( array(
        'period' => '',
    ), $atts, 'pricelist-table' );

    $period = $atts['period'];

    ob_start();
    require_once BEDS_DIR.'/views/pricelist-table.php';

    return ob_get_clean();
});