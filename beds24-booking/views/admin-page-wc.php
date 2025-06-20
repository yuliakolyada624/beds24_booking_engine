<style>
    .admin-block {
        border-radius: 3px;
        border: 1px black solid;
        padding: 15px;
        margin-right: 15px;
    }
</style>

<h2>Orders that have been placed but could not be uploaded to beds24.com</h2>
<div class="admin-block">
    <h4>Orders data</h4>
    <div>
        <?php
        require_once(BEDS_DIR . '/includes/class.action.php');
        $act = new \beds_booking\Action_beds_booking();
        global $wpdb;

        $res = $wpdb->get_results("select * from `wp_postmeta` WHERE `meta_key` = 'request_api_res'");

        $notif = 0;
        $url = site_url().'/wp-admin/';
        foreach ($res as $re) {
            if (empty($re->meta_value)){
                $notif++;
                echo '<p>'.$notif.'. Order #'.$re->post_id.' has empty answer from Beds24 API (lost connections, wrong data, etc.).</p>';
                echo '<p><a href="'.$url.'post.php?post='.$re->post_id.'&action=edit">Open order #'.$re->post_id.'</a><br>';
                echo '<button id="'.$re->post_id.'" class="btn-resolved-beds">The issue is resolved</button></p><hr>';
            }
            else{
                $resApiObj = json_decode($re->meta_value)[0];
                $apiSuccess = $resApiObj->success;
                if (!$apiSuccess){
                    $notif++;
                    echo '<p>'.$notif.'. Order #'.$re->post_id.' has Error answer from Beds24 API ( wrong data, etc.).</p>';
                    echo '<p><a href="'.$url.'post.php?post='.$re->post_id.'&action=edit">Open order #'.$re->post_id.'</a><br>';
                    echo '<button id="'.$re->post_id.'" class="btn-resolved-beds">The issue is resolved</button></p><hr>';

                }
            }
        }
        ?>
    </div>

    <script>
        jQuery('.btn-resolved-beds').on('click', function (){
            var order_id = jQuery(this).attr('id')
            let site_url = document.location.origin;

            jQuery.ajax({
                type: 'POST',
                url: site_url + '/wp-admin/admin-ajax.php',
                data: {
                    order_id: order_id,
                    action: 'resolvedManualOrder'
                },
                dataType: "json",
                cache: false,
                error: function(error){
                    alert('error');
                },
                success: function(data){
                    location = site_url+"/wp-admin/admin.php?page=beds24-bad-orders";
                }
            });
        })
    </script>

</div>