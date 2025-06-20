<style>
    .custom-1-select {
        border-radius: 10px 0 0 10px;
        appearance: none;      /* Прибирає стандартний вигляд */
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #fff;      /* Колір фону */
        width: 100%;
        position: relative;
    }

    .custom-1-select::after {
        content: "▼";         /* Символ стрілки */
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* Стрілка не заважає вибору */
        font-size: 12px;
        color: #333;          /* Колір стрілки */
    }

    .custom-1-select select {
        padding-right: 30px;  /* Відступ, щоб було місце для стрілки */
        background: transparent;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .new_wrap_date::before {
        content: "";
        position: absolute;
        left: 0;               /* Вирівнювання лінії ліворуч */
        top: 25%;              /* Початок з 25% висоти */
        height: 50%;           /* Лінія на 50% висоти елемента */
        width: 1px;            /* Ширина лінії */
        background-color: #cfcfcf; /* Колір лінії */
    }
    .divider::before {
        content: "";
        position: absolute;
        left: 0;               /* Вирівнювання лінії ліворуч */
        top: 25%;              /* Початок з 25% висоти */
        height: 50%;           /* Лінія на 50% висоти елемента */
        width: 1px;            /* Ширина лінії */
        background-color: #cfcfcf; /* Колір лінії */
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
    .d_e_new{
        background-image: none !important;
        padding: 0 !important;
        width: 140px;
        text-indent: 0px !important;
        text-align: right !important;
        /*border-radius: 9px;*/
    }
    .area-select{
        color: black !important;
    }
    .d_s_new_select{
        padding: 0px 0px 0px 25px !important;
    }
    .d_e_new_select{
    }
    .d_s_new{
        padding: 0px 0px 0px 55px !important;
        width: 290px;
        text-indent: 0 !important;
    }
    .new_wrap_date{
        display: flex;
        width: 50%;
        background: white;
        /*border-radius: 9px;*/
    }
    .rb-search-form select{
        border: none;
        padding: 16px 65px;
        border-radius: 8px 0 0 8px;
        cursor: pointer;
    }
    @media all and (min-width: 500px) and (max-width: 1200px){
        .d_e_new{
            width: 130px !important;
        }
        .d_s_new{
            width: 190px !important;
        }
        .new_wrap_date::before,
        .divider::before{
            display: none;
        }
    }
    @media all and (max-width: 500px){
        .rb-search-form select{
            padding: 16px 56px !important;
            font-weight: 700;
            font-size: 19px;
        }
        .d_s_new{
            text-indent: 0 !important;
        }
        .new_wrap_date::before,
        .divider::before{
            display: none;
        }
        .custom-1-select{
            border-radius: 10px 10px 0 0;
        }
        .new_wrap_date{
            height: 60px;
            border-top: 1px solid #d9d9d9;
        }
    }
</style>
<?php
$loc = get_locale();

$action = 'index.php/boende/';
if ($loc == 'en_US'){
    $action = 'accommodation/';//index.php/en
}
?>
<form method="GET" id="start_form" class="rb-search-form" action="<?php echo $action; ?>"><!--index.php/results-->

    <div class="row">

        <div class="col-md-12">

            <div class="form_s d-flex">

                <div class="custom-1-select">
                    <select name="area" class="inp-block-users plats" id="area-select" style="text-indent: 0 !important;">
                        <?php
                        $args = array( 'hide_empty' => 0 );
                        $terms = get_terms('product_tag', $args );
                        $lindvallenHogfjallet = array_filter($terms, function($t){
                            return in_array($t->slug, ['lindvallen', 'hogfjallet']);
                        });
                        $termsToOptions = [];
                        foreach($terms AS $t){
                            if(in_array($t->slug, ['lindvallen', 'hogfjallet'])){
                                $k = implode('/', array_map(function($lht){
                                    return $lht->slug;
                                }, $lindvallenHogfjallet));
                                $n = implode('/', array_map(function($lht){
                                    return $lht->name;
                                }, $lindvallenHogfjallet));
                            }
                            else{
                                $k = $t->slug;
                                $n = $t->name;
                            }
                            $termsToOptions[$k] = $n;
                        }
              
                        if($_GET['area']){
                            echo '<option disabled selected hidden>'.$_GET['area'].'</option>';
                            echo '<option >';_e('Område', 'beds24'); echo '</option>';
                        }else{
                            echo '<option value="">';_e('Område', 'beds24');echo '</option>';
                        }
                        foreach($termsToOptions as $val => $label){ ?>
                            <option value="<?php echo $val ?>"><?php echo $label ?></option>
                        <?php } ?>
                    </select>
                </div>

<!--                --><?php
                if ($loc == 'en_US'){
                    ?>
                    <input type="hidden" name="lang" value="en">
                    <?php
                }
//                if (get_current_user_id() == 2){
//                ?>
                <div class="new_wrap_date inp-block-users">
                    <input class="d_s_new" type="text" readonly id="date-3_1"
                           placeholder='<?php _e('Datum','beds24'); ?>'/>
<!--                    <div class="arrow-down" style="background: url('/wp-content/plugins/beds24-booking/assets/img/arrow-down.png');"></div>-->
                    <input type="hidden" name="date_start" id="startDateNew">
                    <input type="hidden" name="date_end" id="endDateNew">
<!--                    <input type="text" placeholder="/" style="width: 10px;padding: 0 !important;">-->
<!--                    <input class="d_e_new" type="text" readonly name="date_end" id="date-3_2"-->
<!--                           placeholder="--><?php //_e('Avresa','beds24'); ?><!--"/>-->
                </div>
<!--                --><?php //}?>
<!--                <input type="text" readonly name="date_start" id="date-3_1"-->
<!--                       placeholder='--><?php //the_field('Tilltrade', 'option'); ?><!--'/>-->
<!---->
<!--                <input type="text" readonly name="date_end" id="date-3_2"-->
<!--                       placeholder="--><?php //the_field('Avresa', 'option'); ?><!--"/>-->

                <div class="inp-block-users divider">

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


                                <input type="number" id="num-adult" value="1" name="number-adult" readonly>

                                <svg class="plus-client" id="plus-adult" width="32" height="32" viewBox="0 0 32 32"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">

                                    <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                    <path d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z"
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

                                <input type="number" id="num-child" value="0" name="number-child" readonly>

                                <svg class="plus-client" id="plus-child" width="32" height="32" viewBox="0 0 32 32"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">

                                    <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"/>

                                    <path d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z"
                                          fill="black"/>

                                    <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"/>

                                </svg>

                            </div>

                        </div>

                        <hr>

                        <div class="clients-blk">
                            <label class="switcher-container" for="animals" style="width: 100%;justify-content: space-between;">
                                <?php //_e('Vi har hund med oss.', 'beds24'); ?>
                                <div>
                                    <p style="font-weight: 600;color: black; font-size: 18px; margin-bottom: 0"><?php _e('Hund', 'beds24'); ?></p>
                                    <p style="margin-bottom: 0; color: #595959;"><?php _e('Deposition', 'beds24'); ?> 500 SEK</p>
                                </div>
                                <input type="checkbox" id="animals" name="animals">
                                <span class="switchmark"></span>
                            </label>
                        </div>

                    </div>

                </div>

                <div class="search_button">
                    <input type="submit" class="btn-sbm" value="<?php the_field('sok', 'option'); ?>">
                </div>

            </div>

        </div>
        <div class="col-md-7" id="gist-3"></div>
    </div>

</form>

<script>
    // jQuery('body').on('click', '.day-item', function () {
    //     console.log('test')
    //     jQuery('.d_s_new').addClass('d_s_new_select');
    // })
</script>