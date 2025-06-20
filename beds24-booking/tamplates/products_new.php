<?php
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

$current_page = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

if(isset($_GET['npage'])){
    $current_page = $_GET['npage'];
    update_option('current_page', $_GET['npage']);
}else{
    $current_page = 1;
}
$per_page = 10; // Кількість об'єктів на сторінку

if(!empty($area) && mb_strpos($area, '/')){
    $area = explode('/', $area);
}

if (isset($meta_arr)){
    $loop_filter = new WP_Query(array(
        'post_type' => 'product',
        'product_tag' => $area,
        'orderby' => 'post__in',
        'order' => 'DESC',
        'meta_query' => $meta_arr,
        'nopaging' => true
    ));
}
$f_array = [];
//echo '<pre>';
//var_dump($adult);
//var_dump($child);

if ($loop_filter->have_posts()){
while ($loop_filter->have_posts()) {
    $loop_filter->the_post();

    $people = 0;
    $c = get_post_meta(get_the_id(),'_children',true);
    if (!is_numeric($c)){
        $c = 0;
    }
    $a = get_post_meta(get_the_ID(),'_product_peoples',true);
    if (!is_numeric($a)){
        $a = 0;
    }
//    var_dump($a);
//    var_dump($c);
    if (isset($child)){

        // c = 4, a = 5
        
        if ($child > $maxChild){  // 4>2
            $adult = $adult + ($child-$maxChild); // 5 + (4-2) = 7
            $child = $child-$maxChild; //4-2 = 2
        }
//        var_dump($adult);
//        var_dump($child);
        if ($c>=$child && $a >= $adult){
            $people++;
        } elseif ( $a>= $adult + $child ){
            $people++;
        }
//        var_dump($people);
    } elseif ($adult > 0){
//        var_dump($adult);
//        var_dump($child);
        if ($a >= $adult){
            $people++;
        }
//        var_dump($people);
    }
//    echo 'all ='.$people;
    if ($people >0){
        array_push($f_array,get_the_id());
    }
    }
}

//var_dump($f_array);
//echo '</pre>';

$available_ids = $product_ids; // Ваша функція для отримання доступних
$unavailable_ids = $product_ids_no_avaliable; // Ваша функція для отримання недоступних

if (!empty($f_array)){
    $combined_ids = array_merge($available_ids, $unavailable_ids);
    $combined_ids = array_intersect($combined_ids,$f_array);
    $total_items = count($combined_ids);
    $total_pages = ceil($total_items / $per_page);
} else {
    $combined_ids = array_merge($available_ids, $unavailable_ids);
    $total_items = count($combined_ids);
    $total_pages = ceil($total_items / $per_page);
}


//var_dump($combined_ids);

