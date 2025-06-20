<style>
    .custom-1-select {
        border-radius: 10px 0 0 10px;
        appearance: none; /* Прибирає стандартний вигляд */
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #fff; /* Колір фону */
        width: 100%;
        position: relative;
    }

    .custom-1-select::after {
        content: "▼"; /* Символ стрілки */
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* Стрілка не заважає вибору */
        font-size: 12px;
        color: #333; /* Колір стрілки */
    }

    .custom-1-select select {
        padding-right: 30px; /* Відступ, щоб було місце для стрілки */
        background: transparent;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .new_wrap_date::after {
        content: '\25BC'; /* Unicode for the down arrow */
        position: absolute;
        right: 10px; /* Position it on the right */
        top: 50%;
        transform: translateY(-50%); /* Center it vertically */
        font-size: 12px;
        color: #333; /* Arrow color */
    }

    .inp-block-users::after {
        content: '\25BC'; /* Unicode for the down arrow */
        position: absolute;
        right: 10px; /* Position it on the right */
        top: 50%;
        transform: translateY(-50%); /* Center it vertically */
        font-size: 12px;
        color: #333; /* Arrow color */
    }

    .new_wrap_date::before {
        content: "";
        position: absolute;
        left: 0; /* Вирівнювання лінії ліворуч */
        top: 25%; /* Початок з 25% висоти */
        height: 50%; /* Лінія на 50% висоти елемента */
        width: 1px; /* Ширина лінії */
        background-color: #cfcfcf; /* Колір лінії */
    }

    .inp-block-users::before {
        content: "";
        position: absolute;
        left: 0; /* Вирівнювання лінії ліворуч */
        top: 25%; /* Початок з 25% висоти */
        height: 50%; /* Лінія на 50% висоти елемента */
        width: 1px; /* Ширина лінії */
        background-color: #cfcfcf; /* Колір лінії */
    }

    @media all and (max-width: 1200px) {
        .new_wrap_date::before,
        .inp-block-users::before {
            display: none;
        }
    }
</style>
<?php
//var_dump($_GET);
if (!empty($_GET['date_start'])) {
    $date_start = $_GET['date_start'];
//    $dateTime = DateTime::createFromFormat('m/d/Y', $date_start); // Create DateTime object from MM/DD/YYYY
//$date_start = $dateTime->format('Y-m-d');
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
//    $dateTime1 = DateTime::createFromFormat('m/d/Y', $date_end); // Create DateTime object from MM/DD/YYYY
//$date_end = $dateTime1->format('Y-m-d');
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

//var_dump($date_start);
//var_dump($date_end);


$adult = 1;

if (!empty($_GET['number-adult'])) {
    $adult = $_GET['number-adult'];
}

$children = 0;

if (!empty($_GET['number-child'])) {
    $children = $_GET['number-child'];
}


$animals = '';
if (!empty($_GET['animals'])) {
    $animals = $_GET['animals'];
}

if (empty($terms)) {
    $terms = get_terms('product_tag', [
        'hide_empty' => false
    ]);
}
$lindvallenHogfjallet = array_filter($terms, function ($t) {
    return in_array($t->slug, ['lindvallen', 'hogfjallet']);
});
$termsToOptions = [];
foreach ($terms as $t) {
    if (in_array($t->slug, ['lindvallen', 'hogfjallet'])) {
        $k = implode('/', array_map(function ($lht) {
            return $lht->slug;
        }, $lindvallenHogfjallet));
        $n = implode('/', array_map(function ($lht) {
            return $lht->name;
        }, $lindvallenHogfjallet));
    } else {
        $k = $t->slug;
        $n = $t->name;
    }
    $termsToOptions[$k] = $n;
}

?>

<form method="GET" id="start_form" class="rb-result-form" action="index.php/">
    <div class="row col-md-12" style="margin: 0; padding: 0;">
        <div class="form_s d-flex" style="width: 100%;">
            <div class="custom-1-select" style="width: 100%">
                <select name="area" id="area-select">
                    <?php
                    if (!empty($_GET['area'])) {
                        echo '<option disabled selected hidden>' . $termsToOptions[$_GET['area']] . '</option>';
                        echo '<option value="">';
                        _e('Område', 'beds24');
                        echo '</option>';
                    } else {
                        echo '<option value="">';
                        _e('Område', 'beds24');
                        echo '</option>';
                    }
                    foreach ($termsToOptions as $val => $label) { ?>
                        <option value="<?php echo $val ?>"><?php echo $label ?></option>
                    <?php } ?>
                </select></div>
            <div class="new_wrap_date" style="width: 100%;position: relative;">
                <input type="text" readonly id="date-3_1"
                       value="<?php if (!empty($formatted_date_start) and !empty($formatted_date_end)) {
                           echo $formatted_date_start . ' → ' . $formatted_date_end;
                       } ?>"
                       placeholder="<?php if (!empty($formatted_date_start) and !empty($formatted_date_end)) {
                           echo $formatted_date_start . ' → ' . $formatted_date_end;
                       } else _e('Datum', 'beds24'); ?>"/>
                <input type="hidden" name="date_start" id="startDateNew" value="<?php echo $date_start; ?>">
                <input type="hidden" name="date_end" id="endDateNew" value="<?php echo $date_end; ?>">
                <!--            <input type="text" readonly name="date_end" id="date-3_2" value="-->
                <?php //echo $date_end; ?><!--"-->
                <!--                   placeholder="--><?php //_e('Avresa','beds24'); ?><!--"/>-->
            </div>
            <?php
            if (get_locale() == 'en_US') {
                ?>
                <input type="hidden" name="lang" value="en">
                <?php
            }
            ?>
            <div class="inp-block-users" style="width: 100%">

                <input type="text" readonly id="adult-select" name="adult" required
                       placeholder="<?php the_field('gaster', 'option'); ?>">

                <div class="form-clients">

                    <div class="clients-blk">

                        <div>

                            <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Vuxna', 'beds24'); ?></p>

                            <p style="margin-bottom: 0; color: #595959;"><?php _e('Från 13 år', 'beds24'); ?></p>

                        </div>

                        <div class="pl-mi-btn">

                            <svg class="minus-client" id="minus-adult" width="32" height="32" viewBox="0 0 32 32"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                <path d="M11 16.7505V15.2495H21V16.7505H11Z" fill="black"/>

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>

                            </svg>


                            <input type="number" id="num-adult" value="<?php echo $adult; ?>" name="number-adult"
                                   readonly>

                            <svg class="plus-client" id="plus-adult" width="32" height="32" viewBox="0 0 32 32"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                <path
                                    d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z"
                                    fill="black"/>

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>

                            </svg>


                        </div>

                    </div>

                    <hr>

                    <div class="clients-blk">

                        <div>

                            <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Barn', 'beds24'); ?></p>

                            <p style="margin-bottom: 0; color: #595959;"><?php _e('Åldrar 2–12', 'beds24'); ?></p>

                        </div>

                        <div class="pl-mi-btn">

                            <svg class="minus-client" id="minus-child" width="32" height="32" viewBox="0 0 32 32"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                <path d="M11 16.7505V15.2495H21V16.7505H11Z" fill="black"/>

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>

                            </svg>


                            <input type="number" id="num-child" value="<?php echo $children; ?>" name="number-child"
                                   readonly>

                            <svg class="plus-client" id="plus-child" width="32" height="32" viewBox="0 0 32 32"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                <path
                                    d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z"
                                    fill="black"/>

                                <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>

                            </svg>


                        </div>

                    </div>

                    <hr>

                    <div class="clients-blk">
                        <label for="animals" class="switcher-container"
                               style="width: 100%;justify-content: space-between;">
                            <?php //_e('Vi har hund med oss.', 'beds24'); ?>
                            <div>
                                <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Hund', 'beds24'); ?></p>
                                <p style="margin-bottom: 0; color: #595959;"><?php _e('Deposition', 'beds24'); ?> 500
                                    SEK</p>
                            </div>
                            <input type="checkbox" id="animals" name="animals" <?php if ($animals == 'on') {
                                echo 'checked';
                            } ?>>
                            <span class="switchmark"></span>
                        </label>

                    </div>

                </div>

            </div>

            <div class="search_button">
                <input type="submit" style="width: 100%" class="btn-sbm" value="<?php _e('Sök', 'beds24'); ?>">
            </div>

        </div>


        <div class="col-md-7" id="gist-3"></div>


    </div>


</form>