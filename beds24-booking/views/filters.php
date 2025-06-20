<?php  
$gets = $_GET;
if(isset($_GET["sovrum"])){
    $sovrum = $_GET["sovrum"];
}else{
    $sovrum = '';
}
if(isset($_GET["skidlift"])){
    $skidlift = $_GET["skidlift"];
}else{
    $skidlift = '';
}
if(isset($_GET["parstring1"])){
    $parstring1 = explode(';', $_GET["parstring1"]);
}else{
    $parstring1 = '';
}
if(isset($_GET["parstring2"])){
    $parstring2 = explode(';', $_GET["parstring2"]);
}else{
    $parstring2 = '';
}



?>

<style>
    #beds_filter img{
        fill: black;
    }
    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        z-index: 1;
    }
    input[type=range]:focus {
        outline: none;
    }
    input[type=range]::-webkit-slider-runnable-track {
        width: 100%;
        height: 40px;
        cursor: pointer;
        box-shadow: 0px 2px 45px 0px rgba(184, 204, 222, 0.25);
        background: #fff;
        border-radius: 4px;
    }
    input[type=range]::-webkit-slider-thumb {
        border: 1px solid #000000;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        background: #fff !important;
        cursor: pointer;
        -webkit-appearance: none;
    }
    input[type=range]::-moz-range-track {
        width: 100%;
        height: 40px;
        cursor: pointer;
        box-shadow: 0px 2px 45px 0px rgba(184, 204, 222, 0.25) !important;
        background: #fff;
        border-radius: 4px;
    }
    input[type=range]::-moz-range-thumb {
        border: 1px solid #000000!important;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        background: #fff !important;
        cursor: pointer;
    }
    input[type=range]::-ms-track {
        width: 100%;
        height: 40px;
        cursor: pointer;
        animate: 0.2s;
        background: transparent;
        border-color: transparent;
        border-width: 39px 0;
        color: transparent;
    }
    input[type=range]::-ms-fill-lower {
        background: #fff !important;
        border: 0px solid #000 !important;
        border-radius: 4px;
        box-shadow: 0px 2px 45px 0px rgba(184, 204, 222, 0.25) !important;
    }
    input[type=range]::-ms-fill-upper {
        background: #fff !important;
        border: 0px solid #000 !important;
        border-radius: 4px;
        box-shadow: 0px 2px 45px 0px rgba(184, 204, 222, 0.25) !important;
    }
    input[type=range]::-ms-thumb {
        border: 1px solid #000000 !important;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        background: #fff !important;
        cursor: pointer;
    }
    .wrap {
        position: relative;
    }
    output {
        position: absolute;
        top: -60%;
        width: 50px;
        transform: translateX(-20px);
    }
</style>

<div id="beds_filter" class="filter-wrap">

    <div class="filter-btn">

        <div class="col-md-12 btn-filter-wrap">
            <div>
              <button class="btn" id="btn-filter"><img src="<?php echo BEDS_URL;?>assets/svg/filter.svg"> <?php _e('FILTER','beds24'); ?></button>
            </div>
            <div>
              <button class="btn btn-map" data-tab="list" id="btn-map"><img src="<?php echo BEDS_URL;?>assets/svg/map.svg"> <?php _e('Karta','beds24'); ?></button>
              <button class="btn" data-tab="list" id="btn-lista" style="display: none;background-color: white; border: 2px solid #010E2C; color: #010E2C"><?php _e('Visa Lista','beds24'); ?></button>
            </div>

        </div>

        <div class="col-md-12 row" id="filters-body" style="text-align: center; display: none; padding: 0;margin: 0;">



            <!-- <div class="col-md-12"> -->