$offset = ($current_page - 1) * $per_page;
$page_ids = array_slice($combined_ids, $offset, $per_page);
$loop = new WP_Query(array(
    'post_type' => 'product',
    'product_tag' => $area,
    'post__in' => $page_ids,
//    'posts_per_page' => $per_page,
    'orderby' => 'post__in',
    'order' => 'DESC',
    'meta_query' => $meta_arr,
//    'paged' => $current_page,
    'nopaging' => true
));
//var_dump($loop->posts);
// Виведення результатів
if ($loop->have_posts()) {
    while ($loop->have_posts()) {
        $loop->the_post();

        $permalink = get_the_permalink().'?'.$get_parrs;
        $post_id = get_the_id();
        $product = wc_get_product( $post_id );
        $regular_price = floatval($product->get_regular_price());
        $current_tags = get_the_terms( $post_id, 'product_tag' );
        $tags_name_arr = [];
        if(is_array($current_tags)){
            foreach($current_tags as $tag){
                array_push($tags_name_arr,$tag->name);
            }
        }

        $breadcrumbs = implode(" / ", $tags_name_arr);
        $child = get_post_meta($post_id,'_children', true);
        $hundtillatet = get_post_meta($post_id,'_product_hundtillåtet', true);
        $wi_fi = get_post_meta($post_id,'_product_wi_fi', true);
        $bastu = get_post_meta($post_id,'_product_bastu', true);
        $oppen_spis = get_post_meta($post_id,'_product_oppen_spis', true);
        $skidförråd = get_post_meta($post_id,'_product_skidförråd', true);
        $diskmaskin = get_post_meta($post_id,'_product_diskmaskin', true);
        $twatt = get_post_meta($post_id,'_product_tvättmaskin', true);
        $tork = get_post_meta($post_id,'_product_torkskåp', true);
        $barnsang = get_post_meta($post_id,'_product_barnsäng', true);
        $barnstol = get_post_meta($post_id,'_product_barnstol', true);
        $sovrum = get_post_meta($post_id,'_product_sovrum', true);
        $_product_boyta = get_post_meta($post_id,'_product_boyta',true);
        $product_details = $product->get_data();
        $product_short_description = $product_details['short_description'];
        $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $post_id);
        $peoples = intval(get_post_meta($post_id,'_product_peoples', true));
        $adult = intval($adult);

        $roomID = get_post_meta($post_id,'_product_beds_id',true);
//        $avail = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_start'");
//        $availEnd = $wpdb->get_var("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_end'");
        $origin = date_create($date_start);
        $target = date_create($date_end);
        $interval = date_diff($origin, $target);
        $dateCount = $interval->format('%a');

        if($adult<=$peoples){
            $picture = get_the_post_thumbnail_url($post_id,'middle');

            if($picture == false){
                $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $base_url = $scheme . '://' . $host;
                $picture = $base_url.'/wp-content/uploads/2023/08/20230401_124150932_iOS-1.jpg';
            }
        }
//        var_dump("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_start'");
//        var_dump("select `isBooked` from `beds_calendar` where `roomId` = '$roomID' and `date` = '$date_end'");
//        var_dump($avail);
//        var_dump($availEnd);
if(in_array($post_id,$unavailable_ids)){ ?>
<div style="opacity: 0.7;" class="searh-item-wrap dis">
    <?php }else{ ?>
    <div  class="searh-item-wrap" >
        <?php } ?>
        <div class="search-item-image" style="background: url('<?php echo $picture; ?>')" >
            <div style="width: 100%; height: 100%; cursor: pointer;" onclick="window.open('<?php echo $permalink; ?>');"></div>
            <!--<a href="#" onclick="window.open('<?php /*echo $permalink; */?>');"></a>-->
<!--            <img src="--><?php //echo $picture; ?><!--" alt="">-->
            <label  class="add-to-favorites" data-id="<?php echo $post_id;?>" style="z-index: 999">
                <?php
                if (isset($_SESSION['wishlist'])){
                    $list = explode(',',$_SESSION['wishlist']);
                    $disp = [0=>'block',1=>'none'];
                    if (in_array($post_id,$list)){
                        $disp[0] = 'none';
                        $disp[1] = 'block';
                    }
                } else {
                    $disp = [0=>'block',1=>'none'];
                }
                ?>
                <svg data-id="<?php echo $post_id;?>-b" style="display: <?= $disp[0];?>" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                    <path fill="red" d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/>                    </svg>
                <svg data-id="<?php echo $post_id;?>-r" style="display: <?= $disp[1];?>" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                    <path fill="red" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/>
                </svg>
            </label>
        </div>
        <?php
        $s = generateRandomString();
        $args_rating = array(
            'number'  => -1,
            'post_id'=>$post_id,
        );

        $reviewCount = 0;
        $repeater_field = get_field('acomodation_reviews',$post_id);
        if ($repeater_field) {
            $reviewCount = count($repeater_field);
        }
        $average = 0;
        $ratingAll = 0;
        while (have_rows('acomodation_reviews',$post_id)):
            the_row();
            $rating = get_sub_field('rating_from_0_to_5');
            if (!empty($rating) and $rating != 0){
                $ratingAll += (int)$rating;
            }
        endwhile;
        if ($ratingAll != 0){
            $average = $ratingAll / $reviewCount;
        }
        ?>

        <style>
            .on-mobile-show{
                display: none;
            }
            @media all and (max-width: 500px)  {
                .mob-hide{
                    display: none;
                }
                .on-mobile-show{
                    display: block;
                    text-decoration: none !important;
                }
                .mob-flex{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                    padding-right: 4px;
                    margin-bottom: 15px;
                }
                .period{
                    margin-bottom: 0;
                }

            }
        </style>
        <div class="search-item-content">

            <div style="display: flex;justify-content: space-between;">
                <p class="search-item-subtitle"><?php echo $breadcrumbs; ?><span> - <?php echo get_post_meta($post_id,'_product_breadcrumbs',true);?></span></p>

                <div class="search-item-reviews">
                    <div><a class="on-mobile-show" href="<?php echo $permalink; ?>"><i class="fas fa-star"></i><span> <?php if ($average == 0){echo $average;} else {echo sprintf("%.2f", $average);}?></span></a></div>
                </div>
            </div>
            <a href="#" class="search-item-title">
                <h4 onclick="window.open('<?php echo $permalink; ?>');"><?php the_title(); ?></h4>
            </a>
            <ul class="search-item-icons">
                <li class="icon-gray"><span><?php echo $peoples.'&nbsp;';
                        if (!empty($child)){echo '(+'.$child.')&nbsp;';}?></span><i class="fas fa-user-friends"></i></li>
                <li class="icon-gray"><span><?php echo $sovrum; ?></span> <img style="vertical-align: bottom;height:19px;" src="<?php echo BEDS_URL;?>assets/svg/hotel-bed.svg">

                </li>
                <li class="icon-gray"><span><?php echo $_product_boyta; ?> m<sup>2</sup></span></li>
                <?php

                if($hundtillatet){
                    echo '<li class="icon-red"><svg style=" margin: -4px 0; " width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.1862 12.2328L15.1834 12.2403H10.9831C10.3734 12.2403 9.80583 12.4238 9.33241 12.7382H7.99222C7.44296 12.7382 6.99611 12.2915 6.99611 11.7423C6.99611 11.1932 7.44296 10.7464 7.99222 10.7464H9.48638C9.76143 10.7464 9.98443 10.5234 9.98443 10.2484C9.98443 9.97344 9.76143 9.75049 9.48638 9.75049H7.99222C6.89371 9.75049 6 10.644 6 11.7423C6 12.8406 6.89371 13.7341 7.99222 13.7341H8.39448C8.13881 14.1745 7.99222 14.6857 7.99222 15.2306V21.469C7.99222 21.744 8.21521 21.967 8.49027 21.967H10.4825C10.7575 21.967 10.9805 21.744 10.9805 21.469V17.2666L15.5294 17.6751V21.5022C15.5294 21.7772 15.7524 22.0002 16.0275 22.0002H18.0197C18.2948 22.0002 18.5178 21.7772 18.5178 21.5022V16.7712L19.1315 13.7032L15.1862 12.2328Z" fill="#F2A4A9"/>
                        <path d="M22.5016 9.25222H20.9987C20.9179 8.4921 20.2744 7.89114 19.4817 7.89114H18.646L18.3411 6.39727C18.2413 5.90864 17.5605 5.85546 17.3863 6.32311L15.5332 11.2991L19.3287 12.7138L19.4235 12.2399H20.5094C21.8825 12.2399 22.9997 11.123 22.9997 9.75017C22.9997 9.47517 22.7767 9.25222 22.5016 9.25222Z" fill="#F2A4A9"/>
                        </svg>
                        </li>';
                }
                if($wi_fi){
                    echo '<li class="icon-red"><svg style=" margin: -4px 0; " width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.8749 14.0016C21.7272 14.0016 21.5857 13.9439 21.4813 13.8411C19.6165 12.0052 17.1372 10.9941 14.5 10.9941C11.8628 10.9941 9.38352 12.0052 7.51874 13.8411C7.41435 13.9439 7.27278 14.0017 7.12516 14.0017C6.97754 14.0017 6.83596 13.9439 6.73158 13.8411L5.16304 12.2969C4.94565 12.0829 4.94565 11.7359 5.16304 11.5219C6.42335 10.2811 7.89306 9.32029 9.5314 8.6662C11.1136 8.0345 12.7853 7.71423 14.5 7.71423C16.2147 7.71423 17.8864 8.03454 19.4686 8.6662C21.1069 9.32029 22.5767 10.2811 23.837 11.5219C24.0543 11.7359 24.0543 12.0829 23.837 12.2969L22.2685 13.8411C22.1641 13.9439 22.0225 14.0016 21.8749 14.0016Z" fill="#F2A4A9"/>
                        <path d="M18.6246 17.189C18.4769 17.189 18.3353 17.1312 18.231 17.0284C17.2341 16.047 15.9087 15.5064 14.4988 15.5064C13.089 15.5064 11.7636 16.047 10.7667 17.0284C10.6623 17.1312 10.5208 17.189 10.3731 17.189C10.2255 17.189 10.0839 17.1312 9.97952 17.0284L8.41105 15.4842C8.1937 15.2702 8.1937 14.9232 8.41109 14.7092C10.0372 13.1082 12.1992 12.2266 14.4989 12.2266C16.7985 12.2266 18.9606 13.1082 20.5867 14.7092C20.804 14.9232 20.804 15.2702 20.5867 15.4842L19.0182 17.0284C18.9138 17.1312 18.7722 17.189 18.6246 17.189Z" fill="#F2A4A9"/>
                        <path d="M14.5006 21.2858C13.2283 21.2858 12.1934 20.2769 12.1934 19.0368C12.1934 17.7967 13.2284 16.7878 14.5006 16.7878C15.7728 16.7878 16.8078 17.7967 16.8078 19.0368C16.8078 20.2769 15.7728 21.2858 14.5006 21.2858Z" fill="#F2A4A9"/>
                        </svg>
                        </li>';
                }
                if ($bastu){
                    echo '<li class="icon-red"><img style="vertical-align: inherit;" src="'.BEDS_URL.'assets/img/66.svg"></li>';
                }

                if ($oppen_spis){
                    echo '<li class="icon-red" ><img style="vertical-align: inherit;width:21px;" src="'.BEDS_URL.'assets/img/7.svg"></li>';
                }
                ?>
            </ul>
            <p class="search-item-excerpt">
                <?php echo mb_strimwidth($product_short_description, 0, 200, "..."); ?>
            </p>
        </div>
        <?php
        $reviewCount = 0;
        $repeater_field = get_field('acomodation_reviews',$post_id);
        if ($repeater_field) {
            $reviewCount = count($repeater_field);
        }
        $average = 0;
        $ratingAll = 0;
        while (have_rows('acomodation_reviews',$post_id)):
            the_row();
            $rating = get_sub_field('rating_from_0_to_5');
            if (!empty($rating) and $rating != 0){
                $ratingAll += (int)$rating;
//                            var_dump($rating);
            }
        endwhile;
        if ($ratingAll != 0){
            $average = $ratingAll / $reviewCount;
        }
        ?>
        <div class="search-item-meta" style="position: relative;">
            <div class="search-item-reviews mob-hide">
                <div><i class="fas fa-star"></i><span> <?php if ($average == 0){echo $average;} else {echo sprintf("%.2f", $average);}?></span></div>
                <a class="" href="<?php echo $permalink; ?>"><?php echo $reviewCount;?> <?php _e('omdömen','beds24');?></a>
            </div>
            <div class="mob-flex">
                <div><p class="search-item-price"><?php echo round($price_by_period, -2); ?> SEK</p></div>
                <div><p class="period"><span>Period: <?php echo $period1.' - '. $period2?></span></p></div>
            </div>
            <div class="search-item-buttons">
                <?php if( in_array($post_id,$unavailable_ids)){ ?>
                    <a  class="btn btn-transparent add-to-cart" style="background-color: transparent !important;" disabled="disabled">+ <i class="fas fa-shopping-cart"></i></a>

                    <a style="pointer-events: none;background: grey;" data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo round($price_by_period, -2); ?>" class="btn open-cart beds_add_to_cart" href=""><?php _e('inte tillgänglig','beds24');?></a>
                <?php }else{ ?>
                    <a href="#" class="btn btn-transparent add-to-cart" data-s="<?php echo $s;?>" data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo round($price_by_period, -2); ?>" data-toggle="modal" data-target="#<?php echo $s;?>">+ <i class="fas fa-shopping-cart"></i></a>

                    <a data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo round($price_by_period, -2); ?>" class="beds_add_to_cart btn open-cart" href=""><i class="fas fa-shopping-cart"></i> <?php _e('Boka','beds24');?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal fade mod400" id="<?php echo $s;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border: none;margin-bottom: -10px;padding-bottom: 0;">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="width: 100%; text-align: center;">
                        <svg width="66" height="63" viewBox="0 0 66 63" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <ellipse cx="33" cy="31.4392" rx="33" ry="31.4392" fill="url(#pattern0_4538_37265)"/>
                            <defs>
                                <pattern id="pattern0_4538_37265" patternContentUnits="objectBoundingBox" width="1" height="1">
                                    <use xlink:href="#image0_4538_37265" transform="matrix(0.00444444 0 0 0.00466509 0 -0.0224901)"/>
                                </pattern>
                                <image id="image0_4538_37265" width="225" height="224" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"/>
                            </defs>
                        </svg>


                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: transparent !important;">
                        <span aria-hidden="true" class="modal-close">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">

                        <h5 style="margin-bottom: 15px;font-size: 32px;"><?php _e('Boende reserverat', 'beds24');?></h5>
                        <h6 style=" margin-bottom: 25px; font-weight: 600; "><?php the_title(); ?></h6>
                        <div class="card-modalPOP" style="width: 100%; display: flex;justify-content: center;">
                            <div><img src="<?php echo $picture; ?>" alt=""></div>
                            <div class="grid">
                                <div>
                                    Tillträde
                                </div>
                                <?php
                                $period1_obj = DateTime::createFromFormat('d.m.Y', $period1 . '.' . date('Y'));
                                $formatted_date1 = $period1_obj ? $period1_obj->format('Y-m-d') : '';

                                $period2_obj = DateTime::createFromFormat('d.m.Y', $period2 . '.' . date('Y'));
                                $formatted_date2 = $period2_obj ? $period2_obj->format('Y-m-d') : '';
                                ?>
                                <div style="text-align: right"><span><?php echo $formatted_date1 ?></span></div>
                                <div>
                                    Avresa
                                </div>
                                <div style="text-align: right"><span><?php echo $formatted_date2 ?></span></div>
                                <div style="font-weight: 700;display: flex ; align-items: flex-end; justify-content: flex-start;">
                                    Pris
                                </div>
                                <div style="font-weight: 700;display: flex ; align-items: flex-end; justify-content: flex-end; text-align: right"><?php echo round($price_by_period_modal, -2); ?> SEK</div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer w-100" style="border: none; text-align: center;justify-content: center">
                    <button type="button" class="btn btn-transparent" data-dismiss="modal"><?php _e('Fortsätt Boka','beds24');?></button>
                    <button type="button" class="btn btn-primary" onclick='location = site_url+"/index.php/cart/"'><?php _e('Gå Till Varukorgen','beds24');?></button>
                </div>
            </div>
        </div>
    </div>

<?php }
}

