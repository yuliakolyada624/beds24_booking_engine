<?php
if (empty($period) || !in_array($period, ['winter', 'summer'])) return;

global $wpdb;

$beds24_pricelis_table_settings = get_option('beds24_pricelis_table_settings');

require(BEDS_DIR . '/includes/class.action.php');
$actionBedsBooking = new \beds_booking\Action_beds_booking();
//$actionBedsBooking->sendDepositRemind(5245);
//$actionBedsBooking->sendDepositRemind(5259);
//$actionBedsBooking->sendDepositRemind(5270);
//$actionBedsBooking->sendDepositRemind(5710);
//$actionBedsBooking->sendDepositRemind(5382);


$months_by_period = get_months_by_period();

$months_list = $months_by_period[$period];


$weeks_data = $wpdb->get_results("SELECT * FROM beds_pricelist_weeks ORDER BY start_date ASC", ARRAY_A);
$period_weeks = array_filter($weeks_data, function ($wd) use ($months_list) {
    if (empty($wd['start_date']) || empty($wd['end_date'])) return false;
    $today = new \DateTime('today');
    $date_start = new \DateTime($wd['start_date']);
    if ($today > $date_start) return false;
    $month = (int)$date_start->format('m');
    return isset($months_list[$month]);
});

$period_weeks = array_map(function ($p) use ($beds24_pricelis_table_settings) {
    $subtitle = !empty($beds24_pricelis_table_settings['week_subtitle'][$p['id']])
        ? $beds24_pricelis_table_settings['week_subtitle'][$p['id']]
        : sprintf(
            '%s - %s',
            (new \DateTime($p['start_date']))->format('d.m'),
            (new \DateTime($p['end_date']))->format('d.m')
        );
    return array_merge($p, [
        'week_num' => preg_replace('/\D/', '', $p['week_name']),
        'subtitle' => $subtitle
    ]);
}, $period_weeks);

$posts = get_posts([
    'posts_per_page' => -1,
    'post_type' => 'product',
    'post_status' => 'publish',
    'suppress_filters' => false
]);


$table_data = array_filter(array_map(function ($p) use ($period, $period_weeks, $actionBedsBooking, $beds24_pricelis_table_settings) {
    if (!empty($beds24_pricelis_table_settings['apartment'][$p->ID]['hidden'][$period])) {
        return false;
    }
    $d = [
        'id' => $p->ID,
        'permalink' => get_permalink($p->ID),
        'thumb' => get_the_post_thumbnail_url($p->ID, 'thumbnail'),
        'title' => $p->post_title,
        //'desc' => function_exists('get_field') ? get_field('facts', $p->ID) : '',
        'desc' => get_post_meta($p->ID, '_product_breadcrumbs', true),
        'people' => get_post_meta($p->ID, '_product_peoples', true),
        'tags' => wp_get_post_terms($p->ID, 'product_tag'),
        'dogs_available' => get_post_meta($p->ID, '_product_hundtillåtet', true) == 'yes',
        'beds' => get_post_meta($p->ID, '_product_baddar', true),
        'bedrooms' => get_post_meta($p->ID, '_product_sovrum', true),
        'priority' => !empty($beds24_pricelis_table_settings['apartment'][$p->ID]['sort'][$period]) ? $beds24_pricelis_table_settings['apartment'][$p->ID]['sort'][$period] : 0,
        'weeks' => []
    ];

    foreach ($period_weeks as $week) {
        $availability_data = get_availability_data($p->ID, $week['start_date'], $week['end_date']);
        $available_dates = $availability_data['case1'];
        if (is_array($available_dates) && count($available_dates) > 1) {
            $date_start = reset($available_dates);
            $date_end = end($available_dates);
            $availableFromDateTime = new \DateTime($date_start);
            $availableToDateTime = new \DateTime($date_end);
            $no_off_days = (int)$availableToDateTime->diff($availableFromDateTime)->format('%a');
            $price_by_period = $actionBedsBooking->getRoomPriceByDays($no_off_days, $date_start, $date_end, $p->ID);
            $week_day_start = $availableFromDateTime->format('N');
            $week_day_end = $availableToDateTime->format('N');
            $d['weeks'][$week['week_name']] = [
                'date_start' => $availableFromDateTime->format('Y-m-d'),
                'day_start_text' => mb_substr(__($availableFromDateTime->format('D')), 0, 2),
                'date_end' => $availableToDateTime->format('Y-m-d'),
                'day_end_text' => mb_substr(__($availableToDateTime->format('D')), 0, 2),
                'price' => round($price_by_period, -1),
                'is_colored' => $week_day_start == $week_day_end && in_array($week_day_start, [4, 7]),
                'url' => get_permalink($p->ID) . '?' . http_build_query([
                        'date_start' => $date_start,
                        'date_end' => $date_end,
                        'number-adult' => 1,
                        'number-child' => 0,
                        //'animals' => 'on'
                    ])
            ];
        } else {
            $d['weeks'][$week['week_name']] = [];
        }
    }

    return $d;
}, $posts));