<!--                <div class="range-wrap">-->

            <div class="col-12 mb-4"></div>
              <div class="col-md-6 mb-3">
                <div style="padding-bottom: 20px; font-weight: bold; text-align: left; text-transform: uppercase">
                  <label class="mb-2"><?php _e('Sovrum (Minst)','beds24'); ?></label>
                </div>
                <form class="">
                    <div class="wrap" style="position:relative; margin:auto; width:100%">
                        <?php if($sovrum){ ?>
                            <output class="inputValue" id="inputValue1" name="result"><?php echo $sovrum; ?></output>
                            <input id="sovrum" style="width: 100%" type="range" class="inputRange" name="bedrooms" max="3" step="1" min="1" oninput="result.value=parseInt(bedrooms.value)" value="<?php echo $sovrum; ?>">
                        <?php }else{ ?>
                            <output class="inputValue" id="inputValue1" name="result">1</output>
                            <input id="sovrum" style="width: 100%" type="range" class="inputRange" name="bedrooms" max="3" step="1" min="1" oninput="result.value=parseInt(bedrooms.value)" value="1">
                        <?php } ?>
                    </div>
                    <script>
                        let sovrum = $("#sovrum");
                        let inputValue1 = $("#inputValue1");
                        const setValue = () => {
                        let newValue = Number(
                            ((sovrum.val() - sovrum.attr("min")) * 100) /
                                (sovrum.attr("max") - sovrum.attr("min"))
                            ),
                            newPosition = 10 - newValue * 0.35;
                        inputValue1.html(sovrum.val());
                        inputValue1.css("left", `calc(${newValue}% + (${newPosition}px))`);
                        };
                        setValue();
                        sovrum.on("input", setValue);
                    </script>

                </form>
              </div>
              <div class="col-md-6 mb-3">
                <div style="padding-bottom: 20px; font-weight: bold; text-align: left; text-transform: uppercase">
                  <label class="mb-2"><?php _e('Avstånd till lift (Högst m)','beds24'); ?></label>
                </div>
                <form class=""><!-- style="padding: 0" oninput="result.value=parseInt(ski.value)">-->
                  <div class="wrap" style="/*width: 85%;*/">
                    <?php if($skidlift){ ?>
                      <output name="result" class="inputValue" id="inputValue2"><?php echo $skidlift; ?></output>
                      <input id="skidlift" type="range" class="inputRange" name="ski" max="2500" step="100" min="100" oninput="result.value=parseInt(ski.value)" value="<?php echo $skidlift; ?>">
                    <?php }else{ ?>
                      <output name="result" class="inputValue" id="inputValue2">2500</output>
                      <input id="skidlift" type="range" class="inputRange" name="ski" max="2500" step="100" min="100" oninput="result.value=parseInt(ski.value)" value="2500">
                    <?php } ?>
                  </div>
                  <script>
                    let skidlift = $("#skidlift");
                    let inputValue2 = $("#inputValue2");
                    const setValue1 = () => {
                    let newValue1 = Number(
                        ((skidlift.val() - skidlift.attr("min")) * 100) /
                            (skidlift.attr("max") - skidlift.attr("min"))
                        ),
                        newPosition = 10 - newValue1 * 0.35;
                    inputValue2.html(skidlift.val());
                    inputValue2.css("left", `calc(${newValue1}% + (${newPosition}px))`);
                    };
                    setValue1();
                    skidlift.on("input", setValue1);
                  </script>
                </form>
              </div>
            <div class="col-12"></div>

            <div class="col-md-12">
              <div style="font-weight: bold; text-align: left; text-transform: uppercase">
                <label ><?php the_field('egenskaper','option'); ?></label>
              </div>
            </div>

            <div class="filter-blocks top_blocks">

                <div class="filter-case" >
                    <?php
                        if($parstring1){
                            if(in_array("_product_hundtillåtet", $parstring1)){ ?>
                                <div id="dog-enable" data-item="_product_hundtillåtet" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div id="dog-enable" data-item="_product_hundtillåtet" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div id="dog-enable" data-item="_product_hundtillåtet" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/pet.svg" alt="dogs-allowed">

                        </div>
                        <div class="fil-brn-text">
                          <label for=""><?php the_field('hundtillatet','option'); ?></label>
                        </div>
                    </div>
                </div>



                <div class="filter-case" >
                    <?php
                        if($parstring1){
                            if(in_array("_product_wi_fi", $parstring1)){ ?>
                                <div data-item="_product_wi_fi" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_wi_fi" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_wi_fi" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/wifi.svg" alt="wi-fi">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('wi-fi','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring1){
                            if(in_array("_product_bastu", $parstring1)){ ?>
                                <div data-item="_product_bastu" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_bastu" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_bastu" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/bastu.svg" alt="sauna">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('bastu','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring1){
                            if(in_array("_product_oppen_spis", $parstring1)){ ?>
                                <div data-item="_product_oppen_spis" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_oppen_spis" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_oppen_spis" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/oppen spis.svg" alt="fireplace">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('oppen_spis','option'); ?></label>

                        </div>

                    </div>

                </div>



                <div class="filter-case" >
                    <?php
                        if($parstring1){
                            if(in_array("_product_laddning_elbil", $parstring1)){ ?>
                                <div data-item="_product_laddning_elbil" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_laddning_elbil" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_laddning_elbil" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL; ?>assets/svg/2.svg" alt="area">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php _e('Laddning elbil','beds24'); ?></label>

                        </div>

                    </div>

                </div>



            </div>



            <div class="col-md-12 mt-3">

                <div style="font-weight: bold; text-align: left; text-transform: uppercase">

                    <label ><?php the_field('utrustning','option'); ?></label>

                </div>

            </div>



            <div class="filter-blocks bottom_blocks">

                <div class="filter-case">
                    <?php
                        if($parstring2){
                            if(in_array("_product_diskmaskin", $parstring2)){ ?>
                                <div data-item="_product_diskmaskin" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_diskmaskin" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_diskmaskin" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/dishwasher.svg" alt="dishwasher">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('diskmaskin','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring2){
                            if(in_array("_product_tvättmaskin", $parstring2)){ ?>
                                <div data-item="_product_tvättmaskin" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_tvättmaskin" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_tvättmaskin" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/laundry.svg" alt="washing-machine">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('tvattmaskin','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring2){
                            if(in_array("_product_torkskåp", $parstring2)){ ?>
                                <div data-item="_product_torkskåp" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_torkskåp" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_torkskåp" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/drying-machine.svg" alt="drying-cabinet">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('torkskap','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring2){
                            if(in_array("_product_barnstol", $parstring2)){ ?>
                                <div data-item="_product_barnstol" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_barnstol" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_barnstol" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/baby-chair.svg" alt="highchair">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('barnstol','option'); ?></label>

                        </div>

                    </div>

                </div>

                <div class="filter-case">
                    <?php
                        if($parstring2){
                            if(in_array("_product_barnsäng", $parstring2)){ ?>
                                <div data-item="_product_barnsäng" class="col-md-12 row filter-wrap-block active active-filter">
                        <?php }else{ ?>
                                <div data-item="_product_barnsäng" class="col-md-12 row filter-wrap-block">
                        <?php } 
                        }else{ ?>
                            <div data-item="_product_barnsäng" class="col-md-12 row filter-wrap-block">
                    <?php } ?>

                        <div class="fil-icon-wrap">

                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/svg/baby-crib.svg" alt="Crib">

                        </div>

                        <div class="fil-brn-text">

                            <label for=""><?php the_field('barnsang','option'); ?></label>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

                                    