//    if($ajax == false && $per_pages < ($prod /*+ $notAvailProd*/)){
    $i=0;
    ?>
    <div class="pagination_content">
        <div class="pagination">
            <?php
            $get_parrs = [];
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            $url = $url[0];
            ?><input type="hidden" name="url" value="<?php echo $url; ?>"><?php

            $get["date_start"] = $date_start;
            $get['date_end'] = $date_end;
            foreach($get as $key => $value){
                if($key != 'npage'){
                    array_push($get_parrs,$key.'='.$value);
                }
            }
            $get_parrs = implode('&',$get_parrs);
            $current_page = intval($current_page);
            if($current_page > 1){
                $back = $current_page-1;
                echo '<a href="'.$url.'?'.$get_parrs.'&npage='.$back.'">«</a>';
            }
            while($i < $total_pages){ //
                $i++;
                if($current_page == $i){
                    $class = 'class="active"';
                }else{
                    $class = '';
                }
                echo '<a '.$class.' href="'.$url.'?'.$get_parrs.'&npage='.$i.'">'.$i.'</a>';
            }
            if($current_page < $total_pages){ //$pages
                $next = $current_page+1;
                echo '<a href="'.$url.'?'.$get_parrs.'&npage='.$next.'">»</a>';
            }
            ?>
        </div>
    </div>




        <script>
            $("body").on('click','.add-to-cart', function (e) {
                e.preventDefault();
                let custom_price = $(this).attr('data-custom_price');
                let product_id = $(this).attr('data-product_id');
                let add_button = $(this);
                let date_from = $("#startDateNew").val()
                let date_to = $("#endDateNew").val()
                let persons = $("#adult").val()
                let personsA = $("#num-adult").val()
                let personsC = $("#num-child").val()

                if (!$(this).is('[disabled]')){
                    $.ajax({
                        type: 'POST',
                        url: site_url + '/wp-admin/admin-ajax.php',
                        data: {
                            product_id: product_id,
                            custom_price: custom_price,
                            date_from:date_from,
                            date_to:date_to,
                            personsA:personsA,
                            personsC:personsC,
                            action: 'addtocart'
                        },
                        dataType: "json",
                        cache: false,
                        error: function(error){
                            alert('error');
                            $('.backmodal').remove();

                        },
                        beforeSend: function(){
                            $('body').append('<div class="backmodal"><div></div></div>');
                        },
                        success: function(data){
                            $('.backmodal').remove();
                            // console.log(data[0])
                            if (data[0] === 'limit'){
                                alert("Unfortunately, it is possible to add no more than 3 objects to the basket. To book more objects, contact the administration.")
                            } else {
                                $(add_button).closest('.content_bottom').children('.result').addClass('active');
                                $(add_button).closest('.buy_button').children('.result').addClass('active');
                                // location = site_url+"/index.php/cart/";
                                // $('body').find('header').load(location.href + "* header")
                                $( ".wmc-cart-wrapper" ).load(window.location.href + " .wmc-cart-wrapper > * " );
                            }

                        }
                    });
                }


            })


        </script>
        <?php
        $_av_products_ids = [];

            $map_loop = new WP_Query( array(
                'post_type' => 'product',
                'product_tag' => $area,
                'posts_per_page' => -1,
                'post__in'=> $product_ids,
                'meta_query' => $meta_arr,
                'orderby' => 'post__in',
                'order' => 'DESC',
        //        'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $current_page
            ));

            while ( $map_loop->have_posts() ): $map_loop->the_post();
                $post_id = get_the_id();
                array_push($_av_products_ids,$post_id);
            endwhile;
        $_av_products_ids_str = implode(",", $_av_products_ids);
        ?>
        <input type="hidden" class="av_products_ids_str" name="" value="<?php echo $_av_products_ids_str ?>">