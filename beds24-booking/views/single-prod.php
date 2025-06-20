<?php
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
$price_by_period_modal = $price_by_period;

$reviewCount = 0;
$average = 0;
$repeater_field = get_field('acomodation_reviews');
if( $repeater_field ){
    $reviewCount = count($repeater_field);
    $averages = array_map(function($r){
        return !empty($r['rating_from_0_to_5']) ? $r['rating_from_0_to_5'] : 0;
    }, $repeater_field);
    if($averages){
        $average = array_sum($averages) / $reviewCount;
    }
}


?>
<style>
    .container__main{margin-left: -250px;}

    .dop-wrap{
        display: flex;
        min-width: 628px;
        position: absolute;
        top: -90px;
        border-radius: 5px;
        background: white;
        height: auto;
        width: 100%;
        color: black;
        font-size: 20px;
    }
    .nights{
        width: 49%;
        padding: 10px;
        margin-top: 10px;
    }
    .inout{
        width: 49%;
        display: flex;
        line-height: 1.5em;
        border: 1px solid #CDCDD2;
        margin-top: 10px;
        padding-left: 10px;
        border-radius: 10px;
    }

    .pricelist-toggle-buttons {
        display: flex;
        width: 100%;
        gap: 10px;
        padding-bottom: 20px;
    }

    .pricelist-toggle-button {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 5px;
        border: 1px solid #ddd;
        background-color: white !important;
        padding: 5px 20px;
        border-radius: 10px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: black !important;
    }

    .pricelist-toggle-button.active {
        border-color: #CA0013;
    }

    .pricelist-toggle-button .pricelist-toggle-button-icon {
        font-size: 1.2rem;
    }

    #inner_prc_tb{
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        font-size: 16px;
    }

    #inner_prc_tb th{
        background: #CA0013;
        color: #fff;
        padding: 5px;
        text-align: center;
    }
    #inner_prc_tb tr{
        border-left: solid 1px #E4E4EC;
        border-right: solid 1px #E4E4EC;
    }
    #inner_prc_tb td{
        border-bottom: solid 1px #E4E4EC;
        color: #000;
        padding: 5px;
        text-align: center;
        font-weight: lighter;
    }

    #inner_prc_tb tr.pricelist-period-tr-colored td:not(:nth-child(1)){
        color: #293688;
        font-weight: bolder;
    }
    #inner_prc_tb tr.pricelist-period-tr-disabled{
        opacity: 0.5;
    }

    .single-product-features.feature-grid-two{
        border: 1px solid #E4E4EC;
        padding: 32px;
        border-radius: 8px;
    }

    .buy_button{
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-start;
    }

    .buy_button a{
        flex: 1;
        padding: 10px;
        height: 100%;
        align-content: center;
    }

    .single-product-sidebar.period .mob-d-none{
        border: 1px solid #CDCDD2;
        border-radius: 8px;
        padding: 0 25px;
        margin: 25px 0;
    }

    .woocommerce:where(body:not(.woocommerce-uses-block-theme)) div.product p.price{
        font-size: 18px;
    }

    .single-product-attributes-list{
        display: flex;
        gap: 15px;
        align-items: flex-start;
        justify-content: space-between;
    }

    .single-product-attributes-list-col{
        flex: 1;
    }

    .map-container-title-row a{
        text-decoration: none;
        color: inherit;
    }

    .single-product-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-title-reviews {
        display: none;
    }

    .single-product-content-text-mobile{
        display: none;
    }

    .single-product-content .col-lg-7 {
        padding-top: 3rem;
    }

    .mobile-calendar-btn-updated{
        display: none;
    }

    @media (max-width: 767px) {
        .mobile-title-reviews {
            display: block;
        }
        .desktop-title-reviews{
            display: none;
        }
    }

    @media (min-width: 992px){
        .map-container-title-row > div:nth-child(2){
            text-align: right;
        }
    }

    @media (max-width: 992px){
        .omdomen-block {
            flex: 0 0 100%;
        }
        .testimonials-top{
            display: block;
        }
    }

    @media all and (max-width: 1200px) {
        .dop-wrap{
            flex-direction: column;
            min-width: 315px;
            top: -175px;
        }
        .nights,.inout{
            width: 100%;
        }
    }
    @media all and (max-width: 768px){
        .container__main{
            margin-left:0;
        }
        .dop-wrap{
            max-width: 315px;
        }
        .single-product-content-text-desktop{
            display: none
        }
        .single-product-content-text-mobile{
            display: block;
        }
        .single-product-content .col-lg-7{
            border: none;
        }
        .single-product-content .col-lg-7 .single-product-description{
            padding-bottom: 0 !important;
        }
        .single-product-content .col-lg-7 {
            padding-top: 0;
        }
    }
    @media all and (max-width: 480px){
        .dop-wrap {
            max-width: 480px;
        }
        .single-product-sidebar{
            background: none;
            padding: 0;
            box-shadow: none;
            
        }

    }
     /*body .litepicker.container__months.wrap-calendar-info.btn-start-inactiv-prod{*/
     /*    color: #bcb8b8 !important;*/
     /*    background-color: #fadddf !important;*/
     /*    border: none !important;*/
     /*}*/
</style>

