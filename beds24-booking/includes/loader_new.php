<?php
/**
 * connect files
 */
require_once BEDS_DIR . '/includes/ajax.php';
require_once BEDS_DIR . '/includes/shortcodes.php';
require_once BEDS_DIR . '/includes/constants.php';
/**
 * register scripts|styles
 */
add_action('wp_enqueue_scripts', 'beds_register_scripts');
function beds_register_scripts(){
    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', BEDS_URL.'assets/js/jquery-3.6.2.min.js', false, false, true);
    // wp_register_style('beds-bootstrap-style' , BEDS_URL.'assets/css/bootstrap.min.css');
    wp_register_style('beds-litepicker-style' , BEDS_URL.'assets/css/litepicker.css');
    wp_register_style('beds-slick-style' , BEDS_URL.'assets/css/slick.css');
    wp_register_style('beds-slick_theme-style' , BEDS_URL.'assets/css/slick-theme.css');
    wp_register_style('beds-checkout-style' , BEDS_URL.'assets/css/checkout_styles.css');
    wp_register_style('beds-register-style' , BEDS_URL.'assets/css/style.css');
    wp_register_style('beds-hotel-style' , BEDS_URL.'assets/css/hotel.css');
    wp_register_style('beds-wishlist',BEDS_URL.'assets/css/wishlist.css');
    wp_register_script('beds-cookie-script',BEDS_URL.'assets/js/js.cookie.min.js');
    wp_register_script('beds-filters-script',BEDS_URL.'assets/js/filters.js');
    wp_register_script('beds-buttons-script', BEDS_URL.'assets/js/buttons.js', array('jquery'));
    wp_register_script('beds-moment-script', BEDS_URL.'assets/js/moment.min.js', array('jquery'));
    wp_register_script('beds-litepicker-script', BEDS_URL.'assets/js/litepicker.js', array('jquery'),false,false);
    wp_register_script('beds-slick-script', BEDS_URL.'assets/js/slick.min.js', array('jquery'));
    wp_register_script('beds-fslightbox-script', BEDS_URL.'assets/js/fslightbox.js', array('jquery'));
    wp_register_script('beds-hotel_inner-script', BEDS_URL.'assets/js/hotel_inner.js', array('jquery'));
    wp_register_script('beds-product-script', BEDS_URL.'assets/js/product.js', array('jquery'));
    wp_register_script('beds-register-script', BEDS_URL.'assets/js/script.js', array('jquery'));
    wp_register_script('beds-cart-script', BEDS_URL.'assets/js/cart.js', array('jquery'));
    wp_register_script('beds-script_home-script', BEDS_URL.'assets/js/script_home.js', array('jquery'));
    wp_register_script('beds-wishlist-script', BEDS_URL.'assets/js/wishlist.js', array('jquery'));
    wp_localize_script('beds-register-script', 'beds_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
}
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
function woocommerce_product_custom_fields(){
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    //Custom Product Number Field
    woocommerce_wp_text_input( 
        array(
            'id'        => '_product_breadcrumbs',
            'desc'      => __('Neighborhood', 'woocommerce'),
            'label'     => __('Neighborhood', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_text_input(
        array(
            'id' => '_product_beds_id',
            'placeholder' => 'Beds id',
            'label' => __('Beds id', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    //Custom Product Number Field
    woocommerce_wp_text_input(
        array(
            'id' => '_product_peoples',
            'placeholder' => 'Adults',
            'label' => __('Adults', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    // add child
    woocommerce_wp_text_input(
        array(
            'id' => '_children',
            'placeholder' => 'Children',
            'label' => __('Children', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_sovrum',
            'placeholder' => 'Sovrum',
            'label' => __('Sovrum', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '1'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_skidlift',
            'placeholder' => 'Skidlift',
            'label' => __('Skidlift', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '1'
            )
        )
    );
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_hundtillåtet',
            'desc'      => __('Hundtillåtet', 'woocommerce'),
            'label'     => __('Hundtillåtet', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_dubbelsang',
            'desc'      => __('Dubbelsäng', 'woocommerce'),
            'label'     => __('Dubbelsäng', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_laddning_elbil',
            'desc'      => __('Laddning Elbil', 'woocommerce'),
            'label'     => __('Laddning Elbil', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_wi_fi',
            'desc'      => __('WI-FI', 'woocommerce'),
            'label'     => __('WI-FI', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_bastu',
            'desc'      => __('Bastu', 'woocommerce'),
            'label'     => __('Bastu', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_oppen_spis',
            'desc'      => __('Öppen spis', 'woocommerce'),
            'label'     => __('Öppen spis', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_skidförråd',
            'desc'      => __('Skidförråd', 'woocommerce'),
            'label'     => __('Skidförråd', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_diskmaskin',
            'desc'      => __('Diskmaskin', 'woocommerce'),
            'label'     => __('Diskmaskin', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_tvättmaskin',
            'desc'      => __('Tvättmaskin', 'woocommerce'),
            'label'     => __('Tvättmaskin', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_torkskåp',
            'desc'      => __('Torkskåp', 'woocommerce'),
            'label'     => __('Torkskåp', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_barnstol',
            'desc'      => __('Barnstol', 'woocommerce'),
            'label'     => __('Barnstol', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_barnsäng',
            'desc'      => __('Barnsäng', 'woocommerce'),
            'label'     => __('Barnsäng', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    // new fields
    woocommerce_wp_text_input(
        array(
            'id' => '_product_boyta',
            'placeholder' => 'Boyta',
            'label' => __('Boyta', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_baddar',
            'placeholder' => 'Bäddar',
            'label' => __('Bäddar', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_dusch',
            'placeholder' => 'Dusch',
            'label' => __('Dusch', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_wc',
            'placeholder' => 'WC',
            'label' => __('WC', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_tv',
            'desc'      => __('TV', 'woocommerce'),
            'label'     => __('TV', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
//    woocommerce_wp_text_input(
//        array(
//            'id' => '_product_tv',
//            'placeholder' => 'TV',
//            'label' => __('TV', 'woocommerce'),
//            'type' => 'number',
//            'custom_attributes' => array(
//                'step' => 'any',
//                'min' => '0'
//            )
//        )
//    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_skidbuss',
            'placeholder' => 'Skidbuss',
            'label' => __('Skidbuss', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_langdspar',
            'placeholder' => 'Längdspår',
            'label' => __('Längdspår', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_matbutik',
            'placeholder' => 'Matbutik',
            'label' => __('Matbutik', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_restaurang',
            'placeholder' => 'Restaurang',
            'label' => __('Restaurang', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_salens_by',
            'placeholder' => 'Sälens by',
            'label' => __('Sälens by (km)', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_sommar',
            'desc'      => __('Sommar', 'woocommerce'),
            'label'     => __('Sommar', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_kyl_frys',
            'desc'      => __('Kyl/frys', 'woocommerce'),
            'label'     => __('Kyl/frys', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
    woocommerce_wp_checkbox(
        array(
            'id'        => '_product_mikro',
            'desc'      => __('Mikro', 'woocommerce'),
            'label'     => __('Mikro', 'woocommerce'),
            'desc_tip'  => 'true'
        ));
    echo '</div>';
}
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id){
//    foreach ($_POST as $key => $val){
//        var_dump($val);
//        if ($key == '_product_hundtillåtet'){
//            update_post_meta($post_id, '_product_hundtillåtet', esc_attr($val));
//        } else {
//            update_post_meta($post_id, '_product_hundtillåtet', '');
//        }
//        if ($key == '_product_wi_fi'){
//            update_post_meta($post_id, '_product_wi_fi', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_wi_fi', '');
//        }
//        if ($key == '_product_bastu'){
//            update_post_meta($post_id, '_product_bastu', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_bastu', '');
//        }
//        if ($key == '_product_oppen_spis'){
//            update_post_meta($post_id, '_product_oppen_spis', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_oppen_spis', '');
//        }
//        if ($key == '_product_skidförråd'){
//            update_post_meta($post_id, '_product_skidförråd', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_skidförråd', '');
//        }
//        if ($key == '_product_diskmaskin'){
//            update_post_meta($post_id, '_product_diskmaskin', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_diskmaskin', '');
//        }
//        if ($key == '_product_tvättmaskin'){
//            update_post_meta($post_id, '_product_tvättmaskin', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_tvättmaskin', '');
//        }
//        if ($key == '_product_torkskåp'){
//            update_post_meta($post_id, '_product_torkskåp', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_torkskåp', '');
//        }
//        if ($key == '_product_barnstol'){
//            update_post_meta($post_id, '_product_barnstol', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_barnstol', '');
//        }
//        if ($key == '_product_barnsäng'){
//            update_post_meta($post_id, '_product_barnsäng', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_barnsäng', '');
//        }
//        if ($key == '_product_breadcrumbs'){
//            update_post_meta($post_id, '_product_breadcrumbs', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_breadcrumbs', '');
//        }
//        if ($key == '_product_skidlift'){
//            update_post_meta($post_id, '_product_skidlift', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_skidlift', '');
//        }
//        if ($key == '_product_sovrum'){
//            update_post_meta($post_id, '_product_sovrum', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_sovrum', '');
//        }
//        if ($key == '_product_peoples'){
//            update_post_meta($post_id, '_product_peoples', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_peoples', '');
//        }
//        if ($key == '_product_beds_id' and isset($val)){
//            var_dump(get_post_meta($post_id,'_product_beds_id', true));
//            var_dump(esc_attr($val));
//            update_post_meta($post_id, '_product_beds_id', esc_attr($val));
//        }else {
//            update_post_meta($post_id, '_product_beds_id', '');
//        }
//    }
//    var_dump(get_post_meta($post_id,'_product_beds_id', true));
    $woocommerce_custom_product_number_field = $_POST['_product_beds_id'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_beds_id', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_beds_id','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_peoples'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_peoples', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_peoples','');
    }
    $woocommerce_custom_product_number_field = $_POST['_children'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_children', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_children','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_sovrum'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_sovrum', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_sovrum','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_skidlift'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_skidlift', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_skidlift','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_hundtillåtet'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_hundtillåtet', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_hundtillåtet','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_dubbelsang'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_dubbelsang', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_dubbelsang','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_laddning_elbil'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_laddning_elbil', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_laddning_elbil','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_wi_fi'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_wi_fi', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_wi_fi','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_bastu'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_bastu', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_bastu','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_oppen_spis'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_oppen_spis', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_oppen_spis','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_skidförråd'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_skidförråd', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_skidförråd','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_diskmaskin'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_diskmaskin', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_diskmaskin','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_tvättmaskin'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_tvättmaskin', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_tvättmaskin','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_torkskåp'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_torkskåp', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_torkskåp','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_barnstol'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_barnstol', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_barnstol','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_barnsäng'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_barnsäng', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_barnsäng','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_breadcrumbs'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_breadcrumbs', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_breadcrumbs','');
    }
    //new
    $woocommerce_custom_product_number_field = $_POST['_product_boyta'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_boyta', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_boyta','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_baddar'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_baddar', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_baddar','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_dusch'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_dusch', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_dusch','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_wc'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_wc', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_wc','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_tv'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_tv', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_tv','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_skidbuss'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_skidbuss', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_skidbuss','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_langdspar'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_langdspar', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_langdspar','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_matbutik'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_matbutik', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_matbutik','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_restaurang'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_restaurang', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_restaurang','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_salens_by'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_salens_by', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_salens_by','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_sommar'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_sommar', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_sommar','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_kyl_frys'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_kyl_frys', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_kyl_frys','');
    }
    $woocommerce_custom_product_number_field = $_POST['_product_mikro'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_mikro', esc_attr($woocommerce_custom_product_number_field));
    } else {
        update_post_meta($post_id, '_product_mikro','');
    }
}
add_filter( 'cron_schedules', 'every_30_minutes' );
function every_30_minutes( $schedules ) {
    $schedules['every_30'] = array(
            'interval'  => 60 * 30,
            'display'   => __( 'Every 30 Minutes', 'textdomain' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'every_10_second' );
function every_10_second( $schedules ) {
    $schedules['ten_sec'] = array(
        'interval'  => 10,
        'display'   => __( 'Every 10 Second', 'textdomain' )
    );
    return $schedules;
}
// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'every_30_minutes' ) ) {
    wp_schedule_event( time(), 'every_30', 'every_30_minutes' );
}
// Hook into that action that'll fire every 30 minutes
add_action( 'every_30_minutes', 'every_30_minutes_func' );
function every_30_minutes_func() {
    global $wpdb;
    $table = 'beds_properties';
    $rooms = $wpdb->get_results( "SELECT * FROM $table"); 
//    foreach ($rooms as $room) {
//        $product_id = 0;
//        $room_id = intval($room->roomId);
//        $regular_price = floatval($room->minPrice);
//        $peoples = intval($room->maxPeople);
//
//        $products = get_posts(array(
//            'post_type' => 'product',
//            'posts_per_page' => -1,
//            'meta_query' => array(
//                array(
//                   'key'     => '_product_beds_id',
//                   'value'   => $room_id,
//                   'compare' => 'LIKE'
//                )
//             ),
//        ));
//        if(empty($products)){
//
//            $post = array(
//                'post_author' => 1,
//                'post_content' => $room->propertyDescriptionBookingPage1en,
//                'post_status' => "publish",
//                'post_title' => $room->nameRoom,
//                'post_type' => "product"
//            );
//            $post_id = wp_insert_post($post);
//            $product_id = $post_id;
//            $product = wc_get_product( $post_id );
//            update_post_meta( $post_id, '_visibility', 'visible' );
//            update_post_meta( $post_id, '_downloadable', 'no');
//            update_post_meta( $post_id, '_virtual', 'no');
//            update_post_meta( $post_id, '_visibility', 'visible' );
//            update_post_meta( $post_id, '_product_beds_id', $room_id );
//            update_post_meta( $post_id, '_product_peoples',  $peoples);
//            update_post_meta( $post_id, '_regular_price', $regular_price);
//            update_post_meta($post_id, '_sale_price', $regular_price -1);
//            $product->set_regular_price( $peoples );
//
//            wp_set_object_terms($post_id, "simple", 'product_type');
//
//        }else{
//            $_product_id = 0;
//            foreach($products as $product){
//                $_product_id = $product->ID;
//            }
//            $product_id = $_product_id;
//            $data = array(
//                    'ID' => $_product_id,
//                    'post_content' => $room->propertyDescriptionBookingPage1en,
//                );
//            $product = wc_get_product( $_product_id );
//            update_post_meta( $post_id, '_visibility', 'visible' );
//            update_post_meta( $post_id, '_downloadable', 'no');
//            update_post_meta( $post_id, '_virtual', 'no');
//            update_post_meta( $post_id, '_visibility', 'visible' );
//            update_post_meta( $_product_id, '_product_beds_id', $room_id );
//            update_post_meta( $_product_id, '_product_peoples',  $peoples);
//            $product->set_regular_price( $peoples );
//
//            wp_update_post( $data );
//        }
//
//        // images uploading code start
//        $attachment_ids = [];
//        if($room->images != NULL){
//            $images = explode(",", $room->images);
//            foreach($images as $image){
//                $url = $image;
//                $post_name = basename( $url );
//                $attach_name = explode(".", $post_name);
//                $attach_name = $attach_name[0];
//                $attachment = wp_get_attachment_by_post_name( $attach_name );
//                if ( $attachment ) {
//                    // if attachment is exist
//                    array_push($attachment_ids, $attachment->ID);
//                }else{
//                    // download and create attachment
//                    require_once ABSPATH . 'wp-admin/includes/image.php';
//                    require_once ABSPATH . 'wp-admin/includes/file.php';
//                    require_once ABSPATH . 'wp-admin/includes/media.php';
//
//                    $desc = "Beads Image";
//                    $tmp = download_url( $url );
//
//                    $file_array = [
//                        'name'     => basename( $url ),
//                        'tmp_name' => $tmp
//                    ];
//
//                    $attachment_id = media_handle_sideload( $file_array, 0 );
//                    array_push($attachment_ids, $attachment_id);
//                    @unlink( $tmp );
//                }
//
//            }
//        }
//        if(!empty($attachment_ids)){
//            set_post_thumbnail($product_id, $attachment_ids[0]);
//            update_post_meta($product_id, '_product_image_gallery', implode(',',$attachment_ids));
//        }
//        // images uploading code end
//    }
}
// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_beds_daily_event' ) ) {
    wp_schedule_event( time(), 'daily', 'my_beds_daily_event' );
}
/**
 * daily add +1 day in calendar in every prop
 */
add_action('my_beds_daily_event', 'my_beds_daily_event_function');
function my_beds_daily_event_function()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $date = date('Y-m-d', strtotime('+ 1 year'));
    $act->setDataInCalendar($date,$date);
//    add deposit email, check every day, sent every 3 day
}
// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_beds_hourly_event' ) ) {
    wp_schedule_event( time(), 'hourly', 'my_beds_hourly_event' );
}
/**
 * hourly update availability & prices by 3 month ahead
 */
add_action('my_beds_hourly_event', 'my_beds_hourly_event_function');
function my_beds_hourly_event_function()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime('+3 months'));
    $act->setDataInCalendar($startDate,$endDate);
    $act->updateCalendar();
}
if ( ! wp_next_scheduled( 'my_beds_10_sec_event' ) ) {
    wp_schedule_event( time(), 'ten_sec', 'my_beds_10_sec_event' );
}
add_action('my_beds_10_sec_event','my_beds_10_sec_event_func');
function my_beds_10_sec_event_func()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $act->clearAPIIter();
}
register_activation_hook( __FILE__, 'beds_plugin_activate' );
register_deactivation_hook( __FILE__, 'beds_plugin_deactivate' );
function beds_plugin_activate()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $act->createTokenTable();
    $act->createCalendarTable();
    $act->createMainTable();
    $act->createPriceRulesTable();
    $act->refreshToken();
    $act->setDataInCalendar();
    $act->setDataInPropTable();
}
function beds_plugin_deactivate()
{
    //
}
if( ! ( function_exists( 'wp_get_attachment_by_post_name' ) ) ) {
function wp_get_attachment_by_post_name( $post_name ) {
$args = array(
'posts_per_page' => 1,
'post_type' => 'attachment',
'name' => trim( $post_name ),
);
$get_attachment = new WP_Query( $args );
if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
return false;
}
return $get_attachment->posts[0];
}
}
function check_date_noavaible($room_id=NULL,$date_start=NULL,$date_end=NULL){
    global $wpdb;
    $table = 'beds_calendar';
    if (isset($date_start) and !empty($date_start)){
        $date_start = date('Y-m-d', strtotime($date_start . ' +1 day'));
    }
    if (isset($date_end) and !empty($date_end)){
        $date_end = date('Y-m-d', strtotime($date_end . ' -1 day'));
    }
    $results = $wpdb->get_results( "SELECT roomId FROM $table WHERE date BETWEEN '$date_start' AND '$date_end' AND avaliable = 0 AND roomId = '$room_id'");
    return $results;
}

function delete_options($cart_item_key=0){
    foreach ( WC()->cart->get_cart() as $cart_item_id => $cart_item ){
        if($cart_item_key == $cart_item_id){

            if(isset($cart_item['accompanied_dog'])){ unset($cart_item['accompanied_dog']); }
            if(isset($cart_item['final_cleaning'])){ unset($cart_item['final_cleaning']); }
            if(isset($cart_item['final_cleaning_rut'])){ unset($cart_item['final_cleaning_rut']); }
            if(isset($cart_item['cancellation'])){ unset($cart_item['cancellation']); }

            WC()->cart->cart_contents[$cart_item_key] = $cart_item;
        }
    }
    WC()->cart->set_session();
    WC()->cart->calculate_totals();
}

function woocommerce_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            $price = $value['data']->get_price();
            if( isset( $value["custom_price"] ) ) {
                $price = $value["custom_price"];
            }

            if( isset( $value["options_sum"] ) ) {
                $price = $value["options_sum"]+$value["custom_price"];
            }

            if(isset($_COOKIE['foreign_guests']) && is_numeric($_COOKIE['foreign_guests'])){
               $price += $_COOKIE['foreign_guests'];
            }

            $value['data']->set_price($price);

            /*if (isset($value['persons_adult'])){
                if (isset($_POST['personsA'])){
                    $value['persons_adult'] = (int)$_POST['personsA'];
                }
            }
            if (isset($value['persons_child'])){
                if (isset($_POST['personsC'])){
                    $value['persons_child'] = (int)$_POST['personsC'];
                }
            }*/
        }
    }  
}
add_action( 'woocommerce_before_calculate_totals', 'woocommerce_custom_price_to_cart_item', 99 );
//woocommerce_new_order
//add_action( 'woocommerce_order_status_changed', 'setParamsToBedsAndDB',10,3);
//function setParamsToBedsAndDB( $order_id, $oldStatus, $newStatus ) {
//    global $wpdb;
//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    if ($newStatus == 'completed' and $oldStatus == 'partially-paid'){
//        $re = $act->confirmPartial($order_id);
//    }
////    if ($newStatus == 'completed' and $oldStatus != 'partially-paid')
//     else {
//        $re = $act->setBookingOnAPI($order_id);
//        $order = wc_get_order($order_id);
//        foreach ($order->get_items() as $item_id => $item) {
//            $d = $item->get_data();
//            $from = $item->get_meta('booked_from');
//            $to = $item->get_meta('booked_to');
//            $roomID = get_post_meta($d['product_id'], '_product_beds_id', true);
//            $res = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$roomID'");
//            unsetReserve($res->idBookings, $res->id);
////        }
//        update_post_meta($order_id, 'request_api_res', $re);
//    }
//    if ($newStatus == 'partially-paid'){
//        $re = $act->setPartialBookingOnAPI($order_id);
//        $order = wc_get_order($order_id);
//        foreach ($order->get_items() as $item_id => $item) {
//            $from = $item->get_meta('booked_from');
//            $prodId = $item->get_meta('product_id');
//            $to = $item->get_meta('booked_to');
//            $roomID = get_post_meta($prodId, '_product_beds_id', true);
//            $res = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and dateReserved='$roomID'");
//            unsetReserve($res->idBookings, $res->id);
//        }
//        update_post_meta($order_id, 'request_api_res', $re);
//    }
//}
function beds24_menu_page()
{
    require_once(BEDS_DIR . '/views/admin-page.php');
}
function pricelist_weeks_page() {
    require_once(BEDS_DIR . '/views/admin-page-weeks.php');
}

function pricrlistSettings(){
    require_once(BEDS_DIR . '/views/admin-page-big-pricelist.php');
}

function beds24BookingTable(){
    require_once(BEDS_DIR . '/views/orders-table.php');
}

function beds24ProductsCodesPage(){
    require_once(BEDS_DIR . '/views/admin-page-product-codes.php');
}

function register_beds24_menu_page()
{
     add_menu_page('Beds24 Settings','Beds24 Settings', 'manage_options', 'beds24-settings', 'beds24_menu_page');
     // Submenu page
    add_submenu_page(
        'beds24-settings',   // Parent slug
        'Pricelist Weeks',     // Page title
        'Pricelist Weeks',     // Menu title
        'manage_options',    // Capability
        'pricelist-weeks',     // Menu slug
        'pricelist_weeks_page' // Function to display the page content
    );

    add_submenu_page('beds24-settings', 'Big pricelist settings', 'Big pricelist','manage_options','big-pricelist-settings','pricrlistSettings');
    add_submenu_page('beds24-settings', 'Booking table', 'Booking table','manage_options','beds24-booking-table','beds24BookingTable');
    add_submenu_page('beds24-settings', 'Codes', 'Codes','manage_options','beds24-products-codes','beds24ProductsCodesPage');
}
add_action( 'admin_menu', 'register_beds24_menu_page' );
function beds24_bad_order_admin_page()
{
    require_once(BEDS_DIR . '/views/admin-page-wc.php');
}
function register_beds24_bad_order_admin_page()
{
    global $wpdb;
    $table = $wpdb->prefix.'postmeta';
    $res = $wpdb->get_results("select * from $table WHERE `meta_key` = 'request_api_res'");
    $notif = 0;
    foreach ($res as $re) {
        if (empty($re->meta_value)){
            $notif++;
        }
        else{
            $resApiObj = json_decode($re->meta_value)[0];
            $apiSuccess = $resApiObj->success;
            if (!$apiSuccess){
                $notif++;
            }
        }
    }
    add_submenu_page('woocommerce','Beds24 failed order',$notif ? sprintf( 'Beds24 failed order <span class="awaiting-mod">%d</span>', $notif ) : 'Beds24 failed order', 'manage_options', 'beds24-bad-orders', 'beds24_bad_order_admin_page');
}
add_action( 'admin_menu', 'register_beds24_bad_order_admin_page' );
add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_data_as_order_item_meta_data', 20, 4 );
function save_cart_item_data_as_order_item_meta_data( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['booked_from'] ) ) {
        $item->update_meta_data( __( 'booked_from'), $values['booked_from'] );
    }
    if ( isset( $values['booked_to'] ) ) {
        $item->update_meta_data( __( 'booked_to'), $values['booked_to'] );
    }
    if ( isset( $values['persons'] ) ) {
        $item->update_meta_data( __( 'persons'), $values['persons'] );
    }
    if (isset($values['persons_adult'])){
        $item->update_meta_data( __( 'persons_adult'), $values['persons_adult'] );
    }
    if (isset($values['persons_child'])){
        $item->update_meta_data( __( 'persons_child'), $values['persons_child'] );
    }
    if (isset($values['accompanied_dog'])){
        $item->update_meta_data( __( 'Hund'), wc_price($values['accompanied_dog']) );
    }
    if (isset($values['final_cleaning'])){
        $item->update_meta_data( __( 'Avresestädning'), wc_price($values['final_cleaning']) );
    }

    $item->update_meta_data( __( 'Avresestädning with RUT'), (int)isset($values['final_cleaning_rut']));

    if(!empty($_COOKIE['foreign_guests'])){
        $item->update_meta_data( __( 'Deposition'), wc_price($_COOKIE['foreign_guests']));
    }

    if (isset($values['cancellation'])){
        $item->update_meta_data( __( 'Avbokningskydd'), wc_price($values['cancellation']) );
    }
}
add_action('woocommerce_cart_calculate_fees' , 'add_custom_fees');
function add_custom_fees( WC_Cart $cart ){
    // if(isset($_COOKIE['accompanied_dog'])){
    //     $fees = $_COOKIE['accompanied_dog'];
    //     $fees = floatval($fees);
    //     $dog = 'Dog';
    //     if (get_locale() == 'sv_SE'){
    //         $dog = 'Hund';
    //     }
    //     $cart->add_fee( $dog, $fees);
    // }
    // if(isset($_COOKIE['cancellation'])){
    //     $fees = $_COOKIE['cancellation'];
    //     $fees = floatval($fees);
    //     $canc = 'Cancellation insurance';
    //     if (get_locale() == 'sv_SE'){
    //         $canc = 'Avbeställningsförsäkring';
    //     }
    //     $cart->add_fee( 'Cancellation insurance', $fees);
    // }
    // if(isset($_COOKIE['final_cleaning'])){
    //     $fees = $_COOKIE['final_cleaning'];
    //     $fees = floatval($fees);
    //     $fin = 'Final cleaning';
    //     if (get_locale() == 'sv_SE'){
    //         $fin = 'Slutstädning';
    //     }
    //     $cart->add_fee( $fin, $fees );
    // }
    /*if (isset($_COOKIE['foreign_guests'])){
        $fees = $_COOKIE['foreign_guests'];
//        $fees = floatval($fees);
        $guest = 'Security deposit';
        if (get_locale() == 'sv_SE'){
            $guest = 'Deposition';
        }
        $cart->add_fee( $guest, $fees );
    }*/
}
add_filter('woocommerce_add_cart_item_data', 'addCartItemData', 10, 3);
function addCartItemData($cart_item, $product_id, $variation_id) {
    global $woocommerce;
    $product_id = $variation_id > 0 ? $variation_id : $product_id;
    if (isset($_POST['personsA'])) {
            $cart_item['persons_adult'] = $_POST['personsA'];
    }
    if (isset($_POST['personsC'])){
            $cart_item['persons_child'] = $_POST['personsC'];
    }
    return $cart_item;
}
add_action('woocommerce_checkout_before_terms_and_conditions', 'checkout_additional_checkboxes');
function checkout_additional_checkboxes( ){
    $checkbox_text = __( "Är du över 25 år? Vi har 25-års gräns på alla våra boenden. Bokningen kommer att makuleras om uppgiften är felaktig", "woocommerce" );
    $text = "";
    if (get_locale() == 'sv_SE'){
        $text = "Jag är över 25 år och godkänner <a href='/hyresvillkor/'>hyresvillkoren</a>. Vi har 25-års gräns på alla våra boenden.";
    } else{
        $text = "I am over 25 years old and agree to the <a href='/rental-conditions/?lang=en'>rental conditions</a>. We have a 25-year limit on all our accommodations.";
    }
    ?>
    <div class="check-list-item" style="display: flex;justify-content: center; align-items: flex-start;">
        <label class="switcher-container" style="flex: 0">
            <input style="display: none" type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="custom_one" >
            <span class="switchmark"></span>

<!--            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="custom_one" > <span>--><?php //echo  $checkbox_text; ?><!--</span> <span class="required">*</span>-->
        </label>
        <span><?php echo $text;?></span>
    </div>
    <?php
}
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
function my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( ! $_POST['custom_one'] )
        wc_add_notice( __( 'You must accept "Är du över 25 år?".' ), 'error' );
}
add_action( 'woocommerce_remove_cart_item', 'beds_woocommerce_remove_cart_item_action', 10, 2 );
/**
 * Function for `woocommerce_remove_cart_item` action-hook.
 * Delete accommodation form reserve, change available and send api req with status "Cancelled"
 *
 * @param  $cart_item_key
 * @param  $that
 *
 * @return void
 */
function beds_woocommerce_remove_cart_item_action( $cart_item_key, $that ){
    // action...
}
function setReserve($product_id,$date_from,$date_to,$persons_A, $persons_C)
{
    global $wpdb;
    date_default_timezone_set('Europe/Stockholm');
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $roomID = get_post_meta($product_id,'_product_beds_id',true);
    /**
     * set noAvavil status to db for reserved dates
     */
    $notAvail = array();

        $begin = new DateTime( $date_from);
        $end = new DateTime( $date_to);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            $date = $dt->format('Y-m-d');
            array_push($notAvail,$date);

        }

    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $act->updateAvailByRoom($roomID,$notAvail);

  
    
    $now = time();
//    require_once BEDS_DIR . '/includes/class.action.php';
    $res = $wpdb->query("insert into `beds_reserved` (roomId,dateFrom,dateTo,dateReserved,ip) values ('$roomID','$date_from','$date_to','$now','$ip')");
    //change available and send api req with status "New"
//    $act = new \beds_booking\Action_beds_booking();
    $act->reserveInAPI($product_id,$date_from,$date_to,$persons_A, $persons_C,$now);
    unset($_COOKIE['accompanied_dog']);
    setcookie('accompanied_dog', '', time() - 3600, '/');
    unset($_COOKIE['cancellation']);
    setcookie('cancellation', '', time() - 3600, '/');
    unset($_COOKIE['final_cleaning']);
    setcookie('final_cleaning', '', time() - 3600, '/');
    unset($_COOKIE['foreign_guests']);
    setcookie('foreign_guests', '', time() - 3600, '/');
}
if ( ! wp_next_scheduled( 'every_day_deposit_check' ) ) {
    wp_schedule_event( time(), 'daily', 'every_day_deposit_check' );
}
add_action('every_day_deposit_check','every_day_deposit_check_fu');
function every_day_deposit_check_fu()
{
    $args = array(
        'status' => array('wc-partially-paid'),
    );
    $orders = wc_get_orders( $args );
    foreach ($orders as $order) {
        if (get_post_meta($order->get_id(),'_awcdp_deposits_second_payment_paid',true) == 'no'){
            foreach ($order->get_items() as $item) {
                $from = $item->get_meta('booked_from');
//                var_dump($from);
                $from = date_create($from);
                $now = date_create('now');
                $diff = date_diff($from,$now);
                $res = $diff->format('%a');
                if ((int)$res > 35){
                    $lastSend = get_post_meta($order->get_id(),'date_send_remind', true);
                    if (!$lastSend){
                        require_once(BEDS_DIR . '/includes/class.action.php');
                        $act = new \beds_booking\Action_beds_booking();
                        if ($act->sendDepositRemind($order->get_id())){
                            update_post_meta($order->get_id(),'date_send_remind', date('Y-m-d'));
                        }
                    } else {
                        $lastSend = date_create($lastSend);
                        $letterDiff = date_diff($lastSend,$now);
                        if ((int)$letterDiff->format('%a') >= 3){
                            require_once(BEDS_DIR . '/includes/class.action.php');
                            $act = new \beds_booking\Action_beds_booking();
                            if ($act->sendDepositRemind($order->get_id())){
                                update_post_meta($order->get_id(),'date_send_remind', date('Y-m-d'));
                            }
                        }
                    }
                }
            }
        }
    }
}
add_filter( 'cron_schedules', 'every_1_minutes' );
function every_1_minutes( $schedules ) {
    $schedules['every_1_minutes'] = array(
        'interval'  => 60 * 1,
        'display'   => __( 'Every 1 Minutes', 'textdomain' )
    );
    return $schedules;
}
// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'every_1_minutes' ) ) {
    wp_schedule_event( time(), 'every_1_minutes', 'every_1_minutes' );
}
//======================================================================================================================
add_action('every_1_minutes', 'every_1_minutes_deposit_func');
function every_1_minutes_deposit_func()
{
    global $wpdb;
    $t = $wpdb->prefix.'postmeta';
    $res = $wpdb->get_results("SELECT * FROM $t WHERE meta_key = 'deposit_need_update'");
    if ($res) {
        foreach ($res as $re) {
            $order = wc_get_order($re->post_id);
//            $check_api_req = get_post_meta($order->get_id(),'second_pay_api_req',true);
//            if (get_post_meta($order->get_id(),'second_pay_api_req',true)){
//                $check_api_req = get_post_meta($order->get_id(),'second_pay_api_req',true);
//            } else {
//                $check_api_req = 0;
//            }
            $ord_dep_one = $order->get_id()+1;
            $ord_dep_two = $order->get_id()+2;
            $orderOneStatus = wc_get_order($ord_dep_one)->get_status();
            $orderTwoStatus = wc_get_order($ord_dep_two)->get_status();
            
            if (get_post_meta($order->get_id(), '_awcdp_deposits_second_payment_paid', true) == 'yes' and $orderOneStatus == 'completed' and $orderTwoStatus == 'completed') {
                if (get_post_meta($order->get_id(),'deposit_need_update',true) == 'yes'){
                require_once BEDS_DIR . '/includes/class.action.php';
                $act = new \beds_booking\Action_beds_booking();
                $result = $act->confirmPartial($order->get_id());
                update_post_meta($order->get_id(),'second_pay_api_req',$result);
                update_post_meta($order->get_id(),'deposit_need_update','no');
                }
            }
        }
    }
}
add_action( 'every_1_minutes', 'every_1_minutes_func_proccess' );
function every_1_minutes_func_proccess()
{
    global $wpdb;
    $str = '';
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $res = $wpdb->get_results("select * from `beds_reserved`");
    date_default_timezone_set('Europe/Stockholm');
    foreach ($res as $re) {
        $time = strtotime($re->dateReserved);//str
        $now = strtotime("now");//int
        $tweMin = strtotime('+20 minutes', $time);
//        $oneMin= strtotime('+1 minute', $time);
//        if ($now > $oneMin){
            /**
             * processing
             */
//            $args = array('status' => array('wc-processing'), 'limit' => -1, 'type' => 'shop_order');
//            $orders_s = wc_get_orders($args);
//            foreach ($orders_s as $order_s) {
//                foreach ($order_s->get_items() as $item){
//                    $from = $item->get_meta('booked_from');
//                    $to = $item->get_meta('booked_to');
//                    $prod = $item->get_meta('_product_id');
//                    $room = get_post_meta($prod,'_product_beds_id',true);
//                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId){
//                        $needed = $order_s->get_id();
//                        $confirm = get_post_meta($needed, 'request_api_res', true);
////                        if ($confirm){
////                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
////                            unsetReserve($resu->idBookings, $resu->id);
////                        }
//                        if ($needed and empty($confirm)){
////                            $str .= 'type: process, ID = '.$re->id.', time='.date('Y-m-d H:i:s',$time).', now='.date('Y-m-d H:i:s',$now).', 20min='.date('Y-m-d H:i:s',$tweMin);
////
////                            $str .= ', from:'.$from.', to:'.$to.'room='.$room;
////                            $str .= ', itemid='.$needed;
//                            $re_api = $act->setBookingOnAPI($needed);
//                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
////                            $str .= 'result='.serialize($resu).'\\n';
////                            logtofile($str);
//                            unsetReserve($resu->idBookings, $resu->id);
//                            update_post_meta($needed, 'request_api_res', $re_api);
//                        }
//                    }
//                    break;
//                }
//            }
            /**
             * complete
             */
//            $args_c = array('status' => array('wc-completed'), 'limit' => -1, 'type' => 'shop_order');
//            $orders = wc_get_orders($args_c);
//            foreach ($orders as $order) {
//                foreach ($order->get_items() as $item) {
//                    $from = $item->get_meta('booked_from');
//                    $to = $item->get_meta('booked_to');
//                    $prod = $item->get_meta('_product_id');
//                    $room = get_post_meta($prod, '_product_beds_id', true);
////                    $str = 'from.to.prod.room'.$from.'='.$to.'='.$prod.'='.$room;
//                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId) {
//                        $needed_c = $order->get_id();
//                        $confirm = get_post_meta($needed_c, 'request_api_res', true);
////                        $str .= '$needed_c = '.$needed_c.'$confirm = '.$confirm;
////                        if ($confirm){
////                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
////                            unsetReserve($resu->idBookings, $resu->id);
////                        }
//                        if ($needed_c and empty($confirm)){
//                            $re_api = $act->setBookingOnAPI($needed_c);
//                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
////                            $str .= 'type: complete, ID = '.$re->id.', time='.date('Y-m-d H:i:s',$time).', now='.date('Y-m-d H:i:s',$now).', 20min='.date('Y-m-d H:i:s',$tweMin);
////
////                            $str .= ', from:'.$from.', to:'.$to.'room='.$room;
////                            $str .= ', itemid='.$needed_c;
////                            $str .= 'result='.serialize($resu).'\\n';
//                            unsetReserve($resu->idBookings, $resu->id);
//                            update_post_meta($needed_c, 'request_api_res', $re_api);
////                            logtofile($str);
//                        }
//                    }
//                    break;
//                }
//
//            }
            /**
             * partial
             */
//            $args_p = array('status' => array('wc-partially-paid'), 'limit' => -1, 'type' => 'shop_order');
//            $orders_p = wc_get_orders($args_p);
//            foreach ($orders_p as $item_p) {
//                foreach ($item_p->get_items() as $item) {
//                    $from = $item->get_meta('booked_from');
//                    $to = $item->get_meta('booked_to');
//                    $prod = $item->get_meta('_product_id');
//                    $room = get_post_meta($prod, '_product_beds_id', true);
//                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId) {
//                        $needed_p = $item_p->get_id();
//                        $confirm = get_post_meta($needed_p, 'request_api_res', true);
////                            if ($confirm and (get_post_meta($needed_p, 'deposit_need_update',true) == 'yes')){
////                                $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
////                                unsetReserve($resu->idBookings, $resu->id);
////                            }
//                        if ($needed_p and empty($confirm)){
//                            $re_api = $act->setPartialBookingOnAPI($needed_p);
//                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
//                            unsetReserve($resu->idBookings, $resu->id);
//                            update_post_meta($needed_p, 'request_api_res', $re_api);
//                            update_post_meta($needed_p, 'deposit_need_update', 'yes');
//                            break;
//                        }
//                    }
//                    break; // temp, cuz we have 2 items here but need 1 to find needed reserve, and setPartialBookingOnAPI foreach all ord items second time
//                }
//            }
//        }
        if ($now > $tweMin){
            $idBookings = $re->idBookings;
            $idRes = $re->id;
            clearCart();
            unsetReserve($idBookings,$idRes);
//            $str='cleare '.$idRes.', bookID - '.$idBookings;
//            logtofile($str);
//            file_put_contents('tests.log', print_r($str, true), FILE_APPEND);
        }
    }
}
//add_action( 'every_1_minutes', 'every_1_minutes_func_compl' );
//function every_1_minutes_func_compl(){
    /*global $wpdb;
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $res = $wpdb->get_results("select * from `beds_reserved`");
    date_default_timezone_set('Europe/Stockholm');
    $str = '';
    foreach ($res as $re) {
        $time = strtotime($re->dateReserved);//str
        $now = strtotime("now");//int
        $tweMin = strtotime('+20 minutes', $time);
        $needed = '';
        $str = 'time='.date('Y-m-d H:i:s',$time).', now='.date('Y-m-d H:i:s',$now).', 20min='.date('Y-m-d H:i:s',$tweMin);
        $str .= ',+ 1 min='.date('Y-m-d H:i:s',strtotime('+1 minute', $time)).'\\n';
        logtofile($str);
        $str = '';
        if ($now > strtotime('+1 minute', $time)) {
            $args_c = array('status' => array('wc-completed'), 'limit' => -1, 'type' => 'shop_order');
            $orders = wc_get_orders($args_c);
            foreach ($orders as $order) {
                foreach ($order->get_items() as $item) {
                    $from = $item->get_meta('booked_from');
                    $to = $item->get_meta('booked_to');
                    $prod = $item->get_meta('_product_id');
                    $room = get_post_meta($prod, '_product_beds_id', true);
                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId) {
                        $needed = $order->get_id();
                        if ($needed){
                            $re_api = $act->setBookingOnAPI($needed);
                            $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
                            $str .= 'type: complete, ID = '.$re->id.', time='.date('Y-m-d H:i:s',$time).', now='.date('Y-m-d H:i:s',$now).', 20min='.date('Y-m-d H:i:s',$tweMin);
                            $str .= ', from:'.$from.', to:'.$to.'room='.$room;
                            $str .= ', itemid='.$needed;
                            $str .= 'result='.serialize($resu).'\\n';
                            unsetReserve($resu->idBookings, $resu->id);
                            update_post_meta($needed, 'request_api_res', $re_api);
                            logtofile($str);
                        }
                    }
                    break;
                }
            }
        }
        if ($now > $tweMin){
            $idBookings = $re->idBookings;
            $idRes = $re->id;
            clearCart();
            unsetReserve($idBookings,$idRes);
            $str='cleare '.$idRes.', bookID - '.$idBookings;
            logtofile($str);
        }
    }*/
//}
function logtofile($str)
{
    $url = BEDS_DIR . '/log.txt';
    $file = fopen($url,'a');
    fwrite($file, $str);
    fclose($file);
}
/// 1 minute
//add_action( 'every_1_minutes', 'every_1_minutes_func' );
//function every_1_minutes_func(){
//    global $wpdb;
//
//    $str = '';
//
//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    $res = $wpdb->get_results("select * from `beds_reserved`");
//    date_default_timezone_set('Europe/Stockholm');
//    foreach ($res as $re) {
//        $time = strtotime($re->dateReserved);//str
//        $now = strtotime("now");//int
//        $tweMin = strtotime('+20 minutes', $time);
//        $needed_p = '';
//        $needed_p = '';
//        $str .= 'type: part, ID = 'date('Y-m-d H:i:s',$re->id).', time='.date('Y-m-d H:i:s',$time).', now='.date('Y-m-d H:i:s',$now).', 20min='.date('Y-m-d H:i:s',$tweMin);
//        if ($now > strtotime('+1 minute', $time)) {
            /*$args_p = array('status' => array('wc-partially-paid'), 'limit' => -1, 'type' => 'shop_order');
            $orders_p = wc_get_orders($args_p);
            foreach ($orders_p as $item_p) {
                foreach ($item_p->get_items() as $item) {
                    $from = $item->get_meta('booked_from');
                    $to = $item->get_meta('booked_to');
                    $prod = $item->get_meta('_product_id');
                    $room = get_post_meta($prod, '_product_beds_id', true);
                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId) {
                        $needed_p = $item_p->get_id();
                        $re_api = $act->setPartialBookingOnAPI($needed_p);
                        $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
                        unsetReserve($resu->idBookings, $resu->id);
                        update_post_meta($needed_p, 'request_api_res', $re_api);
                        update_post_meta($needed_p, 'deposit_need_update', 'yes');
                    }
                    break; // temp, cuz we have 2 items here but need 1 to find needed reserve, and setPartialBookingOnAPI foreach all ord items second time
                }
            }*/
//        }
        /*if ($now > strtotime('+1 minute', $time)) {
            $args_c = array('status' => array('wc-completed'), 'limit' => -1, 'type' => 'shop_order');
            $orders = wc_get_orders($args_c);
            foreach ($orders as $order) {
                foreach ($order->get_items() as $item) {
                    $from = $item->get_meta('booked_from');
                    $to = $item->get_meta('booked_to');
                    $prod = $item->get_meta('_product_id');
                    $room = get_post_meta($prod, '_product_beds_id', true);
                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId) {
                        $needed = $order->get_id();
                        $re_api = $act->setBookingOnAPI($needed);
                        $resu = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and roomId='$room'");
                        unsetReserve($resu->idBookings, $resu->id);
                        update_post_meta($needed, 'request_api_res', $re_api);
                    }
                    break;
                }
            }
        }*/
//            $args = array('status' => array('wc-partially-paid	'), 'limit' => -1, 'type' => 'shop_order');
//            $orders = wc_get_orders($args);
//            //orderS
//            foreach ($orders as $order){
//                //order 1 == items
//                foreach ($order->get_items() as $item){
//                    //order item 1
//                    $i_id = $item->get_id();
//                    $from = $item->get_meta('booked_from');
//                    $to = $item->get_meta('booked_to');
//                    $prod = $item->get_meta('_product_id');
//                    $room = get_post_meta($prod,'_product_beds_id',true);
//                    if ($from == $re->dateFrom and $to == $re->dateTo and $room == $re->roomId){
//                        $needed = $order->get_id();
//                    }
//                }
//            }
//            if (!empty($needed)){
//                require_once(BEDS_DIR . '/includes/class.action.php');
//                $act = new \beds_booking\Action_beds_booking();
//                $order = new WC_Order( $needed );
//                $newStatus = $order->get_status();
//                if ($newStatus == 'partially-paid'){
//                    $result = $act->setPartialBookingOnAPI($needed);
////                    $order = wc_get_order($order_id);
////                    foreach ($order->get_items() as $item_id => $item) {
////                        $from = $item->get_meta('booked_from');
////                        $prodId = $item->get_meta('product_id');
////                        $to = $item->get_meta('booked_to');
////                        $roomID = get_post_meta($prodId, '_product_beds_id', true);
////                        $res = $wpdb->get_row("select idBookings,id from `beds_reserved` where dateFrom='$from' and dateTo='$to' and dateReserved='$roomID'");
////                        unsetReserve($res->idBookings, $res->id);
////                    }
//                    unsetReserve($re->idBookings, $re->id);
//                    update_post_meta($needed, 'request_api_res', $re);
//                }
//            }
//        }
//        if ($now > $tweMin){
//            $idBookings = $re->idBookings;
//            $idRes = $re->id;
//            clearCart();
////            $str.='cleare';
//            unsetReserve($idBookings,$idRes);
////            file_put_contents('tests.log', print_r($str, true), FILE_APPEND);
//
//        }
//    }
//}
function clearCart(){
    global $wpdb;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $room = get_post_meta($product->get_ID(), '_product_beds_id', true);
        $reserve = $wpdb->get_row("select * from `beds_reserved` where roomId='$room'");
        if (!$reserve){
            $product_cart_id = WC()->cart->generate_cart_id( $product->get_ID() );
            $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
            if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
        }
    }
}
function unsetReserve($idBookings,$idRes)
{
    global $wpdb;
    $post = [
        [
            'id' => $idBookings,
            "status" => "cancelled",
            "comment" => "This is reserved booking that will be cancelled"
        ]
    ];
    require_once BEDS_DIR . '/includes/class.action.php';
    $act = new \beds_booking\Action_beds_booking();
    $re = $act->sendBooking($post);
    $reserve = $wpdb->get_row("select * from `beds_reserved` where id='$idRes'");
    $date_to = $reserve->dateTo;
    $date_from = $reserve->dateFrom;
    $roomID = $reserve->roomId;
    $act->setBookingInDB($date_from,$date_to, $roomID, true);
//    WC()->cart->empty_cart(true);
    $act->emptyCart($roomID,$date_from,$date_to);
    $reserve = $wpdb->query("delete from `beds_reserved` where id='$idRes'");
}
//add_action('acf/init', 'my_acf_op_init');
//function my_acf_op_init() {
//
//    // Check function exists.
//    if( function_exists('acf_add_options_page') ) {
//
//        // Register options page.
//        $option_page = acf_add_options_page(array(
//            'page_title'    => __('Beds24 Options'),
//            'menu_title'    => __('Beds24 Options'),
//            'menu_slug'     => 'beds24-opt',
//            'capability'    => 'edit_posts',
//            'redirect'      => false
//        ));
//    }
//}
function your_function() {
    $locale = get_locale();
    echo '<div class="locale" style="display:none">'.$locale.'</div>';
}
add_action( 'wp_footer', 'your_function' );
//add_action('woocommerce_before_checkout_form','setTotalInSes', 10, 1);
//function setTotalInSes()
//{
//    $total_cart = WC()->cart->get_totals()['total'];
//    $deposit = 1375;
//    session_start();
//    $_SESSION['total'] = $total_cart;
//    $_SESSION['deposit'] = $deposit;
//}
//add_action( 'woocommerce_before_checkout_process', 'initiate_order' , 10, 1 );
//function initiate_order($order_id){
//    global $wpdb;
//    $act = new \beds_booking\Action_beds_booking();
//    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
//        $product = $cart_item['data'];
//        $room = get_post_meta($product->get_ID(),'_product_beds_id',true);
//        $reserve = $wpdb->get_row("select * from `beds_reserved` where roomId='$room'");
//        $date_to = $reserve->dateTo;
//        $date_from = $reserve->dateFrom;
//
//        $act = new \beds_booking\Action_beds_booking();
//        $re = $act->getIsAvailable($room, $date_from, $date_to);
//        $notAvail = array(); // array with dates booked and close
//        if ($re ["success"]){
//            foreach ($re['data'][0]["availability"] as $key => $val) {
//                if (!$val){
//                    array_push($notAvail,$key);
//                }
//            }
//        }
//        if (!empty($notAvail)){
//            unsetReserve($reserve->idBookings,$reserve->id);
//        }
//    }

//}
//add_action( 'woocommerce_thankyou', 'deposit_complete_orders' );
//
//function deposit_complete_orders( $order_id ) {
//    if ( ! $order_id ) {
//        return;
//    }
//
//    $order = wc_get_order( $order_id );
//    if(get_post_meta($order->get_id(),'_awcdp_deposits_second_payment_paid',true) == 'yes'){
//        require_once BEDS_DIR . '/includes/class.action.php';
//        $act = new \beds_booking\Action_beds_booking();
//
//        $act->confirmPartial($order->get_id());
//    }
//}
//add_action('my_beds_hourly_event', 'getAllowDatesLitepicker');
//function getAllowDatesLitepicker()
//{
//    global $wpdb;
//    $dateAllisB = "select `date` from `beds_calendar` where isBooked=1";
//    $dateAllisB = $wpdb->get_results($dateAllisB,ARRAY_A);
//
//    $r = array();
//    foreach ($dateAllisB as $item) {
//        array_push($r,$item['date']);
//    }
//
//    $dateAll = "select `date` from `beds_calendar`";
//    $dateAll = $wpdb->get_results($dateAll,ARRAY_A);
//
//    foreach ($dateAll as $key => $item) {
//        if (in_array($item['date'],$r )){
//            unset($dateAll[$key]);
//        }
//    }
//    $fin = array();
//
//    foreach ($dateAll as $item) {
//        if ( ! in_array($item['date'],$fin)){
//            array_push($fin,$item['date']);
//        }
//    }
////    echo json_encode($fin);
//    update_option('available_dates_litepicker', json_encode($fin));
//}
// Function to format date as date.month
function format_date($date) {
    return date('d.m', strtotime($date));
}
function get_weekday($date) {
    $day_of_week = date('w', strtotime($date));
    $weekdays = ['sön', 'mån', 'tis', 'ons', 'tors', 'fre', 'lör'];
    return $weekdays[$day_of_week];
}
// Function to calculate number of days between two dates
function calculate_days($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    return $end->diff($start)->format('%a');
}
/*function searchMultipleKeys($array, $criteria) {
    $matchingIndexes = [];
    foreach ($array as $key => $item) {
        $matches = true;
        foreach ($criteria as $criteriaKey => $criteriaValue) {
            if (!isset($item[$criteriaKey]) || $item[$criteriaKey] != $criteriaValue) {
                $matches = false;
                break;
            }
        }
        if ($matches) {
            $matchingIndexes[] = $key; // Add the index to the result array if all criteria match
        }
    }
    return $matchingIndexes; // Return the array of matching indexes
}*/
function searchMultipleKeys($array, $criteria, $criteria2) {
    $matchingIndexes = [];
    
    foreach ($array as $key => $item) {
        $matches = true;
        $matches2 = true;

        // Check if the item matches the first criteria
        foreach ($criteria as $criteriaKey => $criteriaValue) {
            if (!isset($item[$criteriaKey]) || $item[$criteriaKey] != $criteriaValue) {
                $matches = false;
                break;
            }
        }

        // Check if the item matches the second criteria
        foreach ($criteria2 as $criteriaKey => $criteriaValue) {
            if (!isset($item[$criteriaKey]) || $item[$criteriaKey] != $criteriaValue) {
                $matches2 = false;
                break;
            }
        }

        // Add the index if either set of criteria matches
        if ($matches || $matches2) {
            $matchingIndexes[] = $key;
        }
    } 

    return $matchingIndexes;
}
// function get_availability_data($week_name, $post_id, $date_start, $date_end) {
function get_availability_data($post_id, $date_start, $date_end) {
    
    global $wpdb;
    
        $room = get_post_meta($post_id, '_product_beds_id', true);
        // Query to fetch availability data from the database
        $sql = $wpdb->prepare("
            SELECT `date`, `avaliable`, `isBooked`
            FROM `beds_calendar`
            WHERE `roomId` = %d
            AND `date` BETWEEN %s AND %s
        ", $room, $date_start, $date_end);

        $results = $wpdb->get_results($sql, ARRAY_A);

        // Initialize arrays for storing available and not available dates
        $grabAlldata = [];

        // Iterate through results to categorize dates
        foreach ($results as $result) {
            $date = $result['date'];
            $available = $result['avaliable'];
            $isBooked = $result['isBooked'];

            // First grab all data here
            $flag = array($available, $isBooked, $date);

            // Just store all dates for the given range
            array_push($grabAlldata, $flag); 

        }
      

        $grabAlldata2 = [];

        // Loop through each array in the original array
        foreach ($grabAlldata as $item) {
            // Add the item to grabAlldata in the desired format
            $grabAlldata2[] = [$item[0], $item[1], $item[2]];
        }
     

       
        $filteredData = filterArray($grabAlldata);
        $filteredArray = filterAndMergeArrays($filteredData);
        $filterArrayMerge =  filterArrayMerge($filteredArray);
 
        $largest_bookable_slot = [];
        $maxSize = 0;
        $filterArrayMerge;

        foreach ($filterArrayMerge as $subArray) {
            $size = count($subArray);
            if ($size > $maxSize) {
                $maxSize = $size;
                $filterArrayMerge = $subArray;
            }
        }
        
        $largest_bookable_slot = array_map(function($subArray) {
            return $subArray[2]; // Assuming the date is always in the third position
        }, $filterArrayMerge);
        
        return [
            'case1' => $largest_bookable_slot,
        ];
    
}

function mergeArraysByDate($arrays) {
    
    $mergedArrays = [];
    
    $currentArray = array_shift($arrays);
    
    foreach ($arrays as $array) {
        // Check if the end of the current array matches the start of the next array
        if (end($currentArray) == reset($array)) {
            // Merge arrays
            $currentArray = array_merge($currentArray, array_slice($array, 1));
        } else {
            // Add the current array to the result and start a new current array
            $mergedArrays[] = $currentArray;
            $currentArray = $array;
        }
    }

    // Add the last processed array
    $mergedArrays[] = $currentArray;

    // Filter out arrays that do not merge with their neighbors
    $filteredArrays = [];
    $prevArray = null;
    
    foreach ($mergedArrays as $key => $array) {
        // If not the first element, check if it merges with the previous one
        if ($prevArray !== null) {
            if (end($prevArray) == reset($array)) {
                $prevArray = array_merge($prevArray, $array);
                continue;
            } else {
                $filteredArrays[] = $prevArray;
            }
        }
        $prevArray = $array;
    }

    // Add the last processed array if it merges with the last one
    if ($prevArray !== null) {
        if (!empty($filteredArrays) && end($filteredArrays[count($filteredArrays) - 1]) == reset($prevArray)) {
            $filteredArrays[count($filteredArrays) - 1] = array_merge($filteredArrays[count($filteredArrays) - 1], $prevArray);
        } else {
            $filteredArrays[] = $prevArray;
        }
    }

    return $filteredArrays;
}

function filterArray($data) {
    $result = [];
    $temp = [];
    $capture = false;

    foreach ($data as $entry) {
        if ($entry[0] == 1 && $entry[1] == 1) {
            if ($capture) {
                // If sequence is already capturing, end previous sequence at [1, 1]
                $temp[] = $entry;
                $result[] = $temp;
            }
            // Start a new sequence
            $temp = [$entry];
            $capture = true;
        } elseif ($capture) {
            if ($entry[0] == 0 && $entry[1] == 1) {
                // End sequence at [0, 1]
                $temp[] = $entry;
                $result[] = $temp;
                $temp = [];
                $capture = false;
            } elseif ($entry[0] == 0 && $entry[1] == 0) {
                // End sequence at [0, 0]
                $temp[] = $entry;
                $result[] = $temp;
                $temp = [];
                $capture = false;
            } else {
                // Continue capturing
                $temp[] = $entry;
            }
        }
    }

    // Handle remaining temp if still capturing
    if ($capture && !empty($temp)) {
        $result[] = $temp;
    }

    return $result;
}

function filterAndMergeArrays($filteredData){
    foreach ($filteredData as $key =>  $getFilteredData) {
        $getFilteredDataValue = end($getFilteredData);
        if(($getFilteredDataValue[0]== '1' && $getFilteredDataValue[1]== '0') ||( $getFilteredDataValue[0]== '0' && $getFilteredDataValue[1]== '0') ){
            unset($filteredData[$key]);
        }
    }
        $reindexedArray = array_values($filteredData);
        return $reindexedArray;
}

function filterArrayMerge($filteredArray) {
    
    $mergedArrays = [];
    
    $currentArray = array_shift($filteredArray);
    
    foreach ($filteredArray as $array) {
        // Check if the end of the current array matches the start of the next array
        if (end($currentArray) == reset($array)) {
            // Merge arrays
            $currentArray = array_merge($currentArray, array_slice($array, 1));
        } else {
            // Add the current array to the result and start a new current array
            $mergedArrays[] = $currentArray;
            $currentArray = $array;
        }
    }

    // Add the last processed array
    $mergedArrays[] = $currentArray;

    // Filter out arrays that do not merge with their neighbors
    $filteredArrays = [];
    $prevArray = null;
    
    foreach ($mergedArrays as $key => $array) {
        // If not the first element, check if it merges with the previous one
        if ($prevArray !== null) {
            if (end($prevArray) == reset($array)) {
                $prevArray = array_merge($prevArray, $array);
                continue;
            } else {
                $filteredArrays[] = $prevArray;
            }
        }
        $prevArray = $array;
    }

    // Add the last processed array if it merges with the last one
    if ($prevArray !== null) {
        if (!empty($filteredArrays) && end($filteredArrays[count($filteredArrays) - 1]) == reset($prevArray)) {
            $filteredArrays[count($filteredArrays) - 1] = array_merge($filteredArrays[count($filteredArrays) - 1], $prevArray);
        } else {
            $filteredArrays[] = $prevArray;
        }
    }

    return $filteredArrays;
}


//==================================

add_action('woocommerce_order_status_completed', 'my_custom_completed_order_action');

function my_custom_completed_order_action($order_id) {
    $order = wc_get_order($order_id);
    global $wpdb;

    foreach ($order->get_items() as $item_id => $item){
        $to = $item->get_meta('booked_to');
        $prod = $item->get_meta('_product_id');
        $letterDate  = date('Y-m-d', strtotime($to . ' + 3 days'));

        $wpdb->query(
            $wpdb->prepare(
                'INSERT INTO `beds_review_status` (`order_id`, `date_letter`, `post_id`) VALUES (%d, %s, %d)',
                $order_id,
                $letterDate,
                $prod
            )
        );
    }
}


if (!wp_next_scheduled('check_order_status_completed')) {
    wp_schedule_event(time(), 'daily', 'check_order_status_completed');
}

add_action('check_order_status_completed', 'beds_check_orders');

function beds_check_orders() {

    global $wpdb;
    $res = $wpdb->get_results(
        $wpdb->prepare(
            'SELECT * FROM `beds_review_status` WHERE `date_letter` <= NOW()'
        )
    );
    foreach ($res as $item){
//    var_dump($item->id);die();

        $date_plus_three_days = $item->date_letter;
        $current_date = date('Y-m-d');

        $order = wc_get_order($item->order_id);

        $fio = $order->get_billing_first_name().' '. $order->get_billing_last_name();

        $order_data = $order->get_data();
        $postID = $item->post_id;
        $houseName = get_post($postID)->post_title;
        $mail_to = $order_data['billing']['email'];
        if (get_locale()=='sv_SE')
        {
            $subject = 'Tack för din vistelse';
        } else {
            $subject = 'Thank you for your stay';
        }
        $from = 'Rehnbergs Stuguthyrning <bokning@tandadalen.com>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Create email headers
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $linkReview = 'https://stugor2.hemsida.eu/product-reveiws/?house='.$postID.'&for='.$item->order_id;
//        if ($current_date >= $date_plus_three_days){
            $letter = reviewLetterText($houseName,$linkReview,$fio);
            if (mail($mail_to,$subject,$letter,$headers)){
                $wpdb->query('delete from `beds_review_status` where id='.$item->id);
            }
//        }
    }

}

function reviewLetterText($houseName, $link, $fio){
    ob_start();
    ?>
    <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 20px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 800px; background-color: #ffffff; border: 1px solid #dcdcdc; padding: 20px;">
        <tr>
            <td style="padding: 10px;">
                <div style="text-align: center; width: 100%;">
                    <div id="logo-container" style="text-align: center; width: 100%;">
                        <img id="logo" src="https://stugor2.hemsida.eu/wp-content/uploads/2024/07/rehnbergs_logo_stor.png" alt="logo" />
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px;">
                <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                    <?php _e('Hi', 'beds24');?> <?php echo $fio;?> !
                </p>
                <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                    <?php _e('We hope you had a great time in Sälen!', 'beds24');?>
                </p>
                <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                    <?php _e('Please leave some words about the accommodation and your stay. It is appreciated by both us, the cottage owners and other guests, both past as future ones.','beds24');?>
                </p>
                <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                <?php echo $link; ?>
                </p>
                <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                    <?php  _e('We wish you a warm welcome to book from us again','beds24');?>
                </p>
            </td>
        </tr>

    </table>
    </body>
    <?php
    return ob_get_clean();
}

add_action('woocommerce_order_status_changed', 'handle_order_status_change_from_admin', 10, 4);

function handle_order_status_change_from_admin($order_id, $old_status, $new_status, $order) {
    // Перевіряємо, чи зміна статусу виконується через адмінку
    if (is_admin() && !wp_doing_ajax()) {
        // Ваш код для обробки змін статусу
//        error_log("Статус замовлення #{$order_id} змінено з {$old_status} на {$new_status} через адмінку.");

        // Додаткові дії
        if ($new_status === 'completed') {
//            var_dump($order->get_payment_method());
            // Наприклад, надсилаємо повідомлення клієнту
//            wp_mail($order->get_billing_email(), 'Ваше замовлення виконано', 'Дякуємо за покупку!');
        }
    }
}



add_filter('woocommerce_checkout_fields', function ($fields) {
    $css_classes = ['form-row-wide', 'hidden'];
    foreach(WC()->cart->get_cart_contents() AS $cart_item){
        if(isset($cart_item['final_cleaning_rut'])){
            unset($css_classes[1]);
            break;
        }
    }

    $fields['billing']['billing_ssn'] = [
        'label'       => __('Social Security Number. If applying for RUT tax reduction for departure cleaning.', 'beds24'),
        'required'    => false,
        'class'       => $css_classes,
        'clear'       => true,
        'priority' => 25
    ];
    return $fields;
});

add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['billing_ssn'])) {
        update_post_meta($order_id, '_billing_ssn', sanitize_text_field($_POST['billing_ssn']));
    }
});

add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    $billing_ssn = get_post_meta($order->get_id(), '_billing_ssn', true);
    if ($billing_ssn) {
        echo '<p><strong>' . __('Social Security Number', 'beds24') . ':</strong> ' . esc_html($billing_ssn) . '</p>';
    }
});

add_filter('manage_edit-shop_order_columns', function ($columns) {
    $columns['billing_ssn'] = __('Social Security Number', 'beds24');
    return $columns;
});

add_action('manage_shop_order_posts_custom_column', function ($column, $post_id) {
    if ($column === 'billing_ssn') {
        $billing_ssn = get_post_meta($post_id, '_billing_ssn', true);
        echo esc_html($billing_ssn ? $billing_ssn : '—');
    }
}, 10, 2);

add_shortcode('orders-table', function(){
    ob_start();
    include BEDS_DIR . '/views/orders-table.php';
    $cont = ob_get_clean();
    return $cont;
});

add_action( 'wp_ajax_set_order_item_table_assign', 'ajax_set_order_item_table_assign' );
add_action( 'wp_ajax_nopriv_set_order_item_table_assign', 'ajax_set_order_item_table_assign' );
function ajax_set_order_item_table_assign() {
    $order = wc_get_order($_REQUEST['order_id']);
    $order_item = $order->get_item($_REQUEST['item_id']);
    //$order_item->update_meta_data('_table_assign', $_REQUEST['_table_assign']);
    update_post_meta($order->get_id(), '_item_'.$order_item->get_id().'_table_assign', $_REQUEST['_table_assign']);
    wp_send_json_success([

    ]);
}

add_action( 'wp_ajax_set_order_item_table_note', 'ajax_set_order_item_table_note' );
add_action( 'wp_ajax_nopriv_set_order_item_table_note', 'ajax_set_order_item_table_note' );
function ajax_set_order_item_table_note() {
    $order = wc_get_order($_REQUEST['order_id']);
    $order_item = $order->get_item($_REQUEST['item_id']);
    $content = $_REQUEST['content'] ?? '';

    update_post_meta($order->get_id(), '_item_'.$order_item->get_id().'_table_note', $content);

    wp_send_json_success([

    ]);
}



add_filter('acf/load_field/key=field_67b331b5830a4', function($field){

    global $wpdb;

    //$weeks_data = $wpdb->get_results("SELECT * FROM beds_pricelist_weeks ORDER BY id");
    $weeks_data = $wpdb->get_results("SELECT * FROM beds_pricelist_weeks ORDER BY start_date ASC");

    $choices = [
        'old_val' => 'Old value',
    ];

    foreach ($weeks_data AS $wd){
        if($wd->start_date && $wd->end_date ){
            $k = sprintf('%s_%s', $wd->start_date, $wd->end_date);
            $v = sprintf(
                '%s (%s - %s)',
                $wd->week_name,
                DateTime::createFromFormat('Y-m-d', $wd->start_date)->format('d.m'),
                DateTime::createFromFormat('Y-m-d', $wd->end_date)->format('d.m')
            );
            $choices[$k] = $v;
        }
    }

    $field['choices'] = $choices;

    return $field;
});

function auto_send_guest_information_mail(){
    global $wpdb;
    $today = date('Y-m-d');
    //$today = '2025-03-02';
    $two_weeks_period = date('Y-m-d', strtotime('+14 days'));
    $sql = "SELECT DISTINCT o.ID AS order_id
FROM $wpdb->posts o
JOIN {$wpdb->prefix}woocommerce_order_items oi ON o.ID = oi.order_id
JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
WHERE o.post_type = 'shop_order'
AND o.post_status IN ('wc-completed') 
AND oim.meta_key = 'booked_from'
AND oim.meta_value >= '$today' 
AND oim.meta_value <= '$two_weeks_period'";
    $orders_ids = $wpdb->get_col($sql);
    foreach($orders_ids AS $order_id){
        /** @var \Automattic\WooCommerce\Admin\Overrides\Order $order */
        $order = wc_get_order($order_id);
        //if($order->get_id() != 5110) continue;
        foreach($order->get_items() AS $order_item){
            /** @var \WC_Order_Item_Product $order_item */
            $meta_key = sprintf('_order_item_%s_auto_send_guest_information_mail', $order_item->get_id());
            $is_sent= get_post_meta($order->get_id(), $meta_key, true);
            if(!$is_sent){
                update_post_meta($order->get_id(), $meta_key, 1);
                send_guest_information_mail($order->get_id(), $order_item->get_id());
            }
        }
    }
}
add_action('init', 'auto_send_guest_information_mail', 1000);

function send_guest_information_mail($order_id, $order_item_id){
    $order = wc_get_order($order_id);
    $order_item = $order->get_item($order_item_id);
    $guest_name_arr = array_filter([$order->get_billing_first_name()]) ?: [];
    $guest_name = implode(' ', $guest_name_arr);
    $has_cleaning = !!((float)trim(strip_tags($order_item->get_meta(__( 'Avresestädning')))));
    $fields = get_field('product_guest_information_mail', $order_item->get_product_id());
    if(!empty($fields['disabled'])) return;
    $subject = !empty($fields['subject']) ? $fields['subject'] : get_field('guest_information_mail_subject', 'option');
    $body = !empty($fields['body']) && trim(strip_tags($fields['body'])) ? $fields['body'] : get_field('guest_information_mail_body', 'option');

    $subject = str_replace(['{guest-name}', '{house}'], [$guest_name, $order_item->get_name()], $subject);
    $body = str_replace(['{guest-name}', '{house}'], [$guest_name, $order_item->get_name()], $body);
    if(!$has_cleaning){
        $body = preg_replace('/\[cleaning\](.*?)\[\/cleaning\]/s', '', $body);
        $body = str_replace(['[no-cleaning]', '[/no-cleaning]'], ['', ''], $body);
    }
    else{
        $body = preg_replace('/\[no-cleaning\](.*?)\[\/no-cleaning\]/s', '', $body);
        $body = str_replace(['[cleaning]', '[/cleaning]'], ['', ''], $body);
    }

    $codes = get_option('beds24-products-codes');
    $code = $codes[$order_item->get_product_id()] ?? '';
    if(!$code){
        $body = preg_replace('/\[code-info\](.*?)\[\/code-info\]/s', '', $body);
    }
    else{
        $body = str_replace(['[code-info]', '[/code-info]', '{code}'], ['', '', $code], $body);
    }

    $body_html = <<<HTML
    <html>
    <style>
    .woocommerce-Price-currencySymbol{display: none;}
    </style>
        <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 20px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 800px; background-color: #ffffff; border: 1px solid #dcdcdc; padding: 20px;">
                <tr>
                    <td style="padding: 10px;">
                        <div style="text-align: center; width: 100%;">
                            <div id="logo-container" style="text-align: center; width: 100%;">
                                <img id="logo" src="https://stugor2.hemsida.eu/wp-content/uploads/2024/07/rehnbergs_logo_stor.png" alt="logo" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                <td style="padding: 10px; text-align: left;">
                    $body
                </td>
                </tr>
            </table>
        </body>
    </html>
    HTML;

    wc_mail($order->get_billing_email(), $subject, $body_html);
}



function ajax_send_guest_information_mail(){
    $order_id = $_REQUEST['order_id'] ?? '';
    $order_item_id = $_REQUEST['order_item_id'] ?? '';
    if(!$order_id || !$order_item_id){
        wp_send_json_error(['message' => 'Query error']);
    }
    try {
    send_guest_information_mail($order_id, $order_item_id);
    }
    catch (\Exception $e){
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }
    catch (\Error $e){
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }

    wp_send_json_success([]);
}
add_action( 'wp_ajax_send_guest_information_mail', 'ajax_send_guest_information_mail' );
add_action( 'wp_ajax_nopriv_send_guest_information_mail', 'ajax_send_guest_information_mail' );

add_action('post_submitbox_misc_actions', function () {
    global $post;

    if (get_post_type($post) !== 'product') return;

    $trid = apply_filters('wpml_element_trid', null, $post->ID, 'post_product');
    $translations = apply_filters('wpml_get_element_translations', null, $trid, 'post_product');

    $source_id = null;

    foreach ($translations as $lang => $t) {
        if ($t->original) {
            $source_id = $t->element_id;
            break;
        }
    }

    echo '<div class="misc-pub-section">';
    if ($source_id && $source_id != $post->ID) {
        echo '<br><button type="button" class="button" id="copy_meta_from_original" data-source-id="' . esc_attr($source_id) . '" data-target-id="' . esc_attr($post->ID) . '">Copy properties from original</button>';
    }
    echo '</div>';

    // JS
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#copy_meta_from_original').on('click', function () {
                const sourceId = $(this).data('source-id');
                const targetId = $(this).data('target-id');
console.log("Nonce:", '<?php echo wp_create_nonce('copy_meta_nonce'); ?>');
                $.post(ajaxurl, {
                    action: 'copy_product_meta_from_original',
                    source_id: sourceId,
                    target_id: targetId,
                    nonce: '<?php echo wp_create_nonce('copy_meta_nonce'); ?>'
                }, function (response) {
                    alert(response.data?.message || '✅ Copy done');
                }).fail(function () {
                    alert('❌ Error.');
                });
            });
        });
    </script>
    <?php
});


add_action('wp_ajax_copy_product_meta_from_original', function () {
if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'copy_meta_nonce')) {
        wp_send_json_error(['message' => 'error']);
    }

    $source_id = absint($_POST['source_id'] ?? 0);
    $target_id = absint($_POST['target_id'] ?? 0);

    if (!$source_id || !$target_id) {
        wp_send_json_error(['message' => '❌ no ID']);
    }

    $fields_to_copy = [
        '_product_breadcrumbs', '_product_beds_id', '_product_peoples', '_children',
        '_product_sovrum', '_product_skidlift', '_product_hundtillåtet', '_product_dubbelsang',
        '_product_laddning_elbil', '_product_wi_fi', '_product_bastu', '_product_oppen_spis',
        '_product_skidförråd', '_product_diskmaskin', '_product_tvättmaskin', '_product_torkskåp',
        '_product_barnstol', '_product_barnsäng', '_product_boyta', '_product_baddar',
        '_product_dusch', '_product_wc', '_product_tv', '_product_skidbuss',
        '_product_langdspar', '_product_matbutik', '_product_restaurang', '_product_salens_by',
        '_product_sommar', '_product_kyl_frys', '_product_mikro'
    ];

    foreach ($fields_to_copy as $key) {
        $value = get_post_meta($source_id, $key, true);
        if ($value !== '') {
            update_post_meta($target_id, $key, $value);
        }
    }

    wp_send_json_success(['message' => '✅ copy']);
});