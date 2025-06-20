<style>
    .price-list-filters {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-content: center;
        justify-content: center;
        align-items: center;
    }

    .price-list-filters div {
        display: flex;
        align-items: center;
        margin: 20px;
    }

    .price-list-filters div label {
        margin-right: 10px;
        margin-bottom: 0;
    }

    #num-beds {
        width: 40px;
        height: 40px;
        background: none;
        border: none;
        color: black;
        font-size: 18px;
        font-weight: 500;
        padding: 0px;
        text-align: center;
    }

    .minus-client, .plus-client {
        cursor: pointer;
    }
</style>

<div class="price-list-filters">
    <div><label for=""><?php _e('Month', 'beds24'); ?></label>
        <select name="filter-month" id="">
            <option value="1"><?php _e('January', 'beds24'); ?></option>
            <option value="2"><?php _e('February', 'beds24'); ?></option>
            <option value="3"><?php _e('March', 'beds24'); ?></option>
            <option value="4"><?php _e('April', 'beds24'); ?></option>
            <option value="5"><?php _e('May', 'beds24'); ?></option>
            <option value="6"><?php _e('June', 'beds24'); ?></option>
            <option value="7"><?php _e('July', 'beds24'); ?></option>
            <option value="8"><?php _e('August', 'beds24'); ?></option>
            <option value="9"><?php _e('September', 'beds24'); ?></option>
            <option value="10"><?php _e('October', 'beds24'); ?></option>
            <option value="11"><?php _e('November', 'beds24'); ?></option>
            <option value="12"><?php _e('December', 'beds24'); ?></option>

        </select>
    </div>
    <div>
        <label for=""><?php _e('Mountain Area', 'beds4'); ?></label>
        <select name="filter-area" id="">
            <option value="">Område</option>
            <option value="hogfjallet"><?php _e('Högfjället', 'beds24'); ?></option>
            <option value="hundfjallet"><?php _e('Hundfjället', 'beds24'); ?></option>
            <option value="lindvallen"><?php _e('Lindvallen', 'beds24'); ?></option>
            <option value="tandadalen"><?php _e('Tandådalen', 'beds24'); ?></option>
        </select>
    </div>
    <div>
        <label for=""><?php _e('Number of beds', 'beds4'); ?></label>
        <svg class="minus-client" id="minus-adult" width="32" height="32" viewBox="0 0 32 32" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"></rect>
            <path d="M11 16.7505V15.2495H21V16.7505H11Z" fill="black"></path>
            <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"></rect>
        </svg>

        <input type="number" id="num-beds" value="1" name="number-beds" readonly="">

        <svg class="plus-client" id="plus-adult" width="32" height="32" viewBox="0 0 32 32" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" fill="#F7F9FC"></rect>
            <path d="M15.2308 21V16.6998H11V15.1988H15.2308V11H16.7692V15.1988H21V16.6998H16.7692V21H15.2308Z"
                  fill="black"></path>
            <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#E4E4EC"></rect>
        </svg>
    </div>
    <div>
        <label for=""><?php _e('Allowed for dogs', 'beds4'); ?></label>
        <div class="checkbox-wrapper-6">
            <input class="tgl tgl-light" id="cb1-6" type="checkbox"/>
            <label class="tgl-btn" for="cb1-6">
        </div>
    </div>
</div>

<div class="price-list-wrap">
    <!--    first get houses in foreach, then foreach by periods-->
</div>


<style>
    .checkbox-wrapper-6 .tgl {
        display: none;
    }

    .checkbox-wrapper-6 .tgl,
    .checkbox-wrapper-6 .tgl:after,
    .checkbox-wrapper-6 .tgl:before,
    .checkbox-wrapper-6 .tgl *,
    .checkbox-wrapper-6 .tgl *:after,
    .checkbox-wrapper-6 .tgl *:before,
    .checkbox-wrapper-6 .tgl + .tgl-btn {
        box-sizing: border-box;
    }

    .checkbox-wrapper-6 .tgl::-moz-selection,
    .checkbox-wrapper-6 .tgl:after::-moz-selection,
    .checkbox-wrapper-6 .tgl:before::-moz-selection,
    .checkbox-wrapper-6 .tgl *::-moz-selection,
    .checkbox-wrapper-6 .tgl *:after::-moz-selection,
    .checkbox-wrapper-6 .tgl *:before::-moz-selection,
    .checkbox-wrapper-6 .tgl + .tgl-btn::-moz-selection,
    .checkbox-wrapper-6 .tgl::selection,
    .checkbox-wrapper-6 .tgl:after::selection,
    .checkbox-wrapper-6 .tgl:before::selection,
    .checkbox-wrapper-6 .tgl *::selection,
    .checkbox-wrapper-6 .tgl *:after::selection,
    .checkbox-wrapper-6 .tgl *:before::selection,
    .checkbox-wrapper-6 .tgl + .tgl-btn::selection {
        background: none;
    }

    .checkbox-wrapper-6 .tgl + .tgl-btn {
        outline: 0;
        display: block;
        width: 4em;
        height: 2em;
        position: relative;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .checkbox-wrapper-6 .tgl + .tgl-btn:after,
    .checkbox-wrapper-6 .tgl + .tgl-btn:before {
        position: relative;
        display: block;
        content: "";
        width: 50%;
        height: 100%;
    }

    .checkbox-wrapper-6 .tgl + .tgl-btn:after {
        left: 0;
    }

    .checkbox-wrapper-6 .tgl + .tgl-btn:before {
        display: none;
    }

    .checkbox-wrapper-6 .tgl:checked + .tgl-btn:after {
        left: 50%;
    }

    .checkbox-wrapper-6 .tgl-light + .tgl-btn {
        background: #f0f0f0;
        border-radius: 2em;
        padding: 2px;
        transition: all 0.4s ease;
    }

    .checkbox-wrapper-6 .tgl-light + .tgl-btn:after {
        border-radius: 50%;
        background: #fff;
        transition: all 0.2s ease;
    }

    .checkbox-wrapper-6 .tgl-light:checked + .tgl-btn {
        background: #2cc652;
    }
</style>