$table_data = array_filter($table_data, function($td){
   return !!$td['people'];
});

usort($table_data, function ($a, $b) {
    return $b['priority'] - $a['priority'];
});

$all_tags = get_terms([
    'taxonomy' => 'product_tag',
    'parent' => 0,
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'DESC'
]);


$tags_groups = [];
if (!empty($beds24_pricelis_table_settings['product_tag_order']) && array_filter($beds24_pricelis_table_settings['product_tag_order'])) {
    foreach ($all_tags as $t) {
        $n = !empty($beds24_pricelis_table_settings['product_tag_order'][$t->term_id]) ? $beds24_pricelis_table_settings['product_tag_order'][$t->term_id] : 10000;
        if (!isset($tags_groups[$n])) $tags_groups[$n] = [];
        $tags_groups[$n][] = $t;
    }
} else {
    $tags_groups = [$all_tags];
}
ksort($tags_groups);

?>

<div class="pricetable-wrap">
    <div class="pricetable-filter">
        <a href="#popup" class="pricetable-filter-icon">
            <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.38331 5.50001H12.6141M1.33203 1.33334H14.6654M6.46024 9.66668H9.53716" stroke="black"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Filter</span>
        </a>

        <div id="popup" class="overlay">
            <div class="center-screen">
                <div class="popup">
                    <a class="cancel" href="#">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="15" cy="15" r="15" fill="white"/>
                            <path
                                d="M19.8171 10.1829C19.5733 9.93904 19.1822 9.93904 18.9383 10.1829L15 14.1212L11.0617 10.1829C10.8178 9.93904 10.4267 9.93904 10.1829 10.1829C9.93904 10.4267 9.93904 10.8178 10.1829 11.0617L14.1212 15L10.1829 18.9383C9.93904 19.1822 9.93904 19.5733 10.1829 19.8171C10.3025 19.9367 10.4635 20.0011 10.62 20.0011C10.7764 20.0011 10.9374 19.9413 11.0571 19.8171L14.9954 15.8788L18.9337 19.8171C19.0534 19.9367 19.2144 20.0011 19.3708 20.0011C19.5319 20.0011 19.6883 19.9413 19.8079 19.8171C20.0518 19.5733 20.0518 19.1822 19.8079 18.9383L15.8788 15L19.8171 11.0617C20.061 10.8178 20.061 10.4267 19.8171 10.1829Z"
                                fill="black"/>
                        </svg>
                    </a>
                    <div class="tt">Filtrera prislistan</div>
                    <div class="pp">
                        <div class="pricetable-filter-item">
                            <select class="pricetable-filter-select" data-name="product_tag">
                                <option value=""><?= __('Område') ?></option>
                                <?php foreach ($all_tags as $tag): ?>
                                    <option value="<?= $tag->term_id ?>"><?= $tag->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pricetable-filter-item">
                            <select class="pricetable-filter-select" data-name="month">
                                <option value="0"><?= __('Månad') ?></option>
                                <?php foreach ($months_list as $mv => $ml): ?>
                                    <option value="<?= $mv ?>"><?= $ml ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pricetable-filter-item">
                            <div class="pricetable-filter-switcher">
                                <span><?php _e('Hundtillåtet'); ?></span>
                                <input type="checkbox" class="checkbox" value="1" id="prietable-filter-dogs"
                                       data-name="dogs_available">
                                <label for="prietable-filter-dogs" class="for-checkbox"></label>

                            </div>
                        </div>
                        <div class="pricetable-filter-item">
                            <div class="pricetable-filter-quantity">
                                <div for="pricetable-filter-quantity-title"><?php _e('Antal bäddar', 'beds4'); ?></div>
                                <div class="pricetable-filter-quantity-input">
                                    <button type="button" data-action="-">
                                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.790995" y="0.904948" width="21.1901" height="21.1901"
                                                  rx="10.5951"
                                                  fill="#F7F9FC"/>
                                            <rect x="0.790995" y="0.904948" width="21.1901" height="21.1901"
                                                  rx="10.5951"
                                                  stroke="#E4E4EC" stroke-width="0.683552"/>
                                            <path d="M7.96875 12.013V10.987H14.8043V12.013H7.96875Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <input type="number" value="0" min="0" step="1" data-name="beds">
                                    <button type="button" data-action="+">
                                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1.06834" y="0.904948" width="21.1901" height="21.1901" rx="10.5951"
                                                  fill="#F7F9FC"/>
                                            <rect x="1.06834" y="0.904948" width="21.1901" height="21.1901" rx="10.5951"
                                                  stroke="#E4E4EC" stroke-width="0.683552"/>
                                            <path
                                                d="M11.138 14.9178V11.9784H8.24609V10.9524H11.138V8.08228H12.1897V10.9524H15.0816V11.9784H12.1897V14.9178H11.138Z"
                                                fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="pricetable-filter-item">
                            <div class="pricetable-filter-quantity">
                                <div for="pricetable-filter-quantity-title"><?php _e('Antal sovrum', 'beds4'); ?></div>
                                <div class="pricetable-filter-quantity-input">
                                    <button type="button" data-action="-">
                                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.790995" y="0.904948" width="21.1901" height="21.1901"
                                                  rx="10.5951"
                                                  fill="#F7F9FC"/>
                                            <rect x="0.790995" y="0.904948" width="21.1901" height="21.1901"
                                                  rx="10.5951"
                                                  stroke="#E4E4EC" stroke-width="0.683552"/>
                                            <path d="M7.96875 12.013V10.987H14.8043V12.013H7.96875Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <input type="number" value="0" min="0" step="1" data-name="bedrooms">
                                    <button type="button" data-action="+">
                                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1.06834" y="0.904948" width="21.1901" height="21.1901" rx="10.5951"
                                                  fill="#F7F9FC"/>
                                            <rect x="1.06834" y="0.904948" width="21.1901" height="21.1901" rx="10.5951"
                                                  stroke="#E4E4EC" stroke-width="0.683552"/>
                                            <path
                                                d="M11.138 14.9178V11.9784H8.24609V10.9524H11.138V8.08228H12.1897V10.9524H15.0816V11.9784H12.1897V14.9178H11.138Z"
                                                fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="btnFilter">Applicera filter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php foreach ($tags_groups as $tg):
    $tg_ids = array_map(fn($t) => $t->term_id, $tg);
    $group_title = implode('/', array_map(fn($t) => $t->name, $tg));
    $group_apartments = array_filter($table_data, function ($item) use ($tg_ids) {
        $item_tags = array_map(fn($t) => $t->term_id, $item['tags']);
        return !!array_intersect($item_tags, $tg_ids);
    });
    ?>
    <div class="pricetable-group">
        <div class="pricetable-title">
            <?= $group_title ?>
        </div>
        <div class="outer">
        <div class="pricetable-table inner">
            <table class="pricetable-table">
                <thead>
                <tr>
                    <th class="pricetable-th-text fix"><?= __('Boende') ?></th>
                    <th class="pricetable-th-people mobHide">
                        <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.8701 5.63382C5.3976 5.63382 5.91325 5.47739 6.35185 5.18432C6.79044 4.89125 7.13227 4.4747 7.33411 3.98734C7.53596 3.49999 7.58875 2.96373 7.4858 2.44637C7.38286 1.92902 7.12881 1.45381 6.75578 1.08084C6.38275 0.707877 5.90749 0.453909 5.39012 0.351055C4.87274 0.248202 4.33649 0.301084 3.84917 0.503012C3.36185 0.704941 2.94536 1.04685 2.65236 1.48549C2.35937 1.92413 2.20303 2.43981 2.20313 2.96731C2.20387 3.67437 2.4851 4.35224 2.98511 4.85216C3.48512 5.35209 4.16305 5.6332 4.8701 5.63382Z"
                                fill="white"/>
                            <path
                                d="M8.3556 7.83664C7.67979 7.14345 6.81268 6.66745 5.86504 6.46945C4.9174 6.27144 3.93225 6.36042 3.03542 6.72501C2.13859 7.0896 1.3708 7.71326 0.830126 8.51632C0.289452 9.31937 0.000437443 10.2654 0 11.2335C0 11.3572 0.0491661 11.4759 0.136682 11.5635C0.224199 11.651 0.342896 11.7001 0.466663 11.7001H9.26792C9.39169 11.7001 9.51039 11.651 9.5979 11.5635C9.68542 11.4759 9.73458 11.3572 9.73458 11.2335C9.73639 10.7386 9.66001 10.2466 9.50825 9.77562C9.2823 9.04745 8.88733 8.38304 8.3556 7.83664Z"
                                fill="white"/>
                            <path
                                d="M10.4989 5.86702C11.5943 5.86702 12.4823 4.97906 12.4823 3.88371C12.4823 2.78835 11.5943 1.90039 10.4989 1.90039C9.40359 1.90039 8.51562 2.78835 8.51562 3.88371C8.51562 4.97906 9.40359 5.86702 10.4989 5.86702Z"
                                fill="white"/>
                            <path
                                d="M10.4988 6.41302C9.87324 6.41537 9.25982 6.58573 8.72266 6.90629C8.82392 6.99682 8.92612 7.08595 9.02179 7.18395C9.65721 7.83707 10.1292 8.63114 10.3994 9.5014C10.4919 9.78791 10.5601 10.0817 10.6033 10.3797H13.5321C13.6558 10.3797 13.7745 10.3305 13.8621 10.243C13.9496 10.1555 13.9987 10.0368 13.9987 9.913C13.9978 8.98505 13.6287 8.09539 12.9725 7.43923C12.3164 6.78308 11.4267 6.41401 10.4988 6.41302Z"
                                fill="white"/>
                        </svg>
                    </th>
                    <?php foreach (array_slice($period_weeks, 0, 16) as $week):
                        $months_nums = [
                            (int)date('m', strtotime($week['start_date'])),
                            (int)date('m', strtotime($week['end_date'])),
                        ];
                        ?>
                        <th class="pricetable-th-week pricetable-filterable-col"
                            data-filterable-months="<?= esc_attr(json_encode(array_unique($months_nums))) ?>">
                                <span class="pricetable-th-week-title">
                                    <?= $week['week_num'] ?>
                                </span>
                            <span class="pricetable-th-week-desc">
                                    <?= $week['subtitle'] ?>
                                 </span>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($group_apartments as $item): ?>
                    <tr
                        class="pricetable-filterable-row"
                        data-filterable-product_tag="<?= esc_attr(json_encode(array_map(fn($t) => $t->term_id, $item['tags']))) ?>"
                        data-filterable-dogs_available="<?= (int)$item['dogs_available'] ?>"
                        data-filterable-beds="<?= (int)$item['beds'] ?>"
                        data-filterable-bedrooms="<?= (int)$item['bedrooms'] ?>"
                    >
                        <td class="pricetable-td-item fix">
                            <a href="<?= $item['permalink'] ?>" target="_blank">
                                <div class="flexT">
                                    <div class="pricetable-item-image">
                                        <img src="<?= $item['thumb'] ?>" alt="<?= $item['title'] ?>">
                                    </div>
                                    <div class="pricetable-item-data">
                                        <div class="pricetable-item-data-title">
                                            <?= $item['title'] ?>
                                        </div>
                                        <div class="pricetable-item-data-desc">
                                            <?= $item['desc'] ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td class="pricetable-th-people mobHide"><?= $item['people'] ?></td>
                        <?php foreach (array_slice($period_weeks, 0, 16) as $week):
                            $item_week = !empty($item['weeks'][$week['week_name']]) ? $item['weeks'][$week['week_name']] : false;
                            $item_week_td_classes = ['pricetable-td-week', 'pricetable-filterable-col'];
                            $months_nums = [];
                            if (!$item_week) {
                                $item_week_td_classes[] = 'pricetable-td-week-none';
                            } elseif ($item_week['is_colored']) {
                                $item_week_td_classes[] = 'pricetable-td-week-colored';
                            }
                            $months_nums = [
                                (int)date('m', strtotime($week['start_date'])),
                                (int)date('m', strtotime($week['end_date'])),
                            ];
                            ?>
                            <td class="<?= implode(' ', $item_week_td_classes) ?>"
                                data-filterable-months="<?= esc_attr(json_encode(array_unique($months_nums))) ?>">
                                <?php if (!$item_week || !$item_week['price']): ?>
                                    -
                                <?php else: ?>
                                    <a href="<?= $item_week['url'] ?>" target="_blank">
                                        <div class="pricetable-td-week-price">
                                            <?= $item_week['price'] ?>
                                        </div>
                                        <div class="pricetable-td-week-days">
                                            <?= $item_week['day_start_text'] ?> - <?= $item_week['day_end_text'] ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>

    </div>
