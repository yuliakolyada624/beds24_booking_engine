<?php

$option_name = 'beds24_pricelis_table_settings';

if(!empty($_POST[$option_name])){
    update_option($option_name, $_POST[$option_name]);
}

$saved_options = get_option($option_name);

$months_by_period = get_months_by_period();

$all_tags = array_map(function($t)use($saved_options){
    $t->sort_order = !empty($saved_options['product_tag_order'][$t->term_id]) ? $saved_options['product_tag_order'][$t->term_id] : '';
    return $t;
}, get_terms([
    'taxonomy' => 'product_tag',
    'orderby' => 'name',
    'order' => 'DESC',
    'hide_empty' => true,
]));

global $wpdb;
$weeks_data = $wpdb->get_results("SELECT * FROM beds_pricelist_weeks ORDER BY start_date ASC", ARRAY_A);
$weeks_data = array_filter($weeks_data, function($w){
    return !empty($w['start_date']) && !empty($w['end_date']);
});
$weeks_data = array_map(function($w)use($saved_options){
    $date_start = new \DateTime($w['start_date']);
    $date_end = new \DateTime($w['end_date']);
    $months = array_values(array_unique([
        (int)$date_start->format('m'),
        (int)$date_end->format('m'),
    ]));

    $default_val = sprintf('%s - %s', $date_start->format('d.m'), $date_end->format('d.m'));
    $val = !empty($saved_options['week_subtitle'][$w['id']]) ? $saved_options['week_subtitle'][$w['id']] : $default_val;
    return array_merge($w, [
        'value' => $val,
        'months' => $months
    ]);
}, $weeks_data);

$apartments = array_map(function($a)use($saved_options, $weeks_data){
    $months = [];
    foreach($weeks_data AS $week){
        $availability_data = get_availability_data($a->ID, $week['start_date'], $week['end_date']);
        $available_dates = $availability_data['case1'];
        if($available_dates && count($available_dates) > 1){
            sort($available_dates);
            $months[] = (int)(new \DateTime(reset($available_dates)))->format('m');
            $months[] = (int)(new \DateTime(end($available_dates)))->format('m');
        }
    }
    $sort_order = !empty($saved_options['apartment'][$a->ID]['sort']) ? $saved_options['apartment'][$a->ID]['sort'] : '';
    return (object)[
        'ID' => $a->ID,
        'post_title' => $a->post_title,
        'sort_order' => $sort_order,
        'hidden' => $saved_options['apartment'][$a->ID]['hidden'] ?? [],
        'tags' => wp_get_post_terms($a->ID, 'product_tag'),
        'months' => array_values(array_unique($months))
    ];
}, get_posts([
    'posts_per_page' => -1,
    'post_type' => 'product',
    'post_status' => 'published'
]));

$tags_groups = [];
if(!empty($saved_options['product_tag_order']) && array_filter($saved_options['product_tag_order'])){
    foreach($all_tags AS $t){
        $n = !empty($saved_options['product_tag_order'][$t->term_id]) ? $saved_options['product_tag_order'][$t->term_id] : 10000;
        if(!isset($tags_groups[$n])) $tags_groups[$n] = [];
        $tags_groups[$n][] = $t;
    }
}
else{
    $tags_groups = [$all_tags];
}
ksort($tags_groups);

