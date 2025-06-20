<?php
global $wpdb;
//methods
if (isset($_POST['update-props'])) {
    foreach ($_POST as $key => $val) {
        if ($key != 'update-props' and !empty($val)) {
            $prop = str_replace('prop', '', $key);
            $wpdb->query('update `beds_properties` set propKey="' . $val . '" where propertyId="' . $prop . '"');
        }
    }
}


require_once(BEDS_DIR . '/includes/class.action.php');
$act = new \beds_booking\Action_beds_booking();

if (isset($_POST['upd-prop'])) {
    $res = $act->updatePropTable();
    if ($res != false or $res < 0) {
        echo "
        <p style='background-color: green; color: white;'>All properties successfully updated!</p>
        ";
    } elseif ($res == 0) {
        echo "       
        <p style='background-color: #ecc415; color: white;'>Properties not updated! No Changes in API.</p>
";
    } else {
        echo "       
        <p style='background-color: red; color: white;'>Properties not updated!</p>
";
    }
}
if (isset($_POST['upd-price'])){

//    die();
//    if ($act->cleareDates($_POST['d_start'],$_POST['d_end'])){
        $act->cleareDates($_POST['d_start'],$_POST['d_end']);

        if ($newDates = $act->setDataInCalendar($_POST['d_start'],$_POST['d_end'])){
            $res = $act->updateCalendar($_POST['d_start'],$_POST['d_end']);
        } 
//        else {$res = false;}
//    } else {
//        $res = false;
//    }
//    var_dump($newDates);
//    var_dump($res);
    
//    if ($res != false or $res < 0) {
        echo "
        <p style='background-color: green; color: white;'>All properties successfully updated!</p>
        ";
//    } elseif ($res == 0) {
//        echo "
//        <p style='background-color: #ecc415; color: white;'>Properties not updated! No Changes in API.</p>
//";
//    } else {
//        echo "
//        <p style='background-color: red; color: white;'>Properties not updated!</p>
//";
//    }
}
if (isset($_POST['setCsv'])){
    if (!empty($_FILES)){
        $uploaddir = BEDS_DIR.'/csvdir/';
        $fileName = date("Y-m-d-H-i-s").'.csv';
        $uploadfile = $uploaddir . $fileName;

        if (move_uploaded_file($_FILES['csvPrices']['tmp_name'], $uploadfile)) {
            $res = $act->parseCSV($fileName);
            $res = json_decode($res,1);
            $good = 0;
            $bed = 0;
            foreach ($res as $re){
                if (key($re) == 'success'){
                    $good++;
                } else {
                    $bed++;
                }
            }

            echo "
        <p style='background-color: green; color: white;'>All prices updated! ".$good." successfully operations, and ".$bed." false operations!</p>
        ";
        } else {
            echo "       
        <p style='background-color: red; color: white;'>Prices not updated! API, Network or file error!</p>
";
        }
    }
}
?>
<style>
    .prop-form {
        display: flex;
        width: 97%;
        flex-direction: column;
    }

    .admin-label-prop {
        width: 100%;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        padding: 5px 0;
    }

    .admin-label-prop input {
        width: 80%;
        margin: auto;
    }

    .admin-prop-btn {
        font-weight: bold;
        font-size: 14px;
        width: 100px;
        padding: 10px;
        color: white;
        background-color: #54e865;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .admin-block {
        border-radius: 3px;
        border: 1px black solid;
        padding: 15px;
        margin-right: 15px;
    }

    .w-200 {
        width: 200px;
    }
</style>
<script src="<?= BEDS_URL ?>assets/js/admin-page.js">

</script>

<h2>Settings page</h2>

<div class="admin-block">
    <h4>Data Updating</h4>
    <div>
        <p>Update properties data from Beds24, if there have changes.</p>
        <div>
            <form action="" method="post">
                <button class="admin-prop-btn w-200" name="upd-prop">Update properties data</button>
                <article>*Property name, photos, address, people count by room etc.</article>
            </form>
        </div>
    </div>

</div>

<div class="admin-block">
    <h4>Data Updating</h4>
    <div>
        <p>Update calendar data from Beds24, if there have changes.</p>
        <div>
            <form action="" method="post">
                <label for="">
                    <input type="date" name="d_start">
                    <input type="date" name="d_end">
                </label>

                <button class="admin-prop-btn w-200" name="upd-price">Update available & prices</button>
                <article>* if dates set empty, by default use period 1 year from today</article>
            </form>
        </div>
    </div>
</div>

<div class="admin-block">
    <h4>Set prices csv</h4>
    <div>
        <p>Upload .csv file with prices on Beds24 API</p>
        <div>
            <form action="" method="post" enctype="multipart/form-data">
                <label>Select file: <input type="file" name="csvPrices"></label>
                <button class="admin-prop-btn w-200" name="setCsv">Upload</button>
            </form>
        </div>
    </div>
</div>

<div class="admin-block">

    <h4>PropKey`s block</h4>
    <?php
    $propCount = $wpdb->get_results("select propertyId,propKey from `beds_properties`");

    ?>
    <div>
        <p>Set PropKey`s to the properties, you can get this data from Beds24>Properties>Access</p>
        <form action="" method="post" class="prop-form">
            <?php
            foreach ($propCount as $item) {
                ?>
                <label class="admin-label-prop">PropKey for <?= $item->propertyId; ?><input type="text"
                                                                                            value="<?= $item->propKey; ?>"
                                                                                            name="prop<?= $item->propertyId; ?>"></label>
                <?php
            }
            ?>
            <button class="admin-prop-btn" name="update-props">Update</button>
        </form>
    </div>
</div>
<script src='https://stugor2.hemsida.eu//wp-content/plugins/beds24-booking/assets/js/admin-page.js?ver=6.1.1'
        id='my-script-js'></script>