<div class="single-product-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 border-b">
                <div class="single-product-description mb-4 pb-5">

                    <div class="single-product-top">
                        <div class="single-product-meta">
                            <div class="single-product-title">
                                <h1><?= get_the_title() ?></h1>
                                <div class="mobile-title-reviews">
                                    <i class="fas fa-star pr-1"></i><span> <?php echo sprintf("%.2f", $average); ?></span>
                                </div>
                            </div>

                            <div class="product-tags mr-sm-auto" style="display: block;">

                                <div class="d-flex align-items-center">

                                    <svg class="mr-2" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M9.00016 10.0726C10.2925 10.0726 11.3402 9.02492 11.3402 7.73258C11.3402 6.44023 10.2925 5.39258 9.00016 5.39258C7.70781 5.39258 6.66016 6.44023 6.66016 7.73258C6.66016 9.02492 7.70781 10.0726 9.00016 10.0726Z" stroke="black" stroke-width="1.5"/>

                                        <path d="M2.71527 6.3675C4.19277 -0.127498 13.8153 -0.119998 15.2853 6.375C16.1478 10.185 13.7778 13.41 11.7003 15.405C10.1928 16.86 7.80777 16.86 6.29277 15.405C4.22277 13.41 1.85277 10.1775 2.71527 6.3675Z" stroke="black" stroke-width="1.5"/>

                                    </svg>

                                    <?php echo wc_get_product_tag_list($product->get_id(), ', '); ?>

                                    <span> - <?php echo get_post_meta($product->get_id(),'_product_breadcrumbs',true);?></span>

                                </div>



                                <div class="desktop-title-reviews">

                                    <i class="fas fa-star pr-1"></i>
                                    <span>
                                        <?php echo sprintf("%.2f", $average); ?>
                                        <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="2" cy="2" r="2" fill="black"/>
                                        </svg>
                                    </span>

                                    <a class="link-to-reviews" href="#"><?php echo $reviewCount;?> <?php _e('omdömen','beds24');?></a>

                                </div>



                            </div>

                        </div>
                    </div>

                    <div>
                        <!--<h5><?php /*_e('Beskrivning', 'beds24'); */?></h5>-->
                        <?php

                        $content = apply_filters( 'the_content', get_the_content(), get_the_ID() );

                        echo $content; ?>
                    </div>
                    </div>
                <!-- <div class="single-product-attributes">
                    <h5><?php _e('Properties', 'beds24'); ?></h5>

                    <?php
                    $post_id = get_the_id();

                    //                    var_dump(get_post_meta($post_id));

                    $child = get_post_meta($post_id, '_children', true);
                    $hundtillatet = get_post_meta($post_id, '_product_hundtillåtet', true);
                    $wi_fi = get_post_meta($post_id, '_product_wi_fi', true);
                    $bastu = get_post_meta($post_id, '_product_bastu', true);
                    $oppen_spis = get_post_meta($post_id, '_product_oppen_spis', true);
                    $skidforrad = get_post_meta($post_id, '_product_skidförråd', true);
                    $diskmaskin = get_post_meta($post_id, '_product_diskmaskin', true);
                    $twatt = get_post_meta($post_id, '_product_tvättmaskin', true);
                    $tork = get_post_meta($post_id, '_product_torkskåp', true);
                    $barnsang = get_post_meta($post_id, '_product_barnsäng', true);
                    $barnstol = get_post_meta($post_id, '_product_barnstol', true);
                    $sovrum = get_post_meta($post_id, '_product_sovrum', true);
                    $_product_boyta = get_post_meta($post_id, '_product_boyta', true);
                    $sommar = get_post_meta($post_id, '_product_sommar', true);
                    $LaddningElbil = get_post_meta($post_id, '_product_laddning_elbil', true);
                    $baddar = get_post_meta($post_id, '_product_baddar', true);
                    $product_dusch = get_post_meta($post_id, '_product_dusch', true);
                    $product_wc = get_post_meta($post_id, '_product_wc', true);
                    $product_tv = get_post_meta($post_id, '_product_tv', true);
                    $product_skidbuss = get_post_meta($post_id, '_product_skidbuss', true);
                    $product_kyl_frys = get_post_meta($post_id, '_product_kyl_frys', true);
                    $product_mikro = get_post_meta($post_id, '_product_mikro', true);
                    $product_bus = get_post_meta($post_id, '_product_skidbuss', true);
                    $product_skidlift = get_post_meta($post_id, '_product_skidlift', true);
                    $product_langdspar = get_post_meta($post_id, '_product_langdspar', true);
                    $product_matbutik = get_post_meta($post_id, '_product_matbutik', true);
                    $product_restaurang = get_post_meta($post_id, '_product_restaurang', true);
                    $product_salens_by = get_post_meta($post_id, '_product_salens_by', true);
                    $product_dubbelsang = get_post_meta($post_id, '_product_dubbelsang', true);
                    $peoples = intval(get_post_meta($post_id,'_product_peoples', true));

                    if (empty(get_post_meta( $post_id, '_price', true))){
                        update_post_meta( $post_id, '_price', 300);
                    }

                    ?>
                    <style>
                        .specWrap {
                            display: flex;
                            justify-content: space-between;
                        }

                        .specName {
                            text-align: left;
                            width: 50%;
                        }
                        .specIco, .specVal{
                            width: 25%;
                        }
                        .woocommerce-product-gallery__trigger{
                            bottom: .5em !important;
                            left: .5em !important;
                            top: unset !important;
                            /*content: "Alla foton";*/
                        }
                        .woocommerce div.product div.images .woocommerce-product-gallery__trigger::after{
                            content: none;
                        }
                        .woocommerce div.product div.images .woocommerce-product-gallery__trigger::before{
                            content: none;
                        }
                    </style>
                    <div style="display: flex;justify-content: space-between;">
                        <div style="width: 40%">
                            <div>
                                <h6><?php _e('Allmänt', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/40 m2.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Boyta', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $_product_boyta . ' m' ?><sup>2</sup></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun 1.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Wifi', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($wi_fi == 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/2.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Laddning Elbil', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($LaddningElbil == 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun12.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidförråd', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($skidforrad === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun13.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sommar', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($sommar === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Husdjur', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/pet.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Hundtillåtet', 'beds24'); ?></div>
                                    <div class="specVal" id="petA" data-p="<?= $hundtillatet;?>"><?php if ($hundtillatet === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Sovrum', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/hotel.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Bäddar', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $baddar; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/hotel-bed.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sovrum', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $sovrum; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bed.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Dubbelsäng', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_dubbelsang === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/baby-crib.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Barnsäng', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($barnsang === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Badrum och tvätt', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/shower.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Dusch', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_dusch; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/toilet.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('WC', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_wc; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/drying-machine.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Torkskåp', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($tork === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/laundry.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Tvättmaskin', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($twatt === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>

                        </div>

                        <div style="width: 40%">
                            <div>
                                <h6><?php _e('Kök', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/dishwasher.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Diskmaskin', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($diskmaskin === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/fridge.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Kyl/frys', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_kyl_frys === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/microwave-oven.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Mikro', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_mikro === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/baby-chair.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Barnstol', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($barnstol === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Nöje', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/television.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('TV', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_tv == 'yes' or $product_tv ==1) {
                                            _e('Ja', 'beds24');
                                        } else {
                                            _e('Nej', 'beds24');
                                        } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/oppen spis.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Öppen spis', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($oppen_spis === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bastu.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Bastu', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($bastu === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Avstånd', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bus.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidbuss', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_bus . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/ski-lift.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidlift', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_skidlift . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/path.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Längdspår', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_langdspar . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/store.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Matbutik', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_matbutik . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/restaurant.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Restaurang', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_restaurang . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/mountains.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sälens by', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_salens_by . ' km'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <style>
                .new_wrap_date::after {
                    content: '\25BC'; /* Unicode for the down arrow */
                    position: absolute;
                    right: 25px; /* Position it on the right */
                    top: 50%;
                    transform: translateY(-50%); /* Center it vertically */
                    font-size: 12px;
                    color: #333; /* Arrow color */
                }
                .new_wrap_date{
                    width: 100%;
                    position: relative;
                    padding: 15px;
                }
                #date-3_1{
                    cursor: pointer;
                    width: 100%;
                    border: none;
                    background-position-x: 0 !important;
                    text-indent: 30px !important;
                }
                .g{
                    font-family: Mulish;
                    font-size: 18px;
                    text-transform: uppercase;
                    font-weight: 700;
                    display: block;
                    cursor: pointer;
                    padding: 12px 16px;
                    cursor: pointer;
                }
                .modal-close{
                    color: black;
                    font-weight: 100;
                    text-shadow: none;
                    background: white;
                }
                .inp-block-users::after {
                    content: '\25BC';
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 12px;
                    color: #333;
                }
                #adult-select{
                    background-position-x: 0px !important; ;
                    text-indent: 35px !important;
                }
            </style>
            <div class="col-lg-5 border-b">

                <div class="single-product-sidebar period">
                    <div class="mobile-box-btn">
                        <div class="mobile-box-btn-inner">
                    <?php
                    $adult = 1;
                    if (isset($_GET['number-adult'])){
                        $adult = (int)$_GET['number-adult'];
                    }
                    $children = (int)$_GET['number-child'] ?? 0;
                    $animals = $_GET['animals'];

                    ?>
                    <div class="mobile-calendar-btn">
                        <?php
                    global $wpdb;
                    $times = $wpdb->get_row("select checkInStart,checkInEnd,checkOutEnd from `beds_properties` where roomId=$room");
                    ?>
                        <div class="desktop-hide" style="display: flex; justify-content: space-between;">
                            <p class="price"><?php _e('Pris:','beds24');?></p>
                            <p class="price"><?php echo round($price_by_period, -2); ?> SEK</p>
                        </div>
                        <div class="datum-btn">
                            <span class="datum-btn-inner">
                            <img src="http://stugor2.hemsida.eu/wp-content/uploads/2024/05/calendar.png">
                            Datum
                            </span>
                        
                        <div class="single-product-calendar" title="v1">
                            <div class="dates new_wrap_date">
                                <?php
                                if (!empty($_GET['date_start'])) {
                                    $date_start = $_GET['date_start'];
                                    $dateTime = DateTime::createFromFormat('Y-m-d', $date_start) ?:
                                        DateTime::createFromFormat('Y/m/d', $date_start) ?:
                                            DateTime::createFromFormat('d/m/Y', $date_start) ?:
                                                DateTime::createFromFormat('m/d/Y', $date_start) ?:
                                                    DateTime::createFromFormat('d-m-Y', $date_start) ?:
                                                        DateTime::createFromFormat('m-d-Y', $date_start);
                                    $formatted_date_start = $dateTime->format('d M.');
                                } else {
                                    $date_start = '';
                                    $formatted_date_start = '';
                                }
                                if (!empty($_GET['date_end'])) {
                                    $date_end = $_GET['date_end'];
                                    $dateTime1 = DateTime::createFromFormat('Y-m-d', $date_end) ?:
                                        DateTime::createFromFormat('Y/m/d', $date_end) ?:
                                            DateTime::createFromFormat('d/m/Y', $date_end) ?:
                                                DateTime::createFromFormat('m/d/Y', $date_end) ?:
                                                    DateTime::createFromFormat('d-m-Y', $date_end) ?:
                                                        DateTime::createFromFormat('m-d-Y', $date_end);
                                    $formatted_date_end = $dateTime1->format('d M.');
                                } else {
                                    $date_end = '';
                                    $formatted_date_end = '';
                                }
                                ?>
                                <input type="text"  id="date-3_1" value="<?php if (!empty($formatted_date_start) and !empty($formatted_date_end)){echo $formatted_date_start .' → '. $formatted_date_end;} ?>"
                                       placeholder="<?php if (!empty($formatted_date_start) and !empty($formatted_date_end)){echo $formatted_date_start .' → '. $formatted_date_end;} else _e('Datum','beds24'); ?>" readonly/>
                                <input type="hidden" name="date_start" id="startDateNew" value="<?php echo $date_start; ?>">
                                <input type="hidden" name="date_end" id="endDateNew" value="<?php echo $date_end; ?>">
                            </div>
                            <div class="gaster-select">
                                <div class="gaster-select-text">
<!--                                    <p class="g">--><?php //_e('Gäster','beds24');?><!--</p>-->
                                    <div class="inp-block-users" id="gaster-select-block">
                                        <?php $gg = $adult+$children; ?>
                                        <input type="text" style="padding-bottom: 10px;" readonly id="adult-select" name="adult" required value="<?php _e('Gäster','beds24'); ?>">
                                        <div class="form-clients">
                                            <div class="clients-blk">
                                                <div>
                                                    <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Vuxna','beds24');?></p>
                                                    <p style="margin-bottom: 0; color: #595959;"><?php _e('Från 13 år','beds24');?></p>
                                                </div>
                                                <div class="pl-mi-btn">
                                                    <svg class="minus-client" id="minus-adult" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>
                                                        <path d="M11 16.7505V15.2495H21V16.7505H11Z" fill="black"/>
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>
                                                    </svg>

                                                    <input type="number" id="num-adult" value="<?php echo $adult;?>" name="number-adult" readonly>
                                                    <svg class="plus-client" id="plus-adult" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>
                                                        <path d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z" fill="black"/>
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>
                                                    </svg>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="clients-blk">
                                                <div>
                                                    <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Barn','beds24');?></p>
                                                    <p style="margin-bottom: 0; color: #595959;"><?php _e('Åldrar 2–12','beds24');?></p>
                                                </div>
                                                <div class="pl-mi-btn">
                                                    <svg class="minus-client" id="minus-child" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>
                                                        <path d="M11 16.7505V15.2495H21V16.7505H11Z" fill="black"/>
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>
                                                    </svg>

                                                    <input type="number" id="num-child" value="<?php echo $children; ?>" name="number-child" readonly>
                                                    <svg class="plus-client" id="plus-child" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>
                                                        <path d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z" fill="black"/>
                                                        <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>
                                                    </svg>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="clients-blk">
                                                <label for="animals" class="switcher-container" style="width: 100%;justify-content: space-between;">
                                                    <?php //_e('Vi har hund med oss.','beds24');?>
                                                    <div>
                                                        <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Hund', 'beds24'); ?></p>
                                                        <p style="margin-bottom: 0; color: #595959;"><?php _e('Deposition', 'beds24'); ?> 500 SEK</p>
                                                    </div>
                                                    <input type="checkbox" <?php if ($hundtillatet !== 'yes') {echo 'disabled';}?>  id="animals" name="animals" <?php if ($animals == 'on'){echo 'checked';}?> value="1000">
                                                    <span class="switchmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<!--                                <div id="gaster-select-icon">-->
<!--                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">-->
<!--                                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
                           
                            </div>

                        </div>
                    </div>
                    </div>
                            <div class="mobile-calendar-btn-updated">
                                <a href="javascript:;" class="btn btn-transparent mobile-calendar-btn-updated-btn">
                                    <div class="mobile-calendar-btn-updated-btn-icon">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.1662 4.37398V3.33398C13.1662 3.06065 12.9395 2.83398 12.6662 2.83398C12.3928 2.83398 12.1662 3.06065 12.1662 3.33398V4.33398H7.83284V3.33398C7.83284 3.06065 7.60617 2.83398 7.33284 2.83398C7.0595 2.83398 6.83284 3.06065 6.83284 3.33398V4.37398C5.03284 4.54065 4.1595 5.61398 4.02617 7.20732C4.01284 7.40065 4.17284 7.56065 4.3595 7.56065H15.6395C15.8328 7.56065 15.9928 7.39398 15.9728 7.20732C15.8395 5.61398 14.9662 4.54065 13.1662 4.37398Z" fill="black"/> <path d="M15.3333 8.56055H4.66667C4.3 8.56055 4 8.86055 4 9.22721V13.3339C4 15.3339 5 16.6672 7.33333 16.6672H12.6667C15 16.6672 16 15.3339 16 13.3339V9.22721C16 8.86055 15.7 8.56055 15.3333 8.56055ZM8.14 14.1405C8.10667 14.1672 8.07333 14.2005 8.04 14.2205C8 14.2472 7.96 14.2672 7.92 14.2805C7.88 14.3005 7.84 14.3139 7.8 14.3205C7.75333 14.3272 7.71333 14.3339 7.66667 14.3339C7.58 14.3339 7.49333 14.3139 7.41333 14.2805C7.32667 14.2472 7.26 14.2005 7.19333 14.1405C7.07333 14.0139 7 13.8405 7 13.6672C7 13.4939 7.07333 13.3205 7.19333 13.1939C7.26 13.1339 7.32667 13.0872 7.41333 13.0539C7.53333 13.0005 7.66667 12.9872 7.8 13.0139C7.84 13.0205 7.88 13.0339 7.92 13.0539C7.96 13.0672 8 13.0872 8.04 13.1139C8.07333 13.1405 8.10667 13.1672 8.14 13.1939C8.26 13.3205 8.33333 13.4939 8.33333 13.6672C8.33333 13.8405 8.26 14.0139 8.14 14.1405ZM8.14 11.8072C8.01333 11.9272 7.84 12.0005 7.66667 12.0005C7.49333 12.0005 7.32 11.9272 7.19333 11.8072C7.07333 11.6805 7 11.5072 7 11.3339C7 11.1605 7.07333 10.9872 7.19333 10.8605C7.38 10.6739 7.67333 10.6139 7.92 10.7205C8.00667 10.7539 8.08 10.8005 8.14 10.8605C8.26 10.9872 8.33333 11.1605 8.33333 11.3339C8.33333 11.5072 8.26 11.6805 8.14 11.8072ZM10.4733 14.1405C10.3467 14.2605 10.1733 14.3339 10 14.3339C9.82667 14.3339 9.65333 14.2605 9.52667 14.1405C9.40667 14.0139 9.33333 13.8405 9.33333 13.6672C9.33333 13.4939 9.40667 13.3205 9.52667 13.1939C9.77333 12.9472 10.2267 12.9472 10.4733 13.1939C10.5933 13.3205 10.6667 13.4939 10.6667 13.6672C10.6667 13.8405 10.5933 14.0139 10.4733 14.1405ZM10.4733 11.8072C10.44 11.8339 10.4067 11.8605 10.3733 11.8872C10.3333 11.9139 10.2933 11.9339 10.2533 11.9472C10.2133 11.9672 10.1733 11.9805 10.1333 11.9872C10.0867 11.9939 10.0467 12.0005 10 12.0005C9.82667 12.0005 9.65333 11.9272 9.52667 11.8072C9.40667 11.6805 9.33333 11.5072 9.33333 11.3339C9.33333 11.1605 9.40667 10.9872 9.52667 10.8605C9.58667 10.8005 9.66 10.7539 9.74667 10.7205C9.99333 10.6139 10.2867 10.6739 10.4733 10.8605C10.5933 10.9872 10.6667 11.1605 10.6667 11.3339C10.6667 11.5072 10.5933 11.6805 10.4733 11.8072ZM12.8067 14.1405C12.68 14.2605 12.5067 14.3339 12.3333 14.3339C12.16 14.3339 11.9867 14.2605 11.86 14.1405C11.74 14.0139 11.6667 13.8405 11.6667 13.6672C11.6667 13.4939 11.74 13.3205 11.86 13.1939C12.1067 12.9472 12.56 12.9472 12.8067 13.1939C12.9267 13.3205 13 13.4939 13 13.6672C13 13.8405 12.9267 14.0139 12.8067 14.1405ZM12.8067 11.8072C12.7733 11.8339 12.74 11.8605 12.7067 11.8872C12.6667 11.9139 12.6267 11.9339 12.5867 11.9472C12.5467 11.9672 12.5067 11.9805 12.4667 11.9872C12.42 11.9939 12.3733 12.0005 12.3333 12.0005C12.16 12.0005 11.9867 11.9272 11.86 11.8072C11.74 11.6805 11.6667 11.5072 11.6667 11.3339C11.6667 11.1605 11.74 10.9872 11.86 10.8605C11.9267 10.8005 11.9933 10.7539 12.08 10.7205C12.2 10.6672 12.3333 10.6539 12.4667 10.6805C12.5067 10.6872 12.5467 10.7005 12.5867 10.7205C12.6267 10.7339 12.6667 10.7539 12.7067 10.7805C12.74 10.8072 12.7733 10.8339 12.8067 10.8605C12.9267 10.9872 13 11.1605 13 11.3339C13 11.5072 12.9267 11.6805 12.8067 11.8072Z" fill="black"/> </svg>
                                    </div>
                                    <?php if (!empty($formatted_date_start) and !empty($formatted_date_end)): ?>
                                        <div class="mobile-calendar-btn-updated-btn-text">
                                            <div class="mobile-calendar-btn-updated-btn-text-title">
                                                <?= round($price_by_period, -2); ?> SEK
                                            </div>
                                            <div class="mobile-calendar-btn-updated-btn-text-desc">
                                                <?= $formatted_date_start .' → '. $formatted_date_end ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mobile-calendar-btn-updated-btn-text">
                                            <div class="mobile-calendar-btn-updated-btn-text-title">
                                                <?php _e('Datum','beds24'); ?>
                                            </div>
                                        </div>
                                        <div class="mobile-calendar-btn-updated-btn-arrow">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M7 10L12 4L2 4L7 10Z" fill="black"/> </svg>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>

                    <?php
                    global $wpdb;
                    $times = $wpdb->get_row("select checkInStart,checkInEnd,checkOutEnd from `beds_properties` where roomId=$room");
                    ?>
                    <!-- <div class="single-product-features">
                        <div>
                            <p><?php _e('Incheckning','beds24');?></p>
                            <span><?php _e('från','beds24');?> <?php echo $times->checkInStart;?></span>
                        </div>
                        <div>
                            <p><?php _e('Utcheckning','beds24');?></p>
                            <span><?php _e('senast','beds24');?> <?php echo $times->checkOutEnd;?></span>
                        </div>
                        <div>
                            <p><?php _e('Slutstädning går att beställa','beds24');?></p>
                            <span>8:00-<?php echo $times->checkOutEnd;?></span>
                        </div>
                        <div>
                            <p><?php _e('Avbokningsskydd','beds24');?> </p>
                            <span>375 SEK</span>
                        </div>
                        <div>
                            <p><?php _e('Deposition för husdjur','beds24');?> </p>
                            <span>1000 SEK</span>
                        </div>
                        <div>
                            <p><?php _e('Åldersgräns','beds24');?></p>
                            <span>25+</span>
                        </div>
                    </div> -->

                    <div class="mob-d-none" style="display: flex; justify-content: space-between;">
                        <p class="price">
                            <span style="padding-right: 10px;">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M1.60714 1.875C0.719542 1.875 0 2.59454 0 3.48214V4.48922H15V3.48214C15 2.59454 14.2804 1.875 13.3929 1.875H1.60714ZM0 11.5179V5.82851H15V11.5179C15 12.4054 14.2804 13.125 13.3929 13.125H1.60714C0.719542 13.125 0 12.4054 0 11.5179ZM10.1786 9.24107C9.80874 9.24107 9.50893 9.54088 9.50893 9.91071C9.50893 10.2806 9.80874 10.5804 10.1786 10.5804H11.7857C12.1556 10.5804 12.4554 10.2806 12.4554 9.91071C12.4554 9.54088 12.1556 9.24107 11.7857 9.24107H10.1786Z" fill="black"/> </svg>
                            </span>
                            <?php _e('Pris:','beds24');?>
                        </p>
                        <p class="price"><?php echo round($price_by_period, -2); ?> SEK</p>
                    </div>

                    <?php
                    $s = generateRandomString();
                    $picture = get_the_post_thumbnail_url($post_id,'middle');
                    $date_period1 = new DateTime($_GET['date_start']);
                    $date_period2 = new DateTime($_GET['date_end']);
                    $period1 = $date_period1->format('d.m');
                    $period2 = $date_period2->format('d.m');
                    ?>

                    <div class="buy_button">
                        <?php if ($peoples+(int)$child >= $gg):?>
                            <a href="#" class="btn btn-transparent add-to-cart <?php if ((!empty($check)) or ($avail == 0) or ($availEnd == 0) or $dateCount < 3) {echo 'notBuy';} ?>" data-s="<?php echo $s;?>" data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>" data-toggle="modal" data-target="#<?php echo $s;?>">+ <i class="fas fa-shopping-cart"></i></a>

                            <a data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>" class="btn w-100 buy beds_add_to_cart <?php if ((!empty($check)) or ($avail == 0) or ($availEnd == 0) or $dateCount < 3) {
                                                                                                                                                            echo 'notBuy';
                                                                                                                                                        } ?>" href=""><?php _e('Boka','beds24');?></a>
                        <div class="result"><?php _e('Added to cart','beds24');?></div>
                        <?php else: ?>
                        <div><?php _e('We cannot accommodate so many people here','beds24');?></div>
                        <?php endif;?>
                    </div>
                </div>
                
            </div>
                </div>
                <div class="feature-info">
                    <h5><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php _e('Bookings information'); ?></font></font></h5>
                    <div class="single-product-features feature-grid-two">
                            <div class="feature-grid-col">
                                <div>
                                    <p><?php _e('Incheckning','beds24');?></p>
                                    <span><?php _e('från','beds24');?> <?php echo $times->checkInStart;?></span>
                                </div>
                                <!--<div>
                                    <p><?php /*_e('Slutstädning går att beställa','beds24');*/?></p>
                                    <span>8:00-<?php /*echo $times->checkOutEnd;*/?></span>
                                </div>-->
                                <div>
                                    <p><?php _e('Avresestädning', 'beds24');?></p>
                                    <?php
                                    $cleaning_price = get_field('departure_cleaning', get_the_ID()) ?: 1800;
                                    $cleaning_rut_price = get_field('departure_cleaning_rut', get_the_ID()) ?:  900;
                                    ?>
                                    <span><?= $cleaning_price.' SEK'.($cleaning_rut_price ? ' ('.$cleaning_rut_price.' med RUT)' : '') ?></span>
                                </div>
                                <div>
                                <p><?php _e('Deposition för husdjur','beds24');?> </p>
                                <span>1000 SEK</span>
                            </div>
                            </div>
                            <div class="feature-grid-col">
                                 <div>
                                    <p><?php _e('Utcheckning','beds24');?></p>
                                    <span><?php _e('senast','beds24');?> <?php echo $times->checkOutEnd;?></span>
                                </div>
                                <div>
                                    <p><?php _e('Avbokningsskydd','beds24');?> </p>
                                    <span>375 SEK</span>
                                </div>
                                <div>
                                    <p><?php _e('Åldersgräns','beds24');?></p>
                                    <span>25+</span>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-lg-7 mb-5">
                <div class="single-product-attributes ">
                    <h5><?php _e('Properties', 'beds24'); ?></h5>

                    <?php
                    $post_id = get_the_id();

                    //                    var_dump(get_post_meta($post_id));

                    $child = get_post_meta($post_id, '_children', true);
                    $hundtillatet = get_post_meta($post_id, '_product_hundtillåtet', true);
                    $wi_fi = get_post_meta($post_id, '_product_wi_fi', true);
                    $bastu = get_post_meta($post_id, '_product_bastu', true);
                    $oppen_spis = get_post_meta($post_id, '_product_oppen_spis', true);
                    $skidforrad = get_post_meta($post_id, '_product_skidförråd', true);
                    $diskmaskin = get_post_meta($post_id, '_product_diskmaskin', true);
                    $twatt = get_post_meta($post_id, '_product_tvättmaskin', true);
                    $tork = get_post_meta($post_id, '_product_torkskåp', true);
                    $barnsang = get_post_meta($post_id, '_product_barnsäng', true);
                    $barnstol = get_post_meta($post_id, '_product_barnstol', true);
                    $sovrum = get_post_meta($post_id, '_product_sovrum', true);
                    $_product_boyta = get_post_meta($post_id, '_product_boyta', true);
                    $sommar = get_post_meta($post_id, '_product_sommar', true);
                    $LaddningElbil = get_post_meta($post_id, '_product_laddning_elbil', true);
                    $baddar = get_post_meta($post_id, '_product_baddar', true);
                    $product_dusch = get_post_meta($post_id, '_product_dusch', true);
                    $product_wc = get_post_meta($post_id, '_product_wc', true);
                    $product_tv = get_post_meta($post_id, '_product_tv', true);
                    $product_skidbuss = get_post_meta($post_id, '_product_skidbuss', true);
                    $product_kyl_frys = get_post_meta($post_id, '_product_kyl_frys', true);
                    $product_mikro = get_post_meta($post_id, '_product_mikro', true);
                    $product_bus = get_post_meta($post_id, '_product_skidbuss', true);
                    $product_skidlift = get_post_meta($post_id, '_product_skidlift', true);
                    $product_langdspar = get_post_meta($post_id, '_product_langdspar', true);
                    $product_matbutik = get_post_meta($post_id, '_product_matbutik', true);
                    $product_restaurang = get_post_meta($post_id, '_product_restaurang', true);
                    $product_salens_by = get_post_meta($post_id, '_product_salens_by', true);
                    $product_dubbelsang = get_post_meta($post_id, '_product_dubbelsang', true);
                    $peoples = intval(get_post_meta($post_id,'_product_peoples', true));

                    if (empty(get_post_meta( $post_id, '_price', true))){
                        update_post_meta( $post_id, '_price', 300);
                    }

                    ?>
                    <style>
                        .specWrap {
                            display: flex;
                            justify-content: space-between;
                        }

                        .specName {
                            text-align: left;
                            width: 50%;
                        }
                        .specIco, .specVal{
                            width: 25%;
                        }
                        .woocommerce-product-gallery__trigger{
                            bottom: .5em !important;
                            left: .5em !important;
                            top: unset !important;
                            /*content: "Alla foton";*/
                        }
                        .woocommerce div.product div.images .woocommerce-product-gallery__trigger::after{
                            content: none;
                        }
                        .woocommerce div.product div.images .woocommerce-product-gallery__trigger::before{
                            content: none;
                        }
                    </style>
                    <div class="single-product-attributes-list">
                        <div class="single-product-attributes-list-col feature-list">
                            <div>
                                <h6><?php _e('Allmänt', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/40 m2.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Boyta', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $_product_boyta . ' m' ?><sup>2</sup></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun 1.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Wifi', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($wi_fi == 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/2.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Laddning Elbil', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($LaddningElbil == 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun12.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidförråd', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($skidforrad === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/sun13.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sommar', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($sommar === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Husdjur', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/pet.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Hundtillåtet', 'beds24'); ?></div>
                                    <div class="specVal" id="petA" data-p="<?= $hundtillatet;?>"><?php if ($hundtillatet === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Sovrum', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/hotel.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Bäddar', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $baddar; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/hotel-bed.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sovrum', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $sovrum; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bed.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Dubbelsäng', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_dubbelsang === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/baby-crib.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Barnsäng', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($barnsang === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Badrum och tvätt', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/shower.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Dusch', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_dusch; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/toilet.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('WC', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_wc; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/drying-machine.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Torkskåp', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($tork === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/laundry.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Tvättmaskin', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($twatt === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>

                        </div>

                        <div class="single-product-attributes-list-col feature-list">
                            <div>
                                <h6><?php _e('Kök', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/dishwasher.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Diskmaskin', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($diskmaskin === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/fridge.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Kyl/frys', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_kyl_frys === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/microwave-oven.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Mikro', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_mikro === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/baby-chair.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Barnstol', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($barnstol === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Nöje', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/television.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('TV', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($product_tv == 'yes' or $product_tv ==1) {
                                            _e('Ja', 'beds24');
                                        } else {
                                            _e('Nej', 'beds24');
                                        } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/oppen spis.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Öppen spis', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($oppen_spis === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bastu.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Bastu', 'beds24'); ?></div>
                                    <div class="specVal"><?php if ($bastu === 'yes') {
                                                                _e('Ja', 'beds24');
                                                            } else {
                                                                _e('Nej', 'beds24');
                                                            } ?></div>
                                </div>
                            </div>
                            <div>
                                <h6><?php _e('Avstånd', 'beds24'); ?></h6>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/bus.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidbuss', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_bus . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/ski-lift.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Skidlift', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_skidlift . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/path.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Längdspår', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_langdspar . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/store.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Matbutik', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_matbutik . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/restaurant.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Restaurang', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_restaurang . ' m'; ?></div>
                                </div>
                                <div class="specWrap">
                                    <div class="specIco"><img src="<?php echo BEDS_URL . 'assets/svg/mountains.svg'; ?>" alt=""></div>
                                    <div class="specName"><?php _e('Sälens by', 'beds24'); ?></div>
                                    <div class="specVal"><?php echo $product_salens_by . ' km'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Get weeks data
            global $wpdb;
            require_once(BEDS_DIR . '/includes/class.action.php');

            $act = new \beds_booking\Action_beds_booking();
            $table_name = 'beds_pricelist_weeks';
            //$weeks_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id");
            $weeks_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY start_date asc");
            ?>

            <div class="col-lg-5">
                <h5><?php _e('Prislista','beds24');?></h5>
                <div class="table-wrapper price-list-table" id="price_list_apd">
                    <div class="pricelist-toggle-buttons">
                        <div class="pricelist-toggle-button" data-period="winter">
                            <span class="pricelist-toggle-button-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_3447_42072)"> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.90047 0.313814C7.48206 -0.104605 6.80366 -0.104605 6.38524 0.313814C5.96683 0.732233 5.96683 1.41062 6.38524 1.82904L8.92857 4.37237V8.92857H4.37237L1.82904 6.38524C1.41062 5.96683 0.732233 5.96683 0.313814 6.38524C-0.104605 6.80366 -0.104605 7.48206 0.313814 7.90047L2.41334 10L0.313814 12.0995C-0.104605 12.5179 -0.104605 13.1963 0.313814 13.6148C0.732233 14.0332 1.41062 14.0332 1.82904 13.6148L4.37237 11.0714H8.92857V15.6276L6.38524 18.171C5.96683 18.5894 5.96683 19.2677 6.38524 19.6861C6.80366 20.1046 7.48206 20.1046 7.90047 19.6861L10 17.5867L12.0995 19.6861C12.5179 20.1046 13.1963 20.1046 13.6148 19.6861C14.0332 19.2677 14.0332 18.5894 13.6148 18.171L11.0714 15.6276V11.0714H15.6276L18.171 13.6148C18.5894 14.0332 19.2677 14.0332 19.6861 13.6148C20.1046 13.1963 20.1046 12.5179 19.6861 12.0995L17.5867 10L19.6861 7.90047C20.1046 7.48206 20.1046 6.80366 19.6861 6.38524C19.2677 5.96683 18.5894 5.96683 18.171 6.38524L15.6276 8.92857H11.0714V4.37237L13.6148 1.82904C14.0332 1.41062 14.0332 0.732233 13.6148 0.313814C13.1963 -0.104605 12.5179 -0.104605 12.0995 0.313814L10 2.41334L7.90047 0.313814ZM4.24239 4.24239C4.6608 3.82397 5.3392 3.82397 5.75761 4.24239L7.18619 5.67096C7.6046 6.08937 7.6046 6.76777 7.18619 7.18619C6.76777 7.6046 6.08937 7.6046 5.67096 7.18619L4.24239 5.75761C3.82397 5.3392 3.82397 4.6608 4.24239 4.24239ZM7.18619 14.329C7.6046 13.9106 7.6046 13.2322 7.18619 12.8138C6.76777 12.3954 6.08937 12.3954 5.67096 12.8138L4.24239 14.2424C3.82397 14.6609 3.82397 15.3391 4.24239 15.7576C4.6608 16.176 5.3392 16.176 5.75761 15.7576L7.18619 14.329ZM15.7576 4.24239C16.176 4.6608 16.176 5.3392 15.7576 5.75761L14.329 7.18619C13.9106 7.6046 13.2322 7.6046 12.8138 7.18619C12.3954 6.76777 12.3954 6.08937 12.8138 5.67096L14.2424 4.24239C14.6609 3.82397 15.3391 3.82397 15.7576 4.24239ZM14.329 12.8138C13.9106 12.3954 13.2322 12.3954 12.8138 12.8138C12.3954 13.2322 12.3954 13.9106 12.8138 14.329L14.2424 15.7576C14.6609 16.176 15.3391 16.176 15.7576 15.7576C16.176 15.3391 16.176 14.6609 15.7576 14.2424L14.329 12.8138Z" fill="black"/> </g> <defs> <clipPath id="clip0_3447_42072"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg>
                            </span>
                            <?php _e('Vinter'); ?>
                        </div>
                        <div class="pricelist-toggle-button" data-period="summer">
                            <span class="pricelist-toggle-button-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_3447_42078)"> <path d="M10.0007 0.833252V2.49992M10.0007 17.4999V19.1666M3.51732 3.51659L4.70065 4.69992M15.3007 15.2999L16.484 16.4833M0.833984 9.99992H2.50065M17.5007 9.99992H19.1673M3.51732 16.4833L4.70065 15.2999M15.3007 4.69992L16.484 3.51659M14.1673 9.99992C14.1673 12.3011 12.3018 14.1666 10.0007 14.1666C7.69946 14.1666 5.83398 12.3011 5.83398 9.99992C5.83398 7.69873 7.69946 5.83325 10.0007 5.83325C12.3018 5.83325 14.1673 7.69873 14.1673 9.99992Z" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_3447_42078"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg>
                            </span>
                            <?php _e('Sommar'); ?>
                        </div>
                    </div>
                    <table id="inner_prc_tb">
                        <thead>
                            <tr>
                                <th ><?php _e('Vecka','beds24');?></th> <!-- Week -->
                                <th><?php _e('Datum','beds24');?></th> <!-- Date -->
                                <th><?php _e('Dagar','beds24');?></th> <!-- Days -->
                                <th><?php _e('Pris','beds24');?> (SEK)</th> <!-- Award -->
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($weeks_data as $week): 
                                
                                $date_start = $week->start_date;
                                $date_end = $week->end_date;
                                $week_name = $week->week_name;
                                
                                $availability_data = get_availability_data($post_id, $date_start, $date_end);
                                // $availability_data = get_availability_data($week_name, $post_id, $date_start, $date_end);
                                
                                $case1_available_dates = $availability_data['case1'];

                                // $case2_notavailable_dates = $availability_data['case2'];

                               
                                // if( $week_name == 'Vecka 51' || $week_name == 'Vecka 8' || $week_name == 'Vecka 3' || $week_name == 'Vecka 4' || $week_name == 'Vecka 5' || $week_name == 'Vecka 6' || $week_name == 'Vecka 7' ) { 

                                 /*if( $week_name == 'Vecka 3' ) {  

                                    $roomID = get_post_meta($post_id, '_product_beds_id', true);
                                    
                                    echo "<br>===============================<br>";
                                    echo 'week_name = '.$week_name.'<br>';
                                    echo 'roomID = '.$roomID.'<br>';
                                    echo 'date_start = '.$date_start.'<br>';
                                    echo 'date_end = '.$date_end.'<br>';

                                    // echo 'availability_data'.'<br>';
                                    // echo '<pre>'; print_r($availability_data); echo '</pre>';

                                    echo 'case1_available_dates'.'<br>';
                                    echo '<pre>'; print_r($case1_available_dates); echo '</pre>';

                                    // echo 'case2_notavailable_dates'.'<br>';
                                    // echo '<pre>'; print_r($case2_notavailable_dates); echo '</pre>';

                                   
                                }*/
                                 
                                

                                // Fetch product URL based on post_id
                                $product_url = get_permalink($post_id);

                                if (isset($case1_available_dates) && is_array($case1_available_dates) && !empty($case1_available_dates)) {

                                    $firstDayDate = $case1_available_dates[0];
                                    
                                    // $lastDayDate = end($case1_available_dates);

                                    $lastIndex = array_key_last($case1_available_dates);

                                    $secondLastIndex = $lastIndex - 1;

                                    $lastDayDate = $case1_available_dates[$secondLastIndex];

                                    $visualLastDayDate = $case1_available_dates[$lastIndex];

                                    /*if( $week_name == 'Vecka 13') {

                                        echo 'firstDayDate = '.$firstDayDate.'<br>';
                                        echo 'lastDayDate = '.$lastDayDate.'<br>';
                                        echo 'visualLastDayDate = '.$visualLastDayDate.'<br>';
                                        echo 'lastIndex = '.$lastIndex.'<br>';

                                    } */

                                } else {
                                // Handle the case where $case1_available_dates is not set, not an array, or empty
                                // For example, you can set default values or throw an error
                                    $firstDayDate = null;
                                    $lastDayDate = null;
                                // or log an error, or handle it in a way that makes sense for your application
                                }

                                // $lastDayDate = $case1_available_dates[$fl];



                                // comment it.
                                $firstDate = format_date($firstDayDate);
                                $lastDate = format_date($visualLastDayDate);

                                // used to draw td data only
                                // $firstDate = format_date($date_start);
                                // $lastDate = format_date($date_end);
                                
                                $noOfDays = calculate_days($firstDayDate, $lastDayDate);
                                $noOfDays = $noOfDays + 1;
                                

                                // $price_by_period = $act->getRoomPriceByDays($noOfDays, $firstDayDate, $lastDayDate, $post_id);

                                $price_by_period = $act->getRoomPriceByDays($noOfDays, $firstDayDate, $lastDayDate, $post_id);


                                $start_weekday = get_weekday($firstDayDate);
                                $end_weekday = get_weekday($visualLastDayDate);

                                // draw for week start and end dates
                                // $start_weekday = get_weekday($date_start);
                                // $end_weekday = get_weekday($date_end);


                                /*if( $week_name == 'Vecka 3' ) {
                                    
                                    echo 'firstDayDate = '.$firstDayDate.'<br>';
                                    echo 'lastDayDate = '.$lastDayDate.'<br>';
                                    echo 'noOfDays = '.$noOfDays.'<br>';
                                    echo 'price_by_period = '.$price_by_period.'<br>';
                                    echo 'start_weekday = '.$start_weekday.'<br>';
                                    echo 'end_weekday = '.$end_weekday.'<br>';
                                    echo "<br>===============================<br>";
                                }*/


                            ?>
                                <!-- <tr onclick="redirectToPage('<?php //echo $firstDayDate; ?>', '<?php //echo $lastDayDate; ?>', '<?php //echo $product_url; ?>')"> -->


                            <?php
                                $start_link = $date_start;
                                if ($firstDayDate !== $visualLastDayDate){
                                    $start_link = $firstDayDate;
                                }
                                $is_period_available = !empty($case1_available_dates) && $price_by_period > 0;
                                $is_period_colored = $is_period_available && $start_weekday == $end_weekday && in_array($start_weekday, ['sön', 'sun', 'tors', 'thu']);
                                $tr_classes = [
                                    'pricelist-period-tr'
                                ];
                                if(!$is_period_available){
                                    $tr_classes[] = 'pricelist-period-tr-disabled';
                                }
                                if($is_period_colored){
                                    $tr_classes[] = 'pricelist-period-tr-colored';
                                }
//                                ['sön', 'mån', 'tis', 'ons', 'tors', 'fre', 'lör']
//                            Mon (Monday) — понеділок
//Tue (Tuesday) — вівторок
//Wed (Wednesday) — середа
//Thu (Thursday) — четвер
//Fri (Friday) — п'ятниця
//Sat (Saturday) — субота
//Sun (Sunday) — неділя
//                                var_dump(get_locale());
                            if (get_locale() == 'en_US'){
                                if ($start_weekday == 'sön'){
                                    $start_weekday = str_replace('sön', 'sun',$start_weekday);
                                }
                                if ($end_weekday == 'sön'){
                                    $end_weekday = str_replace('sön', 'sun',$end_weekday);
                                }
                                if ($start_weekday == 'tors'){
                                    $start_weekday = str_replace('tors', 'thu',$start_weekday);
                                }
                                if ($end_weekday == 'tors'){
                                    $end_weekday = str_replace('tors', 'thu',$end_weekday);
                                }
                            }
                                ?>
                                <tr class="<?= implode(' ', $tr_classes) ?>" data-title="<?= esc_attr($week->week_name) ?>" data-start="<?= esc_attr($date_start) ?>" onclick="redirectToPage('<?php echo $start_link; ?>', '<?php echo $visualLastDayDate; ?>', '<?php echo $product_url; ?>', '<?php echo get_locale();?>')">
                                    <!--<td><?php /*if (get_locale() == 'sv_SE'){ echo $week->week_name;} else { echo str_replace('Vecka', 'Week',$week->week_name ); } */?></td>-->
                                    <td><?= explode(' ', $week->week_name)[1] ?? $week->week_name ?></td>
                                    <?php if ($is_period_available) : ?>
                                        <td><?php echo $firstDate . '-' . $lastDate; ?></td>
                                        <td><?php echo $start_weekday . '-' . $end_weekday; ?></td>
                                        <td><?php echo $price_by_period; ?></td>
                                    <?php else : ?>
                                        <td><?php _e('Not available','beds24');?></td>
                                        <td></td>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php 
                            endforeach; 
                            ?>
                        </tbody>
                        <script>
                            function redirectToPage(startDate, endDate, productUrl, locale) {
                                // Set the start and end date values
                                document.getElementById('startDateNew').value = startDate;
                                document.getElementById('endDateNew').value = endDate;

                                let amp = '?';
                                if (locale === 'en_US'){
                                    amp = '&';
                                }
                                // Construct the URL
                                var url = productUrl + amp +"date_start=" + startDate + "&date_end=" + endDate + "&number-adult=1&number-child=0&animals=1000";

                                // Redirect
                                window.location.href = url;

                                // Set the start and end date values
                                document.getElementById('startDateNew').value = startDate;
                                document.getElementById('endDateNew').value = endDate;
                            }
                        </script>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="<?php echo $s;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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



<?php
$date1 = new DateTime($date_start);
$date2 = new DateTime($date_end);
$interval = $date1->diff($date2);
//echo $interval->days;


$child = get_post_meta($post_id, '_children', true);
$peoples = intval(get_post_meta($post_id,'_product_peoples', true));
//if (!empty($child) and $child != 0){
    $maxChild = (int)$peoples -1 + (int)$child;
    $maxAdult = (int)$peoples;

//    var_dump($peoples);
//    var_dump($maxAdult);
//} else {

//}
?>

<script>

    $("body").on('change','#animals', function (e) {
        let value = $(this).val();
        // value = parseFloat(value);

        if ($(this).prop('checked')) {
            Cookies.set('accompanied_dog' + '<?= $post_id; ?>', value, { expires: 1, path: '/' });
        }else{
            Cookies.remove('accompanied_dog' + '<?= $post_id; ?>');
        }
        $('#ship-to-different-address-checkbox').trigger('click');
        $('#ship-to-different-address-checkbox').trigger('click');
    });

    let adult_min = 1
    let adult_max = parseInt('<?php echo $maxAdult;?>')
    let child_max = parseInt('<?php echo $maxChild;?>')
    let max_peple = parseInt('<?php echo $maxAdult;?>') + parseInt('<?php echo (int)$child;?>')

    $('#plus-adult').on('click', function () {
        let num_adult = $('#num-adult').val()
        let num_child = $('#num-child').val()
        if (num_adult >= adult_min && num_adult < adult_max){
            if ((parseInt(num_adult) + parseInt(num_child)) < max_peple){
                num = parseInt(num_adult) + 1
                $('#num-adult').val(num)

            }

        }
    })
    $('#minus-adult').on('click', function () {
        let num_adult = $('#num-adult').val()
        let num_child = $('#num-child').val()
        if (num_adult > adult_min ){
            if ((parseInt(num_adult) + parseInt(num_child)) <= max_peple){
                num = parseInt(num_adult) - 1
                $('#num-adult').val(num)
            }

        }
    })

    $('#plus-child').on('click', function () {
        let num_adult = $('#num-adult').val()
        let num_child = $('#num-child').val()
        // console.log(child_max)
        // console.log(max_peple)
        if (num_child >= 0 && num_child < child_max){
            // console.log((num_adult + num_child) < max_peple)
            if ((parseInt(num_adult) + parseInt(num_child)) < max_peple){
                num = parseInt(num_child) + 1
                $('#num-child').val(num)
            }

        }
    })
    $('#minus-child').on('click', function () {
        let num_adult = $('#num-adult').val()
        let num_child = $('#num-child').val()
        if (num_child > 0 ){
            if ((parseInt(num_adult) + parseInt(num_child)) <= max_peple){
                num = parseInt(num_child) - 1
                $('#num-child').val(num)
            }

        }
    })


    $(document).mouseup(function(e)
    {
        var container = $(".form-clients");
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });

    // $('#adult-select').on('click',function () {
    //     var display = $(".form-clients").css('display')
    //     if (display === 'none'){
    //         $(".form-clients").css('display','block')
    //     }
    //     // if (display === 'block'){
    //     //     $(".form-clients").css('display','none')
    //     // }
    // })
    $('#gaster-select-block').on('click',function () {
        var display = $(".form-clients").css('display')
        if (display === 'none'){
            $(".form-clients").css('display','block')
        }
        // if (display === 'block'){
        //     $(".form-clients").css('display','none')
        // }
    })

    

    setTimeout( function () {


    //     var gal_text = '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.50065 14.6666H10.5007C13.834 14.6666 15.1673 13.3333 15.1673 9.99998V5.99998C15.1673 2.66665 13.834 1.33331 10.5007 1.33331H6.50065C3.16732 1.33331 1.83398 2.66665 1.83398 5.99998V9.99998C1.83398 13.3333 3.16732 14.6666 6.50065 14.6666Z" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.49935 6.66667C7.23573 6.66667 7.83268 6.06971 7.83268 5.33333C7.83268 4.59695 7.23573 4 6.49935 4C5.76297 4 5.16602 4.59695 5.16602 5.33333C5.16602 6.06971 5.76297 6.66667 6.49935 6.66667Z" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.2793 12.6334L5.56596 10.4267C6.09263 10.0734 6.85263 10.1134 7.32596 10.52L7.54596 10.7134C8.06596 11.16 8.90596 11.16 9.42596 10.7134L12.1993 8.33335C12.7193 7.88669 13.5593 7.88669 14.0793 8.33335L15.166 9.26669" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> Alla foton';
        $('.woocommerce-product-gallery__trigger').remove()
        $('.flex-viewport').append('<a href="#" class="woocommerce-product-gallery__trigger" style="border: 1px #ca0013 solid;justify-content: center;text-decoration: none;text-indent:unset;font-size: unset;background: white;border-radius: 5px;display: flex;width: 146px;align-items: center;"><svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block;margin-right: 5px;"><path d="M6.50065 14.6666H10.5007C13.834 14.6666 15.1673 13.3333 15.1673 9.99998V5.99998C15.1673 2.66665 13.834 1.33331 10.5007 1.33331H6.50065C3.16732 1.33331 1.83398 2.66665 1.83398 5.99998V9.99998C1.83398 13.3333 3.16732 14.6666 6.50065 14.6666Z" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.49935 6.66667C7.23573 6.66667 7.83268 6.06971 7.83268 5.33333C7.83268 4.59695 7.23573 4 6.49935 4C5.76297 4 5.16602 4.59695 5.16602 5.33333C5.16602 6.06971 5.76297 6.66667 6.49935 6.66667Z" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.2793 12.6334L5.56596 10.4267C6.09263 10.0734 6.85263 10.1134 7.32596 10.52L7.54596 10.7134C8.06596 11.16 8.90596 11.16 9.42596 10.7134L12.1993 8.33335C12.7193 7.88669 13.5593 7.88669 14.0793 8.33335L15.166 9.26669" stroke="#CA0013" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> Alla foton</a>')
    },1000)

    //$( 'body' ).on('click',function () {
    //    setTimeout(function () {
    //            $('body').find('.litepicker .container__main').append('<div class="dop-wrap"><div class="nights"><div><label for="nights"><?php //echo $interval->days;?>// NIGHTS</label></div><div><?php //echo date("d.m.Y",strtotime($date_start)).' - '.date('d.m.Y', strtotime($date_end));?>//</div></div><div class="inout"><div><label style="font-width: 800;">Incheckning</label><label><?php //echo $date_start; ?>//</label></div><div style="border-left: 1px solid #CDCDD2; padding-left: 10px;"><label style="font-width: 800;">Utcheckning</label><label><?php //echo $date_end; ?>//</label></div></div></div>')
    //        }, 10)
    //})

    $('.flex-control-nav li img').on('click',function () {
        setTimeout( function () {
            $.each($('.flex-control-nav li img'), function (index,value) {
                // console.log(index)
                // console.log($(this).hasClass('flex-active-slide'))
                if ($(this).hasClass('flex-active') === true){
                    console.log('true'+index)
                }
            })
        },1000)
    })


</script>

<script>
    $("body").on('click','.add-to-cart', function (e) {
        let classBuy = $(this).hasClass('notBuy')
        if (classBuy){
            $(this).css('color','white')
            return false;

        }
        e.preventDefault();
        let custom_price = $(this).attr('data-custom_price');
        let product_id = $(this).attr('data-product_id');
        let add_button = $(this);
        let date_from = $("#startDateNew").val()
        let date_to = $("#endDateNew").val()
        let personsA = $("#num-adult").val()
        let personsC = $("#num-child").val()
        let persons = parseInt(personsA) + parseInt(personsC)


        // console.log(product_id)
        // console.log(custom_price)
        // console.log(date_from)
        // console.log(date_to)
        // console.log(persons)

        const ajaxFormData = {
            product_id: product_id,
            custom_price: custom_price,
            date_from:date_from,
            date_to:date_to,
            personsA:personsA,
            personsC:personsC,
            action: 'addtocart'
        };
        const accompanied_dog = $('#animals');
        if(accompanied_dog.length > 0 && accompanied_dog.is(':checked')){
            ajaxFormData.accompanied_dog = accompanied_dog.val()
        }

        $.ajax({
            type: 'POST',
            url: site_url + '/wp-admin/admin-ajax.php',
            data: ajaxFormData,
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
                // console.log(data)
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
    })

</script>

<script>
    (function($){
        <?php if(!empty($_SERVER['HTTP_REFERER']) && (mb_strpos($_SERVER['HTTP_REFERER'], get_permalink()) !== false || mb_strpos($_SERVER['HTTP_REFERER'], 'prislistor-salen'))): ?>
        $(window).load(function(){
            if(location.search.length > 1){
                const searchParams = new URLSearchParams(location.search.slice(1));
                if(searchParams.has('date_start')){
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".single-product-sidebar.period").offset().top-120
                    }, 200);
                }
            }
        });
        <?php endif; ?>

        $(document).ready(function(){
            const $body = $('body');

            $body.on('click', '.pricelist-toggle-button', function(){
                if(!$(this).hasClass('active')){
                    $body.find('.pricelist-toggle-button.active').removeClass('active')
                    $(this).addClass('active')
                }
                else{
                    $(this).removeClass('active');
                }

                const $activePeriod = $body.find('.pricelist-toggle-button.active');
                if($activePeriod.length > 0){
                    console.log($activePeriod.data('period'));
                    $('.pricelist-period-tr').each(function(){
                        const m = parseInt($(this).data('start').split('-')[1]);
                        console.log($(this));
                        console.log(m);
                        if($activePeriod.data('period') === 'summer'){
                            console.log('summer')
                            if(m >= 6 && m <= 10){
                                $(this).show();
                                console.log('summer show')
                            }
                            else{
                                $(this).hide();
                                console.log('summer hide')
                            }
                        }
                        else{
                            console.log('winter')
                            if(m >= 6 && m <= 10){
                                console.log('winter hide')
                                $(this).hide();
                            }
                            else{
                                console.log('winter show')
                                $(this).show();
                            }
                        }

                    })
                }
                else{
                    $('.pricelist-period-tr').show();
                }
            });
            $body.find('.pricelist-toggle-button:first').click();

            $body.on('click', '.mobile-calendar-btn-updated-btn', function(){
                $('#date-3_1').click()
            })

        });

    })(jQuery)

</script>