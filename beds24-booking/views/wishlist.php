<div class="wishlist-wrap">

    <?php
    session_start();
    $wishlist = $_SESSION['wishlist'];
    if ($wishlist){
    $wishlist = explode(',', $wishlist);
    foreach ($wishlist as $post_id) {
//        die();
        $picture = get_the_post_thumbnail_url((int)$post_id);//,'middle'
        $product = wc_get_product( $post_id );
        $name = $product->get_name();
        $permalink = $product->get_permalink();
//        var_dump($product->get_id());
    ?>
    
    <div class="fav-block">
        <div><img class="fav-img" src="<?= $picture; ?>" alt=""></div>
        <div><a onclick="window.open('<?php echo $permalink; ?>');" style="cursor: pointer;"><?php echo $name; ?></a></div>
        <div class="del-div" data-id="<?= $post_id; ?>">
            <svg class="del-wishlist-item"  width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.66675 3.31331L5.81341 2.43998C5.92008 1.80665 6.00008 1.33331 7.12675 1.33331H8.87341C10.0001 1.33331 10.0867 1.83331 10.1867 2.44665L10.3334 3.31331" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.5669 6.09332L12.1336 12.8067C12.0603 13.8533 12.0003 14.6667 10.1403 14.6667H5.86026C4.00026 14.6667 3.94026 13.8533 3.86693 12.8067L3.43359 6.09332" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.88672 11H9.10672" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.33325 8.33331H9.66659" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

    </div>
    <hr class="hr-fav">
    <?php
    }
    } else {
        ?>
        <div class="fav-block">
            <p><?php _e('There is nothing in the favorites yet.','beds24');?></p>
        </div>
        <?php
    }
    ?>
    
</div>