<?php endforeach; ?>
</div>

<?php

add_action('wp_footer', function () {
    ?>
    <script>
        (function ($) {
            const $body = $('body');

            $body.on('click', '.pricetable-filter-quantity-input button', function () {
                const $btn = $(this);
                const $wrap = $btn.closest('div');
                const $input = $wrap.find('input');
                let val = parseInt($input.val());
                if ($btn.data('action') === '+') {
                    val++;
                } else if ($btn.data('action') === '-') {
                    val--;
                }
                if (val >= 0) {
                    $input.val(val).trigger('change');
                }
            });

            $body.on('change', '.pricetable-filter-quantity-input input', function () {
                const $input = $(this);
                const val = parseInt($input.val());
                if (val < 0) {
                    $input.val(0).trigger('change');
                }
            });

            $body.on('change', '.pricetable-filter-item [data-name]', filterTables)

            function filterTables() {
                const $productTag = $('.pricetable-filter-item [data-name="product_tag"]');
                const $month = $('.pricetable-filter-item [data-name="month"]');
                const $dogsAvailable = $('.pricetable-filter-item [data-name="dogs_available"]');
                const $beds = $('.pricetable-filter-item [data-name="beds"]');
                const $bedrooms = $('.pricetable-filter-item [data-name="bedrooms"]');

                //cols
                const month = parseInt($month.val());
                $('.pricetable-filterable-col[data-filterable-months]').each(function () {
                    if (month && month > 0) {
                        const item_months = $(this).data('filterable-months').map(m => parseInt(m));
                        if (item_months.length > 0 && item_months.includes(month)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    } else {
                        $(this).show();
                    }
                });

                //rows
                $('.pricetable-filterable-row').each(function () {
                    let isHidden = false;

                    const tag = parseInt($productTag.val());
                    if (tag && tag > 0) {
                        const tags = $(this).data('filterable-product_tag').map(t => parseInt(t));
                        if (!tags.includes(tag)) {
                            isHidden = true;
                        }
                    }

                    if ($dogsAvailable.prop('checked') && !parseInt($(this).data('filterable-dogs_available'))) {
                        isHidden = true;
                    }

                    const beds = parseInt($beds.val());
                    if (beds && beds > 0 && beds > parseInt($(this).data('filterable-beds'))) {
                        isHidden = true;
                    }

                    const bedrooms = parseInt($bedrooms.val());
                    if (bedrooms && bedrooms > 0 && bedrooms > parseInt($(this).data('filterable-bedrooms'))) {
                        isHidden = true;
                    }

                    if (isHidden) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                })

                //tables
                $('.pricetable-group').show().each(function () {
                    if ($(this).find('tbody tr:visible').length > 0) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                })
            }

        })(jQuery)
    </script>
    <?php
}, 100);
?>

<style>
    .pricetable-filter,
    #popup .popup .pp {
        display: flex;
        gap: 15px;
        width: 100%;
        justify-content: space-between;
    }

    .pricetable-filter input[type="number"] {
        max-width: 20px;
        text-align: center;
        font-size: 11px;
        border: none;
        height: auto;
        width: auto;
        padding: 0;
        margin: 0 -5px;
    }

    .pricetable-filter-item {
        font-size: 13px;
        font-weight: 600;
        width: 100%;
    }

    .pricetable-item-image img {
        max-width: 70px;
        height: 100%;
        object-fit: cover;
    }

    .pricetable-table {
        text-align: center;
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .pricetable-table td {
        border: solid 1px #E8E7E7;
        font-size: 12px;
        line-height: 16px;
        color: #000;
        padding: 0;
    }

    .pricetable-th-week-title {
        width: 100%;
        display: block;
        font-weight: 800;
        font-size: 12px;
        line-height: 14px;
        margin-bottom: 3px;
    }

    .pricetable-th-week-desc {
        font-size: 8px;
        line-height: 14px;
        font-weight: 800;
    }

    .pricetable-table th {
        background: #CA0013;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 500;
        padding: 5px 0;
        border-right: solid 1px #E4E4EC;
    }

    .pricetable-td-week-days {
        font-size: 8px;
        line-height: 10px;
        color: #A6A6A8;
        font-weight: 600;
        display: block;
    }

    .pricetable-td-week-price {
        font-weight: 600;
        color: #293688;
    }

    .flexT {
        display: flex;
        font-size: 10px;
        line-height: 14px;
        text-align: left;
        color: #000000;
        background: #F7F9FC;
    }

    .pricetable-th-people {
        background: #F7F9FC;
        color: #606060;
        padding: 16px 8px;
        min-width: 38px;
    }

    .pricetable-item-data {
        color: #000000;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 8px;
    }

    .pricetable-td-item {
        max-width: 160px;
    }

    .pricetable-td-item a {
        text-decoration: none;
    }

    .pricetable-item-data-title {
        font-weight: 700;
    }

    .pricetable-title {
        font-family: 'Saira', sans-serif;
        font-size: 18px;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    body {
        background: #F7F9FC;
    }

    /*.pricetable-table tr th:nth-child(3),
    .pricetable-table tr td:nth-child(3){
        display: none;
    }*/

    .pricetable-filter-switcher label {
        margin: 0;
    }

    .pricetable-filter-quantity,
    .pricetable-filter-select,
    .pricetable-filter-switcher {
        display: flex;
        padding: 2px 11px;
        gap: 10px;
        border-radius: 8px;
        border: solid 1px #E4E4EC;
        background: #fff;
        align-items: center;
        min-height: 35px;
        width: 100%;
        justify-content: space-between;
    }

    .pricetable-filter-quantity-input input::-webkit-outer-spin-button,
    .pricetable-filter-quantity-input input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .pricetable-filter-quantity-input input[type=number] {
        -moz-appearance: textfield;
    }

    .pricetable-filter-quantity-input button {
        background: none;
        border: none;
        padding: 0;
        max-width: 21px;
    }

    .pricetable-filter-quantity-input button svg {
        max-width: 100%;
    }

    select.pricetable-filter-select {
        background: #fff url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNSIgdmlld0JveD0iMCAwIDEwIDUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xLjMzOTg0IDAuNzM5OTlMNC44Njk4NCA0LjI1OTk5TDguMzk5ODQgMC43Mzk5OSIgc3Ryb2tlPSJibGFjayIgc3Ryb2tlLXdpZHRoPSIxLjMiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K") no-repeat right center;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-position: center right 10px;
    }

    .wp-block-heading {
        font-weight: 600;
    }

    .pricetable-td-week a {
        text-decoration: none !important;
    }

    .pricetable-td-week:not(.pricetable-td-week-colored) a .pricetable-td-week-price,
    .pricetable-td-week:not(.pricetable-td-week-colored) a .pricetable-td-week-days {
        color: #3F1536;
    }

    .pricetable-filter-switcher [type="checkbox"]:checked,
    .pricetable-filter-switcher [type="checkbox"]:not(:checked),
    .pricetable-filter-switcher [type="radio"]:checked,
    .pricetable-filter-switcher [type="radio"]:not(:checked) {
        position: absolute;
        left: -9999px;
        width: 0;
        height: 0;
        visibility: hidden;
    }

    .pricetable-filter-switcher .checkbox:checked + label,
    .pricetable-filter-switcher .checkbox:not(:checked) + label {
        position: relative;
        width: 42px;
        display: inline-block;
        padding: 0;
        text-align: center;
        margin: 0;
        height: 20px;
        background: #E4E4EC;
        z-index: 99 !important;
        border-radius: 34px;
    }

    .pricetable-filter-switcher .checkbox:checked + label {
        background: #ca0013;
    }

    .pricetable-filter-switcher .checkbox:checked + label:before,
    .pricetable-filter-switcher .checkbox:not(:checked) + label:before {
        position: absolute;
        cursor: pointer;
        top: 2px;
        z-index: 2;
        font-size: 20px;
        line-height: 40px;
        text-align: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        -webkit-transition: all 300ms linear;
        transition: all 300ms linear;
    }

    .pricetable-filter-switcher .checkbox:not(:checked) + label:before {
        content: '';
        left: 3px;
        height: 16px;
        width: 16px;
        bottom: 4px;
        background-color: white;
    }

    .pricetable-filter-switcher .checkbox:checked + label:before {
        content: '';
        left: 23px;
        height: 16px;
        width: 16px;
        bottom: 4px;
        background-color: white;

    }

    .pricetable-filter-icon span {
        display: none;
    }
    .pricetable-filter-icon{
        text-decoration: none !important;
        color: #000;
    }

    #popup{
        width: 100%;
    }

    .cancel,
    .tt,
    .btnFilter{
        display: none;
    }

    @media (max-width: 1023px) {
        #popup .popup{
            width: 100%;
            max-width: 345px;
        }



        .pp{
            flex-direction: column;
        }

        /*body background during popup*/
        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            background: #01040A80;
            right: 0;
            backdrop-filter: blur(10px);
            transition: opacity 200ms;
            visibility: hidden;
            opacity: 0;
            padding: 20px;
        }

        /*cancel background popup click background*/
        .overlay .cancel {
            position: absolute;
            width: 30px;
            height: 30px;
            cursor: default;
            right: 10px;
            top: 10px;
            display: flex;
        }

        .overlay:target {
            visibility: visible;
            opacity: 1;
            z-index: 100;
        }

        .popup .tt{
            font-family: 'Saira', sans-serif;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            margin: 5px 0 20px;
            display: block;
        }

        /*popup*/
        .popup {
            display: inline-block;
            margin: auto;
            padding: 20px;
            background: #F7F9FC;
            border-radius: 8px;
            position: relative;
        }

        .popup .close {
            position: absolute;
            width: 20px;
            height: 20px;
            top: 20px;
            right: 20px;
            opacity: 0.8;
            transition: all 200ms;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: #666;
        }

        .popup .close:hover {
            opacity: 1;
        }

        .center-screen {
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .popup .content {
            max-height: 400px;
            max-width: 80vw;
            overflow: auto;
        }

        .pricetable-filter-icon span {
            display: inline-flex;
        }

        .pricetable-filter-icon {
            background: #fff url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNSIgdmlld0JveD0iMCAwIDEwIDUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xLjMzOTg0IDAuNzM5OTlMNC44Njk4NCA0LjI1OTk5TDguMzk5ODQgMC43Mzk5OSIgc3Ryb2tlPSJibGFjayIgc3Ryb2tlLXdpZHRoPSIxLjMiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K") no-repeat;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: center right 10px;
            display: flex;
            padding: 2px 11px;
            gap: 10px;
            border-radius: 8px;
            border: solid 1px #E4E4EC;
            align-items: center;
            min-height: 35px;
            width: 100%;
            justify-content: flex-start;
            max-width: 225px;
            margin: 0 auto;
        }

        .btnFilter{
            font-weight: 700;
            font-size: 16px;
            background: #000000;
            border-radius: 8px;
            padding: 6px;
            width: 100%;
            height: 35px;
            display: flex;
            align-items: center;
            margin-top: 15px;
            color: #fff !important;
            text-decoration: none;
            justify-content: center;
        }

        table.pricetable-table {
            table-layout: fixed;
            width: 100%;
            border-radius: 0px 0px 0px 0px;
            *margin-left: -100px; /*ie7*/
        }

        .pricetable-item-data {
            padding: 0px;
        }

        .inner{
            border-radius: 8px 0px 0px 0px !important;
            padding-right: 101px;
        }
        table.pricetable-table td, table.pricetable-table th {
            width: 60px;
            height: 60px;

        }
        table.pricetable-table td:first-child, table.pricetable-table th:first-child {
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .fix{
            border-bottom: none !important;
        }

        tbody tr:last-child .fix{
            border-bottom: solid 1px #E8E7E7 !important;
        }

        th:first-child{
        border-radius: 8px 0 0 !important;
    }

        table.pricetable-table th:first-child{
            justify-content: center;
        }
        .pricetable-th-week-title {
            line-height: 12px;
        }
        table.pricetable-table th{
            height: 42px;
        }

        table.pricetable-table td:first-child, table.pricetable-table th:first-child {
            width: 177px;
        }
        table.pricetable-table .fix {
            position: absolute;
            *position: relative; /*ie7*/
            margin-left: -100px;
            width: 100px;
        }
        .outer {
            position: relative;
        }
        .inner {
            overflow-x: scroll;
            overflow-y: visible;
            width: 100%;
            margin-left: 100px;
        }

        main .wp-block-columns{
            padding-right: 0;
        }

        .pricetable-item-image{
            display: none;
        }
        table.pricetable-table td:first-child, table.pricetable-table th:first-child {
            width: 101px;
        }
        .inner {
            border-radius: 0px !important;
        }


    }

    @media (max-width: 568px) {
        .wp-block-spacer{
            height: 30px !important;
        }
        .pricetable-item-image img{
            max-width: 70px;
            height: 100%;
            object-fit: cover;
            max-height: 60px;
            width: 70px;
        }
        .mobHide{
            display: none;
        }




    }


</style>