<?php
$option_name = 'beds24-products-codes';

if(!empty($_POST[$option_name])){
    update_option($option_name, $_POST[$option_name]);
}

$codes = get_option($option_name) ?: [];

$products = get_posts([
    'posts_per_page' => -1,
    'post_type' => 'product',
    'post_status' => 'publish',
    'orderby' => 'post_title'
]);



?>
<style>
    .form-products-codes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .form-products-codes-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }


    .form-products-codes-group label {
        white-space: nowrap;
    }

    .form-products-codes-group input {
        flex: 1;
        min-width: 0;
    }

    @media (min-width: 768px) {
        .form-products-codes {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
<form class="wrap" method="post" action="<?= admin_url('admin.php?page='.$option_name) ?>">
    <h1>Codes</h1>
    <div class="form-products-codes">
        <?php foreach($products AS $p): ?>
            <div class="form-products-codes-group">
                <label for="input-beds24-product-code-<?= $p->ID ?>"><b>#<?= $p->ID ?> <?= $p->post_title ?></b></label>
                <input type="text" name="<?= $option_name ?>[<?= $p->ID ?>]" id="input-beds24-product-code-<?= $p->ID ?>" value="<?= $codes[$p->ID] ?? '' ?>">
            </div>
        <?php endforeach ;?>
    </div>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>