?>
<form class="wrap" method="post" action="<?= admin_url('admin.php?page=big-pricelist-settings') ?>">
    <h1>Pricelist Table settings</h1>

    <table class="form-table">
        <tbody>
        <tr>
            <th>
                <label for="settings_period">
                    Show settings period
                </label>
            </th>            <td>
                <select id="settings_period">
                    <!--<option value="">All</option>-->
                    <option value="winter">Winter</option>
                    <option value="summer">Summer</option>
                </select>
            </td>
        </tr>
        </tbody>
    </table>

    <h2>Tags ordering/grouping</h2>
    <div class="tags-ordering">
        <?php foreach($all_tags AS $tag): ?>
            <p>
                <label>
                    <?= $tag->name ?>
                    <input type="number" step="1" value="<?= $tag->sort_order ?>" name="<?= $option_name ?>[product_tag_order][<?= $tag->term_id ?>]">
                </label>
            </p>
        <?php endforeach; ?>
    </div>

    <hr>

    <h2>Description of the weeks</h2>
    <div class="weeks-subtitles">
        <?php foreach($weeks_data AS $week): ?>
            <div class="week-subtitle" data-months="<?= esc_attr(json_encode($week['months'])) ?>">
                <label><?= $week['week_name'] ?></label>
                <input type="text" name="<?= $option_name ?>[week_subtitle][<?= $week['id'] ?>]" value="<?= $week['value'] ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <h3>Order/Visibility</h3>
    <div class="apartments-ordering">
        <?php foreach($tags_groups AS $tg):
            $tg_ids = array_map(fn($t) => $t->term_id, $tg);
            $group_title = implode('/', array_map(fn($t) => $t->name, $tg));
            $group_apartments = array_filter($apartments, function($item)use($tg_ids){
                $item_tags = array_map(fn($t) => $t->term_id, $item->tags);
                return !!array_intersect($item_tags, $tg_ids);
            });
            $apartments_columns = array_chunk($group_apartments, ceil(count($group_apartments)/2));
            ?>
            <h4 class="apartments-ordering-group-title"><?= $group_title ?></h4>
            <div class="apartments-ordering-group">
                <?php foreach($apartments_columns AS $apartments_col): ?>
                    <table class="apartments-table">
                        <thead>
                        <tr>
                            <th class="large-column">Accomodation</th>
                            <th class="medium-column">Priority</th>
                            <th class="small-column">Hide</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($apartments_col AS $item): ?>
                            <tr data-months="<?= esc_attr(json_encode($item->months)) ?>">
                                <td class="large-column">#<?= $item->ID ?> <?= $item->post_title ?></td>
                                <td class="medium-column">
                                    <input data-months="<?= esc_attr(json_encode(array_keys($months_by_period['winter']))) ?>" type="number" step="1" name="<?= $option_name ?>[apartment][<?= $item->ID ?>][sort][winter]" value="<?= $item->sort_order['winter'] ?? '' ?>" class="order-input">
                                    <input data-months="<?= esc_attr(json_encode(array_keys($months_by_period['summer']))) ?>" type="number" step="1" name="<?= $option_name ?>[apartment][<?= $item->ID ?>][sort][summer]" value="<?= $item->sort_order['summer'] ?? '' ?>" class="order-input">
                                </td>
                                <td class="small-column">
                                    <input data-months="<?= esc_attr(json_encode(array_keys($months_by_period['winter']))) ?>" type="checkbox" name="<?= $option_name ?>[apartment][<?= $item->ID ?>][hidden][winter]" class="hide-checkbox" <?= !empty($item->hidden['winter']) ? 'checked' : '' ?> value="1">
                                    <input data-months="<?= esc_attr(json_encode(array_keys($months_by_period['summer']))) ?>" type="checkbox" name="<?= $option_name ?>[apartment][<?= $item->ID ?>][hidden][summer]" class="hide-checkbox" <?= !empty($item->hidden['summer']) ? 'checked' : '' ?> value="1">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

</form>

<script>
    (function($){
        $(document).ready(function(){
            const $body = $('body');
            $body.on('change', '#settings_period', function(){
               const period = $(this).val();
               $('[data-months]').each(function(){
                   const months = $(this).data('months').map(m => parseInt(m));
                  let isAvailable = true;
                  if(period === 'winter'){
                      isAvailable = months.filter(m => !(m >= 6 && m <= 10)).length > 0;
                  }
                  else if(period === 'summer'){
                      isAvailable = months.filter(m => (m >= 6 && m <= 10)).length > 0;
                  }
                  if(isAvailable){
                      $(this).show();
                  }
                  else{
                      $(this).hide();
                  }
               });
            });
            $('#settings_period').trigger('change');
        })
    })(jQuery)
</script>

<style>
    .weeks-subtitles {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: flex-start;
    }

    .week-subtitle {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 150px;
    }

    .week-subtitle label {
        margin-bottom: 8px;
        font-size: 14px;
        color: #333;
    }

    .week-subtitle input {
        width: 100%;
        padding: 4px;
        font-size: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /*********************************/
    /*********************************/
    /*********************************/

    .apartments-ordering-group {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        width: 100%;
    }

    .apartments-table {
        width: 45%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .apartments-table thead {
        background-color: grey;
        color: white;
    }

    .apartments-table th,
    .apartments-table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .apartments-table .large-column {
        width: 50%;
    }

    .apartments-table .medium-column {
        width: 30%;
    }

    .apartments-table .small-column {
        width: 20%;
        text-align: center;
    }

    .apartments-table .order-input {
        width: 100%;
        padding: 4px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .apartments-table .hide-checkbox {
        display: block;
        margin: auto;
    }

</style>