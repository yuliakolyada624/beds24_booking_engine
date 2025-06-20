<?php


namespace beds_booking;


class Action_beds_booking

{

    /**
     * Get refresh token string
     * @return mixed
     */

    private function getRefToken()

    {

        return BEDS_REF_TOKEN;

    }


    /**
     * Refresh the Token and set it to db
     * call - $act->refreshToken();
     *
     * @return bool|int|\mysqli_result|resource|null
     */

    public function refreshToken()

    {

        if ($this->canCall()):

            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/authentication/token');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Refreshtoken: ' . $this->getRefToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $result = json_decode($result, true);

            $token = $result['token'];

            $exp = $result['expiresIn'];

            global $wpdb;

            $table = $wpdb->get_results("SELECT * FROM `beds_tokens`");

            if (empty($table)) {

                $res = $wpdb->query("insert into `beds_tokens` (`token`, `expin`) values ('$token',$exp)");

            } else {

                $res = $wpdb->query("update `beds_tokens` set token='$token', expin='$exp' where id=1");

            }

            $this->APIIterator();

            return $res;

        else:

            return false;

        endif;

    }


    /**
     * Create table with token
     * call - $act->createTokenTable();
     */

    public function createTokenTable()

    {

        global $wpdb;


        $sql = "CREATE TABLE IF NOT EXISTS `beds_tokens` (

  `id` int(11) NOT NULL auto_increment,   

  `token`  varchar(250) NOT NULL,

  `expin` int(7) NOT NULL,

  `date_token` timestamp NOT NULL,

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    public function createCalendarTable()

    {

        global $wpdb;

        $sql = "CREATE TABLE IF NOT EXISTS `beds_calendar` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10) NOT NULL,

    `propertyId` int(10) NOT NULL,

    `date` date NOT NULL,

    `avaliable` int(1) NOT NULL,

    `isBooked` int(1) NOT NULL,

    `minStay` int(3) NOT NULL,

    `maxStay` int(3) NOT NULL,

    `price1` float NOT NULL,

    `price2` float  NOT NULL,

    

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    /**
     * Create main table with data from each property and rooms
     * call - $act->createMainTable();
     */

    public function createMainTable()

    {

        global $wpdb;

        $sql = "CREATE TABLE IF NOT EXISTS `beds_properties` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10),

    `propertyId` int(10) NOT NULL,

    `nameProp` varchar(250),

    `nameRoom` varchar(250),

    `tel` varchar(15),

    `mail` varchar(60),

    `currency` varchar(10),

    `bookType` varchar(15),

    `site` varchar(250),

    `contactPersName` varchar(50),

    `contactPersLName` varchar(50),

    `fax` varchar(15),

    `address`varchar(150),

    `city` varchar(25),

    `state` varchar(50),

    `country` varchar(10),

    `postcode` varchar(10),

    `latitude` varchar(15),

    `longitude` varchar(15),

    `checkInStart` varchar(15),

    `checkInEnd` varchar(15),

    `checkOutEnd` varchar(15),

    `propertyDescription1en` varchar(250),

    `propertyDescription2en` varchar(250),

    `propertyDescriptionBookingPage1en` text,

    `propertyDescriptionBookingPage2en` text,

    `propertyDescription1sv` varchar(250),

    `propertyDescription2sv` varchar(250),

    `propertyDescriptionBookingPage1sv` text,

    `propertyDescriptionBookingPage2sv` text,

    `minPrice` int(10),

    `maxPeople` int(5),

    `maxAdult` int(5),

    `maxChildren` int(5),

    `images` text,

    `propKey` varchar(250),



   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    /**
     * Method get`s the Token and refresh it if it`s need
     * return token string or verb Error
     * @return string|null
     */

    public function getToken()

    {

        global $wpdb;


        $isToken = $wpdb->get_results("SELECT * FROM `beds_tokens`");

        if (empty($isToken)) {

            if ($this->refreshToken()) {

                return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

            } else {

                return 'error';

            }

        } else {

            $dateNow = date('Y-m-d H:i:s');

            $tokenDate = $wpdb->get_var("select `date_token` from `beds_tokens` where id=1");

            $dateEndToken = date('Y-m-d H:i:s', strtotime("+ 22 hours", strtotime($tokenDate)));

            if ((strtotime($dateEndToken) - strtotime($dateNow)) < 0) {

                if ($this->refreshToken()) {

                    return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

                } else {

                    return 'error';

                }

            } else {

                return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

            }

        }

    }


    /**
     * Method get`s all main property from start date to end
     * using API V2, url: https://beds24.com/api/v2/inventory/rooms/calendar
     * return associative array
     * @return mixed
     */

    public function getAllProp($startDate, $endDate)

    {

        if ($this->canCall()):

            $ch = curl_init();

            if (($timestamp = strtotime($startDate)) === false) {

                return 'error';

            } else {

                $startDate = date('Y-m-d', $timestamp);

            }

            if (($timestamp = strtotime($endDate)) === false) {

                return 'error';

            } else {

                $endDate = date('Y-m-d', $timestamp);

            }

            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/inventory/rooms/calendar?startDate=' . $startDate . '&endDate=' . $endDate . '&includeNumAvail=true&includeMinStay=true&includeMaxStay=true&includeMultiplier=true&includeOverride=true&includePrices=true&includeLinkedPrices=true');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        else:

            return false;

        endif;

    }


    /**
     * Method get`s all data for property by IDs
     * @param $propIDs array it`s array with values only like array($val1,$val2...)
     * @return mixed
     */

    public function getPropDataByID($propIDs)

    {

        if ($this->canCall()):
            $ch = curl_init();


            $propURL = '';

            foreach ($propIDs as $propID) {

                $propURL .= 'id=' . $propID . '&';

            }

            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/properties?' . $propURL . 'includeLanguages=all&includeTexts=all&includePictures=true&includeOffers=true&includePriceRules=true&includeAllRooms=true&includeUnitDetails=true');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        else:
            return false;

        endif;

    }


    public function getNumAdult($roomID)

    {

        global $wpdb;


        $res = $wpdb->get_var('select maxPeople from `beds_properties` where roomId=' . $roomID);

        if ($res) {

            return $res;

        } else {

            return false;

        }

    }


    public function getIsAvailable($roomID, $start, $end)
    {

        if ($this->canCall()) {

            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/inventory/rooms/availability?roomId=' . $roomID . '&startDate=' . $start . '&endDate=' . $end);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                echo 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        } else {

            return false;

        }


    }


    private function createProd()
    {
        //
    }

    public function updateCalendar($startDate = null, $endDate = null)

    {

        if (is_null($startDate) and is_null($endDate) or (empty($startDate) or empty($endDate))) {

            $startDate = date('Y-m-d');

            $endDate = date('Y-m-d', strtotime('+ 1 year'));

        }


        global $wpdb;

        $data = $this->getAllProp($startDate, $endDate);
        $success = 0;
//        var_dump($data);

        if ($data['success']) {

            $propCount = $data['count'];



            foreach ($data['data'] as $datum) {

                $roomID = $datum['roomId'];

                $propertyID = $datum['propertyId'];

                $arrDates = array();

                $res = $wpdb->get_var("select `id` from `beds_properties` where propertyId='$propertyID'");

                if (empty($res) and ($propertyID != 0 or !empty($propertyID))) {

                    $wpdb->query("INSERT INTO `beds_properties` (propertyId) value ('$propertyID')");

                }

                foreach ($datum['calendar'] as $calendar) {

                    if ($calendar['from'] == $calendar['to']) {

                        $date = $calendar['from'];

                        $aval = $calendar['numAvail'];

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'] ?? 0;

                        $price2 = $calendar['price2'] ?? $price1;

                        $isBook = $calendar['override'];

                        if ($isBook == 'none') {

                            $isBook = 1;

                        } else {

                            $isBook = 0;

                        }


                        $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where `date`='$date' and `roomId`='$roomID'";

                        if ($wpdb->query($sql)) {

                            $success++;

                        }

                    } else {
                        $origin = date_create($calendar['from']);

                        $target = date_create($calendar['to']);

                        $interval = date_diff($origin, $target);

                        $dateCount = $interval->format('%a');

                        $dates = array($calendar['from']);

                        $aval = $calendar['numAvail'];

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'] ?? 0;

                        $price2 = $calendar['price2'] ?? $price1;

                        $isBook = $calendar['override'];

                        if ($isBook == 'none') {

                            $isBook = 1;

                        } else {

                            $isBook = 0;

                        }

                        $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where date='$dates[0]' and roomId='$roomID'";


                        if ($wpdb->query($sql)) {

                            $success++;

                        }

                        for ($i = 0; $i < (int)$dateCount; $i++) {

                            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                            array_push($dates, $date);

                            $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where date='$date' and roomId='$roomID'";

                            if ($wpdb->query($sql)) {

                                $success++;

                            }

                        }

                    }

                }

            }

            return $success;

        } else {

            return false;

        }

    }

    public function cleareDates($startDate = null, $endDate = null)
    {
        global $wpdb;

        $wpdb->query("delete FROM `beds_calendar` WHERE date between '$startDate' AND '$endDate'");

    }

    /**
     * Method set data to tables, if date not set - start\end date will be from $now to 1 year ahead
     * @param null $startDate
     * @param null $endDate
     * @return bool
     */

    public function setDataInCalendar($startDate = null, $endDate = null)

    {

        if (is_null($startDate) and is_null($endDate)) {

            $startDate = date('Y-m-d');

            $endDate = date('Y-m-d', strtotime('+ 1 year'));

            $def = true;

        }

        global $wpdb;

        $firstData = $this->getAllProp($startDate, $endDate);


        if ($firstData['success']) {

            $propCount = $firstData['count'];

            $successIter = 0;

            foreach ($firstData['data'] as $datum) {

                $roomID = $datum['roomId'];

                $propertyID = $datum['propertyId'];

                $arrDates = array();

                $res = $wpdb->get_var("select `id` from `beds_properties` where propertyId='$propertyID'");

                if (empty($res)) {

                    $wpdb->query("INSERT INTO `beds_properties` (propertyId) value ('$propertyID')");

                }

                foreach ($datum['calendar'] as $calendar) {

                    if ($calendar['from'] == $calendar['to']) {

                        $date = $calendar['from'];

                        $aval = $calendar['numAvail'];

                        $bookable = $calendar['override'];

                        if ($bookable == 'none') {

                            $bookable = 1; // can book

                        } else {

                            $bookable = 0; //not book in this date

                        }

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $date . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                    } else {

                        $origin = date_create($calendar['from']);

                        $target = date_create($calendar['to']);

                        $interval = date_diff($origin, $target);

                        $dateCount = $interval->format('%a');

                        $dates = array($calendar['from']);

                        $aval = $calendar['numAvail'];

                        $bookable = $calendar['override'];

                        if ($bookable == 'none') {

                            $bookable = 1; // can book

                        } else {

                            $bookable = 0; //not book in this date

                        }

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $dates[0] . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                        for ($i = 0; $i < (int)$dateCount; $i++) {

                            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                            array_push($dates, $date);

                            array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $date . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                        }

                    }

                }

                $res = $wpdb->get_var('SELECT count(`id`) from `beds_calendar`');


                if ((int)$res == 0) {

                    $sql = 'insert into `beds_calendar` (roomId,propertyId,date,avaliable,isBooked,minStay,maxStay,price1,price2) values ' . implode(',', $arrDates);

                    if ($wpdb->query($sql)) {

                        $successIter++;

                    }

                } else {


                    $data = explode(',', $arrDates[0]);


                    $newRes = $wpdb->get_var('SELECT id from `beds_calendar` where `date`="' . str_replace(array('\'', '('), '', $data[2]) . '" and roomId="' . str_replace(array('\'', '('), '', $data[0]) . '" ');

                    if (!$newRes) {

                        $sql = 'insert into `beds_calendar` (roomId,propertyId,date,avaliable,isBooked,minStay,maxStay,price1,price2) values ' . implode(',', $arrDates);

                        $wpdb->query($sql);

                    }


                }

            }

            if ($successIter == 0) {

                return false;

            } else {

                // log to file iter

                return true;

            }

        } else {

            return false;

        }

    }


    public function createPriceRulesTable()

    {

        global $wpdb;


        $sql = "CREATE TABLE IF NOT EXISTS `beds_prices_rules` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10) NOT NULL,

    `idPrice` int(5) NOT NULL,

    `minimumStay` int(3) NOT NULL,

    `maximumStay` int(5) NOT NULL,

    `pricePercent` float NOT NULL,

    

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    public function setDataInPropTable()

    {

        global $wpdb;

        $props = $wpdb->get_results("select propertyId from `beds_properties`");

        $propArr = array();

        foreach ($props as $prop) {

            array_push($propArr, $prop->propertyId);

        }

        $res = $this->getPropDataByID($propArr);


        if ($res['success']) {

            foreach ($res['data'] as $datum) {

                $propID = $datum['id'];

                $name = $datum['name'];

                $currency = $datum['currency'];

                $address = $datum['address'];

                $city = $datum['city'];

                $state = $datum['state'];

                $country = $datum['country'];

                $postcode = $datum['postcode'];

                $latitude = $datum['latitude'];

                $longitude = $datum['longitude'];

                $checkInStart = $datum['checkInStart'];

                $checkInEnd = $datum['checkInEnd'];

                $checkOutEnd = $datum['checkOutEnd'];

                foreach ($datum['texts'] as $text) {

                    if ($text['language'] == 'en') {

                        $propertyDescription1en = $text['propertyDescription1'];

                        $propertyDescription2en = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1en = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2en = $text['propertyDescriptionBookingPage2'];

                    }

                    if ($text['language'] == 'sv') {

                        $propertyDescription1sv = $text['propertyDescription1'];

                        $propertyDescription2sv = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1sv = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2sv = $text['propertyDescriptionBookingPage2'];

                    }

                }

                foreach ($datum['roomTypes'] as $roomType) {

                    $roomID = $roomType['id'];

                    $nameRoom = $roomType['name'];

                    $minPrice = $roomType['minPrice'];

                    $maxPeople = $roomType['maxPeople'];

                    $maxAdult = $roomType['maxAdult'];

                    $maxChildren = $roomType['maxChildren'];

                    $countPrices = count($roomType['priceRules']);

                    foreach ($roomType['priceRules'] as $priceRule) {

                        $idPrice = $priceRule['id'];

                        $minimumStay = $priceRule['minimumStay'];

                        $maximumStay = $priceRule['maximumStay'];

                        $pricePercent = $priceRule['priceLinking']['offsetMultiplier'];


//                        $sql = "update `beds_prices_rules` set idPrice='$idPrice', minimumStay='$minimumStay',

//                        maximumStay='$maximumStay', pricePercent='$pricePercent' where roomId='$roomID'";

                        $sql = "insert into `beds_prices_rules` (idPrice,minimumStay,maximumStay,pricePercent,roomId) values 

                            ('$idPrice','$minimumStay','$maximumStay','$pricePercent','$roomID')";

                        $wpdb->query($sql);

                    }

                }


                $sql = "insert into `beds_properties` (roomId,nameProp,nameRoom,currency,address,city,state,country,postcode,

latitude,longitude,checkInStart,checkInEnd,checkOutEnd,propertyDescription1en,propertyDescription1sv,

propertyDescription2en,propertyDescription2sv,propertyDescriptionBookingPage1en,propertyDescriptionBookingPage1sv,

propertyDescriptionBookingPage2en,propertyDescriptionBookingPage2sv,minPrice,maxPeople,maxAdult,maxChildren,propertyId) values 

('$roomID','$name','$nameRoom','$currency','$address','$city','$state','$country','$postcode','$latitude','$longitude','$checkInStart',

'$checkInEnd','$checkOutEnd','$propertyDescription1en','$propertyDescription1sv','$propertyDescription2en','$propertyDescription2sv',

'$propertyDescriptionBookingPage1en','$propertyDescriptionBookingPage1sv','$propertyDescriptionBookingPage2en',

'$propertyDescriptionBookingPage2sv','$minPrice','$maxPeople','$maxAdult','$maxChildren','$propID')";

                if ($wpdb->query($sql)) {

                    $photos = array();

                    $photo = $this->getPhotos($propID);

                    if (!empty($photo)) {

                        foreach ($photo->hosted as $item) {

                            if (!empty($item->url)) {

                                array_push($photos, $item->url);

                            }

                        }

                        foreach ($photo->external as $item) {

                            if (!empty($item->url)) {

                                array_push($photos, $item->url);

                            }

                        }

                    }

                    if (!empty($photos)) {

                        $img = implode(',', $photos);

                        $wpdb->query("update `beds_properties` set images='$img' where propertyId='$propID'");

                    }

                }

            }

        } else {

            return false;

        }


    }


    public function updatePropTable()

    {

        global $wpdb;

        // need to upd beds prop firstly
        $startDate = date('Y-m-d',time());
        $endDate = date('Y-m-d',strtotime('+1 day'));

        $data = $this->getAllProp($startDate, $endDate);
        if ($data['success']){
            foreach ($data['data'] as $datum){
                $propertyID = $datum['propertyId'];
                $res = $wpdb->get_var("select `id` from `beds_properties` where propertyId='$propertyID'");

                if (empty($res) and ($propertyID != 0 or !empty($propertyID))) {

                    $wpdb->query("INSERT INTO `beds_properties` (propertyId) value ('$propertyID')");

                }
            }
        }

        $props = $wpdb->get_results("select propertyId from `beds_properties`");

        $propArr = array();

        foreach ($props as $prop) {

            array_push($propArr, $prop->propertyId);

        }
        $res = $this->getPropDataByID($propArr);
        if ($res['success']) {

            $success = 0;

            foreach ($res['data'] as $datum) {

                $propID = $datum['id'];

                $name = $datum['name'];

                $currency = $datum['currency'];

                $address = $datum['address'];

                $city = $datum['city'];

                $state = $datum['state'];

                $country = $datum['country'];

                $postcode = $datum['postcode'];

                $latitude = $datum['latitude'];

                $longitude = $datum['longitude'];

                $checkInStart = $datum['checkInStart'];

                $checkInEnd = $datum['checkInEnd'];

                $checkOutEnd = $datum['checkOutEnd'];

                foreach ($datum['texts'] as $text) {

                    if ($text['language'] == 'en') {

                        $propertyDescription1en = $text['propertyDescription1'];

                        $propertyDescription2en = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1en = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2en = $text['propertyDescriptionBookingPage2'];

                    }

                    if ($text['language'] == 'sv') {

                        $propertyDescription1sv = $text['propertyDescription1'];

                        $propertyDescription2sv = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1sv = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2sv = $text['propertyDescriptionBookingPage2'];

                    }

                }

                foreach ($datum['roomTypes'] as $roomType) {

                    $roomID = $roomType['id'];

                    $nameRoom = $roomType['name'];

                    $minPrice = $roomType['minPrice'];

                    $maxPeople = $roomType['maxPeople'];

                    $maxAdult = $roomType['maxAdult'];

                    $maxChildren = $roomType['maxChildren'] ?? 0;

                    $countPrices = count($roomType['priceRules']);

                    foreach ($roomType['priceRules'] as $priceRule) {

                        $idPrice = $priceRule['id'];

                        $minimumStay = $priceRule['minimumStay'];

                        $maximumStay = $priceRule['maximumStay'];

                        $pricePercent = $priceRule['priceLinking']['offsetMultiplier'];


                        $sql = "update `beds_prices_rules` set idPrice='$idPrice', minimumStay='$minimumStay',

                        maximumStay='$maximumStay', pricePercent='$pricePercent' where roomId='$roomID'";


                        $wpdb->query($sql);

                    }

                }


                $sql = "update `beds_properties` set roomId='$roomID', nameProp='$name', nameRoom='$nameRoom',

                currency='$currency', address='$address', city='$city', state='$state', country='$country',

                postcode='$postcode',latitude='$latitude', longitude='$longitude', checkInStart='$checkInStart',

                checkInEnd='$checkInEnd',checkOutEnd='$checkOutEnd', propertyDescription1en='$propertyDescription1en',

                propertyDescription1sv='$propertyDescription1sv', propertyDescription2en='$propertyDescription2en',

                propertyDescription2sv='$propertyDescription2sv', propertyDescriptionBookingPage1en='$propertyDescriptionBookingPage1en',

                propertyDescriptionBookingPage1sv='$propertyDescriptionBookingPage1sv', propertyDescriptionBookingPage2en='$propertyDescriptionBookingPage2en',

                propertyDescriptionBookingPage2sv='$propertyDescriptionBookingPage2sv', minPrice='$minPrice',

                maxPeople='$maxPeople', maxAdult='$maxAdult', maxChildren='$maxChildren' where propertyId='$propID'";

                if ($wpdb->query($sql)) {

                    $success++;

                }

                global $wpdb;
                $table = $wpdb->prefix.'postmeta';
                $r = $wpdb->get_var("select post_id from $table where meta_key='_product_beds_id' AND meta_value=".$roomID);

                if (!isset($r)){
                    $post = array(
                        'post_author' => 1,
                        'post_content' => $propertyDescriptionBookingPage1en,
                        'post_status' => "publish",
                        'post_title' => $nameRoom,
                        'post_type' => "product"
                    );
                    $post_id = wp_insert_post($post);
                    $product = wc_get_product( $post_id );
                    update_post_meta( $post_id, '_visibility', 'visible' );
                    update_post_meta( $post_id, '_downloadable', 'no');
                    update_post_meta( $post_id, '_virtual', 'no');
                    update_post_meta( $post_id, '_product_beds_id', $roomID );
                    update_post_meta( $post_id, '_product_peoples',  $maxAdult);
                    update_post_meta( $post_id, '_regular_price', $minPrice);
                    update_post_meta( $post_id, '_price', $minPrice);
                    update_post_meta($post_id, '_sale_price', $minPrice -1);
                    $product->set_regular_price( $minPrice );
                    wp_set_object_terms($post_id, "simple", 'product_type');
                    $success++;
                }
//                if (){

                $photos = array();

                $photo = $this->getPhotos($propID);

                if (!empty($photo)) {

                    foreach ($photo->hosted as $item) {

                        if (!empty($item->url)) {

                            array_push($photos, $item->url);

                        }

                    }

                    foreach ($photo->external as $item) {

                        if (!empty($item->url)) {

                            array_push($photos, $item->url);

                        }

                    }

                }

//                var_dump($photos); exit();


                if (!empty($photos)) {

                    $img = implode(',', $photos);

                    if ($wpdb->query("update `beds_properties` set images='$img' where propertyId='$propID'")) {

                        $success++;

                    }

                }

//                }

            }

            return $success;

        } else {

            return false;

        }

    }


    public function getRoomPriceByDays($days, $start, $end, $postID) {

        global $wpdb;


        $days = intval($days);


        $table = $wpdb->prefix . 'postmeta';

        $products = $wpdb->get_results("SELECT meta_value FROM $table WHERE post_id = '$postID' AND meta_key = '_product_beds_id'");

        $room_id = $products[0]->meta_value;


        if ($days < 7) {

            $sql = "select price1 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        } else {

            $sql = "select price2 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        }

        

        $prices = $wpdb->get_results($sql, ARRAY_N);

        

        $total = 0;

        for ($i = 0; $i < $days; $i++) {

            $total += $prices[$i]['0'];

        }   

        
        $total = ceil($total); // Round up the total to the nearest whole number

        return $total;

    }

    public function getRoomPriceByDaysPriceList($weekname, $days, $start, $end, $postID) {

        global $wpdb;


        $days = intval($days);


        $table = $wpdb->prefix . 'postmeta';

        $products = $wpdb->get_results("SELECT meta_value FROM $table WHERE post_id = '$postID' AND meta_key = '_product_beds_id'");

        $room_id = $products[0]->meta_value;


        if ($days < 7) {

            $sql = "select price1 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        } else {

            $sql = "select price2 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        }

        

        $prices = $wpdb->get_results($sql, ARRAY_N);

        

        $total = 0;

        for ($i = 0; $i < $days; $i++) {

            $total += $prices[$i]['0'];

        }   

        if ( $room_id == '386684' && $weekname == 'Vecka 13' ) {
            echo 'sql = '.$sql.'<br>';
            // echo 'total = '.$total.'<br>';
        }
        

        $total = ceil($total); // Round up the total to the nearest whole number

        return $total;

    }


    public function getPhotos($propID)

    {

        if ($this->canCall()):

            global $wpdb;

            $propKey = $wpdb->get_var("select propKey from `beds_properties` where propertyId=" . $propID);

            $post = [

                'authentication' => [

                    'apiKey' => BEDS_API_KEY,

                    'propKey' => $propKey,

                ],

                'images' => 'true',

            ];


            $ch = curl_init('https://api.beds24.com/json/getPropertyContent');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));


            $response = curl_exec($ch);

            curl_close($ch);

            $res = json_decode($response);

            $this->APIIterator();

            return $res->getPropertyContent[0]->images ?? 'error';

        else:

            return false;

        endif;


    }


    private function canCall()

    {
        if ($this->checkAPICalls() < 60) {

            return true;

        } else {

            return false;

        }

    }


    private function checkAPICalls()

    {
        $this->clearAPIIter();
        $file = "apiiter.txt";

        $handle = file_get_contents($file);

        $data = explode("\n", $handle);
//var_dump($data);
        return count($data);

    }

    private function APIIterator()

    {

        $file = "apiiter.txt";

        $handle = fopen($file, "a");

        $time = time();

        $str = "$time\n";

        fwrite($handle, $str);

        fclose($handle);

    }

    public function clearAPIIter()

    {

        $file = "apiiter.txt";

        $handle = file_get_contents($file);

        $data = explode("\n", $handle);

        $fiveMinBack = strtotime('-300 second', time());

        foreach ($data as $item => $val) {

            if ($val < $fiveMinBack) {

                unset($data[$item]);

            }

        }

        $data = implode("\n", $data);

        $handle = fopen($file, "w");

        $data = $data . "\n";

        fwrite($handle, $data);

        fclose($handle);

    }


    public function updateAvailByRoom($idRoom, $notAvailDateArr)

    {

        global $wpdb;

        foreach ($notAvailDateArr as $date) {

            $sql = "update `beds_calendar` set avaliable = 0 where `date`='$date' and roomId='$idRoom'";

            $wpdb->query($sql);

        }

    }
    public function setPartialBookingOnAPI($orderID){
        $re = '';

        $fin = array();

        $order = wc_get_order($orderID);

        foreach ($order->get_items() as $item_id => $item) {

            $from = $item->get_meta('booked_from');

            $to = $item->get_meta('booked_to');

//            $persons = $item->get_meta('persons');
            $persons_A = $item->get_meta('persons_adult');
            $persons_C = $item->get_meta('persons_child');
            $prodID = $item->get_data()['product_id'];

            $roomId = get_post_meta($prodID, '_product_beds_id', true);

            $data = $order->get_data();

            $billing_first_name = $data['billing']['first_name'];

            $billing_last_name = $data['billing']['last_name'];

            $billing_address_1 = $data['billing']['address_1'];

            $billing_city = $data['billing']['city'];

            $billing_state = $data['billing']['state'];

            $billing_postcode = $data['billing']['postcode'];

            $billing_country = $data['billing']['country'];

            $mail = $data['billing']['email'];

            $tel = $data['billing']['phone'];

            $sub_ord = get_post_meta($order->get_id(),'_awcdp_deposits_payment_schedule',true);
            if ($sub_ord != ""){
                if (get_post_meta($order->get_id(),'_awcdp_deposits_second_payment_paid',true) == 'no'){
                    $pay_metod  = wc_get_order($sub_ord["deposit"]['id'])->get_payment_method_title();

                    if (wc_get_order($sub_ord['deposit']['id'])->get_date_paid()){
                        $pay_day = wc_get_order($sub_ord['deposit']['id'])->get_date_paid()->date('Y-m-d');
                    } else {
                        $pay_day = 'none';
                    }
                } else {
                    $pay_metod = wc_get_order($sub_ord['unlimited']['id'])->get_payment_method_title();
                    $pay_day = wc_get_order($sub_ord['unlimited']['id'])->get_date_paid()->date('Y-m-d');

                }
            } else {
                $pay_metod = $order->get_payment_method_title();
                $pay_day = $order->get_date_paid()->date('Y-m-d');

            }
//            $total = $item->get_total();
            $total_dep  = get_post_meta($order->ID,'_awcdp_deposits_payment_schedule',true);
            $total_dep = $total_dep['deposit']['total'];
            $quantity = $item->get_quantity();
            $prodName = wc_get_product($prodID)->get_name();

//            $tot_fees_for_total = 0;
//            foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){
//                $fee_total = $item_fee->get_total();
//                $tot_fees_for_total = $tot_fees_for_total + $fee_total;
//            }
//            foreach ($order->get_items() as $item_option){
//
//                if ($item_option->get_meta('Hund')){
//                    $tot_fees_for_total = $tot_fees_for_total + 1000;
//                }
//                if ($item_option->get_meta('Avbokningskydd')){
//                    $tot_fees_for_total = $tot_fees_for_total + 450;
//                }
//            }
            $datetime1 = date_create($from);
            $datetime2 = date_create($to);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->days;
            $total = $this->getRoomPriceByDays($days,$from, $to, $prodID);


//            $total = (float)$item->get_total()+((float)$item->get_taxes()['total'][1]);
//            $total = $total - $tot_fees_for_total;
            if ($pay_day == 'none'){
                $invoiceArr = [
                    [
                        "type" => "charge",
                        "qty"=>$quantity,
                        "amount"=>$total,
                        "lineTotal"=>$total,
                        "description"=>'['.sanitize_title($prodName).'] ['.$from.'] - ['.$to.']'
                    ]
                ];
            } else{
                $invoiceArr = [
                    [
                        "type" => "charge",
                        "qty"=>$quantity,
                        "amount"=>$total,
                        "lineTotal"=>$total,
                        "description"=>'['.sanitize_title($prodName).'] ['.$from.'] - ['.$to.']'
                    ],
                    [
                        "type" => "payment",
                        "qty"=>$quantity,
                        "amount"=>$total_dep,
                        "lineTotal"=> $total_dep,
                        "description"=>'['.$pay_metod.' payment '.$pay_day.']',
                    ]
                ];
            }

            foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){

                $fee_name = $item_fee->get_name();
                $fee_total = $item_fee->get_total();
                $fee_quantity = $item_fee->get_quantity();

                array_push($invoiceArr,  [
                    "type" => "charge",
                    "qty"=>$fee_quantity,
                    "amount"=>$fee_total,
                    "lineTotal"=>$fee_total,
                    "description"=>$fee_name
                ]);

//                array_push($invoiceArr, [
//                    "type" => "payment",
//                    "qty"=>$fee_quantity,
//                    "amount"=>$fee_total,
//                    "lineTotal"=> '-'.$fee_total,
//                    "description"=>$fee_name
//                ]);
            }

            $flagText = '';
            $flagColor = '';

            foreach ($order->get_items() as $item_option){
                /*if ($item_option->get_meta('Avresestädning')){
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=>0,
                        "lineTotal"=>0,
                        "description"=>'Avresestädning'
                    ]);
                }*/
                $cleaning = trim(strip_tags($item_option->get_meta(__( 'Avresestädning'))));
                if ($cleaning){
                    $is_cleaning_with_rut = $item_option->get_meta(__( 'Avresestädning with RUT'));
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=> $is_cleaning_with_rut ? 0 : (float)$cleaning,
                        "lineTotal"=> $is_cleaning_with_rut ? 0 : (float)$cleaning,
                        "description"=> $is_cleaning_with_rut ? 'Avresestädning with RUT' : 'Avresestädning'
                    ]);
                    $flagColor = 'FFFF00';
                    $flagText = $is_cleaning_with_rut ? 'Departure cleaning(RUT)' : 'Departure cleaning';
                }
                if ($item_option->get_meta('Hund')){
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=>1000,
                        "lineTotal"=>1000,
                        "description"=>'Hunddeposition'
                    ]);
                }
                if ($item_option->get_meta('Avbokningskydd')){
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=>450,
                        "lineTotal"=>450,
                        "description"=>'Avbokningskydd'
                    ]);
                }
            }


            $post_data = [

                'roomId' => $roomId,

                "status" => "confirmed",

                "arrival" => $from,

                "departure" => $to,

                "numAdult" => $persons_A,

                "numChild" => $persons_C,

                "firstName" => $billing_first_name,

                "lastName" => $billing_last_name,

                "email" => $mail,

                "phone" => $tel,

                "address" => $billing_address_1,

                "city" => $billing_city,

                "state" => $billing_state,

                "postcode" => $billing_postcode,

                "country2" => $billing_country,

                "invoiceItems" => $invoiceArr,

                "flagColor" => $flagColor,

                "flagText" => $flagText

            ];

            //file_put_contents(ABSPATH.'/wp-content/uploads/beds24-post-log.txt', json_encode($post_data), FILE_APPEND);


            $post = [
                $post_data
            ];


            if (empty($re)){
                $re = $this->sendBooking($post);
            } else {
                $extra = json_decode($this->sendBooking($post),true);
                $re_o = json_decode($re, true);
                $re_o[] = $extra;
                $re = json_encode($re_o);
            }
//            $re = $this->sendBooking($post);

            $this->setBookingInDB($from, $to, $roomId);

        }

        return $re;
    }

    private function filterName($name){

        if (strpos($name,'u00e5')){
            $name = str_replace('u00e5','å',$name);
        }
        if (strpos($name,'u00c5')){
            $name = str_replace('u00c5','Å',$name);
        }
        if (strpos($name,'u00c4')){
            $name = str_replace('u00c4','Ä',$name);
        }
        if (strpos($name,'u00e4')){
            $name = str_replace('u00e4','ä',$name);
        }
        if (strpos($name,'u00d6')){
            $name = str_replace('u00d6','Ö',$name);
        }
        if (strpos($name,'u00f6')){
            $name = str_replace('u00f6','ö',$name);
        }

        return $name;
    }

    public function confirmPartial($order_id){
        $result = '';
        $m = get_post_meta($order_id,'request_api_res',true);
        $order = wc_get_order($order_id);
        $req_api = json_decode($m,true);
        $arinvitems = array();
        $iter=0;
        foreach ($req_api as $item) {
            if ($iter == 0){
                $name = $item['new']["invoiceItems"][0]['description'];
                $name = explode(']',$name);
                $name = substr($name[0], 1);
                $name = $this->filterName($name);

                $idB = $item["new"]["id"];
                $arinvitems[$name] = $idB;
//                array_push($arinvitems,array($name=>$idB));
            } else{
//                var_dump($item[$iter-1]['new']["invoiceItems"][0]['description']);

//                var_dump($item[$iter-1]["new"]["id"]);
                $name = $item[$iter-1]['new']["invoiceItems"][0]['description'];
                $name = explode(']',$name);
                $name = substr($name[0], 1);
                $name = $this->filterName($name);

                $idB = $item[$iter-1]["new"]["id"];
                $arinvitems[$name] = $idB;
//                array_push($arinvitems,array($name=>$idB));
            }
            $iter++;
        }
//        var_dump($arinvitems); die();
        foreach ($arinvitems as $key => $arinvitem) {
            var_dump($arinvitem);
            var_dump($key);
//            die();
            foreach ($order->get_items() as $item_id => $item){
//                var_dump(sanitize_title($item->get_name())); die();
                if (sanitize_title($item->get_name()) == $key){
                    $from = $item->get_meta('booked_from');
                    $to = $item->get_meta('booked_to');
                    $prodID = $item->get_data()['product_id'];
                    $sub_ord  = get_post_meta($order->get_id(),'_awcdp_deposits_payment_schedule',true);
//                    var_dump($sub_ord);
                    $sec_dep = $sub_ord['unlimited']['total'];
                    $quantity = $item->get_quantity();
//                    echo $arinvitem;
                    if ($sub_ord != ""){
                        if (get_post_meta($order->get_id(),'_awcdp_deposits_second_payment_paid',true) == 'no'){
                            $pay_metod  = wc_get_order($sub_ord["deposit"]['id'])->get_payment_method_title();
                            $pay_day = wc_get_order($sub_ord['deposit']['id'])->get_date_paid()->date('Y-m-d');

                        } else {
                            $pay_metod = wc_get_order($sub_ord['unlimited']['id'])->get_payment_method_title();
                            $pay_day = wc_get_order($sub_ord['unlimited']['id'])->get_date_paid()->date('Y-m-d');

                        }
                    } else {
                        $pay_metod = $order->get_payment_method_title();
                        $pay_day = $order->get_date_paid()->date('Y-m-d');

                    }
                    $invoiceArr = [
                        [
                            "type" => "payment",
                            "qty"=>$quantity,
                            "amount"=>$sec_dep,
                            "lineTotal"=> $sec_dep,
                            "description"=>'['.$pay_metod.' payment '.$pay_day.']'
                        ]
                    ];
                    $post = [

                        [
                            "id" => $arinvitem,
                            "invoiceItems" => $invoiceArr
                        ]
                    ];
//                    var_dump($post);
//                    die();
//                    $re = $this->sendBooking($post);
                    if (empty($result)){
                        $result = $this->sendBooking($post);
                    } else {
                        $extra = json_decode($this->sendBooking($post),true);
                        $re_o = json_decode($result, true);
                        $re_o[] = $extra;
                        $result = json_encode($re_o);
                    }
//                    var_dump($result);
                }
            }
        }

        return $result;

    }


    public function setBookingOnAPI($orderID)

    {
        $re = '';

        $order = wc_get_order($orderID);

        foreach ($order->get_items() as $item_id => $item) {

            $from = $item->get_meta('booked_from');

            $to = $item->get_meta('booked_to');

            $persons_A = $item->get_meta('persons_adult');
            $persons_C = $item->get_meta('persons_child');

            $prodID = $item->get_data()['product_id'];

            $roomId = get_post_meta($prodID, '_product_beds_id', true);

            $data = $order->get_data();

            $sub_ord = get_post_meta($order->get_id(),'_awcdp_deposits_payment_schedule',true);

            if ($sub_ord != ''){
                if (get_post_meta($order->get_id(),'_awcdp_deposits_second_payment_paid',true) == 'no'){
                    $pay_metod  = wc_get_order($sub_ord["deposit"]['id'])->get_payment_method_title();
                    $pay_day = wc_get_order($sub_ord['deposit']['id'])->get_date_paid()->date('Y-m-d');
                } else {
                    $pay_metod = wc_get_order($sub_ord['unlimited']['id'])->get_payment_method_title();
                    $pay_day = wc_get_order($sub_ord['unlimited']['id'])->get_date_paid()->date('Y-m-d');
                }
            } else {
                $pay_metod = $order->get_payment_method_title();
                $is_pay = $order->get_date_paid();
                if ($is_pay){
                    $pay_day = $order->get_date_paid()->date('Y-m-d');
                } else {
                    $pay_day = date('Y-m-d');
                }
            }
            $billing_first_name = $data['billing']['first_name'];

            $billing_last_name = $data['billing']['last_name'];

            $billing_address_1 = $data['billing']['address_1'];

            $billing_city = $data['billing']['city'];

            $billing_state = $data['billing']['state'];

            $billing_postcode = $data['billing']['postcode'];

            $billing_country = $data['billing']['country'];

            $mail = $data['billing']['email'];

            $tel = $data['billing']['phone'];
            $quantity = $item->get_quantity();
            $prodName = wc_get_product($prodID)->get_name();
//            $tot_fees_for_total = 0;
//            foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){
//                $fee_total = $item_fee->get_total();
//                $tot_fees_for_total = $tot_fees_for_total + $fee_total;
//            }
//            foreach ($order->get_items() as $item_option){
//                if ($item_option->get_meta('Hund')){
//                    $tot_fees_for_total = $tot_fees_for_total + 1000;
//                }
//                if ($item_option->get_meta('Avbokningskydd')){
//                    $tot_fees_for_total = $tot_fees_for_total + 450;
//                }
//            }
//            $total = (float)$item->get_total()+((float)$item->get_taxes()['total'][1]);
//
//            $total = $total - $tot_fees_for_total;

            $datetime1 = date_create($from);
            $datetime2 = date_create($to);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->days;
            $total = $this->getRoomPriceByDays($days,$from, $to, $prodID);
            $payment_total = $total;

            $invoiceArr = [
                [
                    "type" => "charge",
                    "qty"=>$quantity,
                    "amount"=>$total,
                    "lineTotal"=>$total,
                    "description"=>'['.sanitize_title($prodName).'] ['.$from.'] - ['.$to.']'
                ]
            ];

            foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){

                $fee_name = $item_fee->get_name();
                $fee_total = $item_fee->get_total();
                $fee_quantity = $item_fee->get_quantity();
                array_push($invoiceArr,  [
                    "type" => "charge",
                    "qty"=>$fee_quantity,
                    "amount"=>$fee_total,
                    "lineTotal"=>$fee_total,
                    "description"=>$fee_name
                ]);

                $payment_total += $fee_total;

//                array_push($invoiceArr, [
//                    "type" => "payment",
//                    "qty"=>$fee_quantity,
//                    "amount"=>$fee_total,
//                    "lineTotal"=> '-'.$fee_total,
//                    "description"=>$fee_name
//                ]);
            }

            $flagText = '';
            $flagColor = '';
            foreach ($order->get_items() as $item_option){

                $cleaning = trim(strip_tags($item_option->get_meta(__( 'Avresestädning'))));
                if ($cleaning){
                    $is_cleaning_with_rut = $item_option->get_meta(__( 'Avresestädning with RUT'));

                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=> $is_cleaning_with_rut ? 0 : (float)$cleaning,
                        "lineTotal"=> $is_cleaning_with_rut ? 0 : (float)$cleaning,
                        "description"=> $is_cleaning_with_rut ? 'Avresestädning with RUT' : 'Avresestädning'
                    ]);
                    $flagColor = 'FFFF00';
                    $flagText = $is_cleaning_with_rut ? 'Departure cleaning(RUT)' : 'Departure cleaning';
                }
//                if ($item_option->get_meta('Avresestädning')){
//                    array_push($invoiceArr,  [
//                        "type" => "payment",
//                        "qty"=>1,
//                        "amount"=>0,
//                        "lineTotal"=>'-0',
//                        "description"=>'Avresestädning'
//                    ]);
//                }
                if ($item_option->get_meta('Hund')){
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=>1000,
                        "lineTotal"=>1000,
                        "description"=>'Hunddeposition'
                    ]);
                    $payment_total = $payment_total + 1000;
                }
//                if ($item_option->get_meta('Hund')){
//                    array_push($invoiceArr,  [
//                        "type" => "payment",
//                        "qty"=>1,
//                        "amount"=>1000,
//                        "lineTotal"=>'-1000',
//                        "description"=>'Hunddeposition'
//                    ]);
//                }
                if ($item_option->get_meta('Avbokningskydd')){
                    array_push($invoiceArr,  [
                        "type" => "charge",
                        "qty"=>1,
                        "amount"=>450,
                        "lineTotal"=>450,
                        "description"=>'Avbokningskydd'
                    ]);
                    $payment_total = $payment_total + 450;
                }
//                if ($item_option->get_meta('Avbokningskydd')){
//                    array_push($invoiceArr,  [
//                        "type" => "payment",
//                        "qty"=>1,
//                        "amount"=>450,
//                        "lineTotal"=>'-450',
//                        "description"=>'Avbokningskydd'
//                    ]);
//                }
            }

            array_push($invoiceArr,
                [
                    "type" => "payment",
                    "qty"=>$quantity,
                    "amount"=>$payment_total,
                    "lineTotal"=> '-'.$payment_total,
                    "description"=>'['.$pay_metod.' payment '.$pay_day.']',
                ]);

            $post = [

                [

                    'roomId' => $roomId,

                    "status" => "confirmed",

                    "arrival" => $from,

                    "departure" => $to,

                    "numAdult" => $persons_A,

                    "numChild" => $persons_C,

                    "firstName" => $billing_first_name,

                    "lastName" => $billing_last_name,

                    "email" => $mail,

                    "phone" => $tel,

                    "address" => $billing_address_1,

                    "city" => $billing_city,

                    "state" => $billing_state,

                    "postcode" => $billing_postcode,

                    "country2" => $billing_country,

                    "invoiceItems" => $invoiceArr,

                    "flagColor" => $flagColor,

                    "flagText" => $flagText

                ]

            ];

            //dd($post);
            //file_put_contents(ABSPATH.'/wp-content/uploads/beds24-post-log.txt', json_encode($post), FILE_APPEND);

//            var_dump($post);
//exit();
            if (empty($re)){
                $re = $this->sendBooking($post);
            } else {
                $extra = json_decode($this->sendBooking($post),true);
                $re_o = json_decode($re, true);
                $re_o[] = $extra;
                $re = json_encode($re_o);
            }

//            $re = $this->sendBooking($post);

            $this->setBookingInDB($from, $to, $roomId);

        }

        return $re;

    }


    public function setBookingInDB($dateStart, $dateEnd, $roomID, $unsetReserve = null)

    {

        global $wpdb;

//var_dump($unsetReserve);
        $origin = date_create($dateStart);

        $target = date_create($dateEnd);

        $interval = date_diff($origin, $target);

        $dateCount = $interval->format('%a');

        $dates = array($dateStart);

        if (isset($unsetReserve) and $unsetReserve == true){
            for ($i = 0; $i < (int)$dateCount; $i++) {

                $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                array_push($dates, $date);

                $sql = "update `beds_calendar` set avaliable=1 where date='$date' and roomId='$roomID'";

                $res = $wpdb->query($sql);
//                var_dump($res);

            }
        }
        else {
            for ($i = 0; $i < (int)$dateCount; $i++) {

                $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                array_push($dates, $date);

                $sql = "update `beds_calendar` set avaliable=0 where date='$date' and roomId='$roomID'";

                $res = $wpdb->query($sql);

            }
        }

    }


    public function sendBooking($arr)

    {

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/bookings');
//        curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/'); for tests

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));


        $headers = array();

        $headers[] = 'Accept: application/json';

        $headers[] = 'Token: ' . $this->getToken() . '';

        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $result = curl_exec($ch);

        if (curl_errno($ch)) {

            echo 'Error:' . curl_error($ch);

        }

        curl_close($ch);


//        var_dump($result);
        return $result;
    }

    public function showModal($stat,$type)
    {
        if ($stat == 'Error'){

            if ($type == 'beds24Connect'){
                return $this->modal($stat, 'Not able to automatically process the booking. We will try to contact you soon');
            }

        }
    }

    public function modal($stat, $text)
    {
        ob_start();
        ?>
        <style>
            .beds-modal{
                color: black;
                background-color: white;
                width: 50%;
                height: auto;
                padding: 20px;
                border: 3px solid #e83939;
                border-radius: 20px;
                text-align: center;
                position: fixed;
                z-index: 999999;
                left: 25%;
                top: 40%;
            }
            .beds-modal-btn button{
                color: white;
                background: #e83939;
                font-size: 20px;
                font-weight: 500;
                border-radius: 20px;
            }
            .owf-modal{
                background: rgba(140, 140, 140, 0.59);
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                z-index: 99999;
                right: 0;
                /*display: none;*/
            }
        </style>
        <div class="owf-modal" id="beds24-modal">
            <div class="beds-modal">
                <div class="beds-modal-header">
                    <h3 id="modal-head-beds"><?= $stat; ?></h3>
                </div>
                <div class="beds-modal-body">
                    <p id="modal-text-beds"><?= $text; ?></p>
                </div>
                <div class="beds-modal-btn">
                    <button id="modal-ok-beds">OK</button>
                </div>
            </div>
        </div>

        <script>
            jQuery('#modal-ok-beds').on('click', function (){
                jQuery('#beds24-modal').css('display','none');
            })

        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Add to table beds_reserved row with room
     */
    public function setReserve($product_id,$date_from,$date_to,$persons_A, $persons_C)
    {
        global $wpdb;
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $roomID = get_post_meta($product_id,'_product_beds_id',true);
        date_default_timezone_set('Europe/Stockholm');
        $date_reserve = date('Y-m-d H:i:s');
        $res = $wpdb->query("insert into `beds_reserved` (roomId,dateFrom,dateTo,ip,dateReserved) values ('$roomID','$date_from','$date_to','$ip','$date_reserve')");

        //change available and send api req with status "New"
        $this->reserveInAPI($product_id,$date_from,$date_to,$persons_A, $persons_C, $res->fetch_assoc());
    }

    public function reserveInAPI($product_id,$date_from,$date_to,$persons_A,$personc_C,$now)
    {

        $roomID = get_post_meta($product_id,'_product_beds_id',true);
//        $persons_A = $item->get_meta('persons_adult');
//        $persons_C = $item->get_meta('persons_child');
        $post = [

            [

                'roomId' => $roomID,

                "status" => "request",

                "arrival" => $date_from,

                "departure" => $date_to,

                "numAdult" => $persons_A,

                "numChild" => $personc_C,

                "firstName" => 'reserved'
            ]

        ];
        // set in api
        $re = $this->sendBooking($post);

        $re = json_decode($re);
        $bookID = $re[0]->new->id;
        global $wpdb;
        $wpdb->query("update `beds_reserved` set idBookings='$bookID' where dateReserved='$now'");

        $this->setBookingInDB( $date_from,$date_to, $roomID);
    }

    public function emptyCart($roomID,$date_from,$date_to)
    {
//        WC()->cart->empty_cart(true);
        global $woocommerce;
        $cart =$woocommerce->cart;
        foreach ($cart->cart_contents as $id => $val){
            if ((get_post_meta(($val['product_id'] ),'_product_beds_id',true) == $roomID) and
                ($val['booked_from'] == $date_from) and ($val['booked_to'] == $date_to)){
                $woocommerce->cart->remove_cart_item( $id );
            }

        }
    }

    private function updateDatesViaCSV($post)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/inventory/rooms/calendar');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Token: ' . $this->getToken() . '';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

//        var_dump($result);
//        die();
        return $result;

    }

    public function parseCSV($fileName)
    {
        $headers = array();
        $priceArr1 = array();
        $priceArr2 = array();
        $dateArr = array();
        $dataArr = array();
        $calendarArr = array();
        $row = 1;
        $roomID = 0;
        $rooms = array();
        $price2 = false;
        if (($handle = fopen(BEDS_DIR.'/csvdir/'.$fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $num = count($data);
                // розбивка файла
                for ($c=0; $c < $num; $c++) {

                    // 1 строка, дати
                    if ($row == 1){
//                        array_push($headers, $data[$c]);
                        if ($c != 0){
                        array_push($headers, date('Y-m-d',strtotime(str_replace('.','-',$data[$c]))));
                        }
                    }
                    // строки з даними
                    else{
                        // перша клітка roomID
                        if ($c == 0){
//                            перша клітинка першої строки
                            if ($roomID == 0){
                                $roomID = $data[$c];
                                array_push($dataArr,array('roomId'=>$roomID));
                                $price2 = false;
                            }
                            // інші перші клітинки з ІД
                            else{
                                // якшо рум ІД такий як попередній, це друга ціна
                                if ($roomID == $data[$c]){
                                    $price2 = true;
                                } else {
                                    $roomID = $data[$c];
                                    array_push($dataArr,array('roomId'=>$roomID));
                                    $price2 = false;
                                }
                            }
                        }
                        // інші клітки, ціни
                        else {
                            // 2 sum. це друга ціна, другий рядок
                            if ($price2){
                                // дата по ціні
                                $day = date('Y-m-d',strtotime(str_replace('.','-',$headers[$c-1])));
                                $p = str_replace(',','.',$data[$c]);
                                array_push($priceArr2, $p);

//                                array_push($dataArr,array('roomId'=> $roomID, 'calendar'=>
//                                    [array('from'=>$day, "to" => $day, 'price2'=>$p)]));
                            }
                            // 1 sum. перша ціна перший рядок
                            else {
                                // беремо дату по тій же колонці шо і ціна
                                $day = date('Y-m-d',strtotime(str_replace('.','-',$headers[$c-1])));
                                // в ціні міняємо кому на крапку
                                $p = str_replace(',','.',$data[$c]);
                                // ціна попадає в масив всіх ?! перших цін // а не конкретного румІД
                                array_push($priceArr1, $p);
                                // попадає в загальний масив тільки дата ?!
                                array_push($dateArr, $day);
                                array_push($rooms,$roomID);
//                                array_push($dataArr,array('roomId'=> $roomID, 'calendar'=>
//                                    [array('from'=>$day, "to" => $day, 'price1'=>$p)]));
                            }
                        }
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        $finDate = array();

        foreach ($dataArr as $d){
            for ($i = 0; $i < count($dateArr); $i++){
                if($d["roomId"] == $rooms[$i]){
                    array_push($finDate,array('roomId'=> $rooms[$i], 'calendar'=>
                                    [array('from'=>$dateArr[$i], "to" => $dateArr[$i], 'price1'=>$priceArr1[$i], 'price2'=>$priceArr2[$i])]));
                }
            }

        }

//        for ($i = 0; $i < count($dateArr); $i++){
//            array_push($calendarArr,array('from'=>$dateArr[$i], "to" => $dateArr[$i], 'price1'=>$priceArr1[$i], 'price2'=>$priceArr2[$i]));
//        }
//
//        array_push($dataArr,array('roomId'=> $roomID, 'calendar'=>$calendarArr));

        return $this->updateDatesViaCSV($finDate);
    }

    public function sendDepositRemind($orderID){
        $order = wc_get_order($orderID);
        $fio = $order->get_billing_first_name().' '. $order->get_billing_last_name();
        $link = $order->get_checkout_payment_url();
        $letter = $this->reminder_letter($fio, $orderID,$link);
        //dd($letter);
        $order_data = $order->get_data();
        $to = $order_data['billing']['email'];
        $order_date_created = $order_data['date_created']->date('Y-m-d');
        if (get_locale()=='sv_SE')
        {
            $subject = 'Påminnelse om betalning';
        } else {
            $subject = 'Final payment reminder';
        }
        $from = 'Rehnbergs Stuguthyrning <bokning@tandadalen.com>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        if (mail($to, $subject, $letter, $headers)) {
            return true;
        } else {
            return false;
        }


    }

    public function reminder_letter($fio,$order_id,$link)
    {
        global $wpdb;
        $order = wc_get_order($order_id);
        $order_data = $order->get_data(); // The Order data

        $depositPartialPaymentsData = get_post_meta($order_id, '_awcdp_deposits_payment_schedule', true);
        $accomD = getItemMetaData($order_id);
        $pt = 1;
        $itr2 = 0;
//var_dump($depositPartialPaymentsData);

        foreach ($order->get_items() as $item_key => $item) {
            $product_name = $item->get_name();
            $item_data = $item->get_data();
            $facts = get_field('facts',$item_data['product_id']) ?: '';

            $message .= '<tr>

                <td style="'.cssClassToStyle('mail-text-td').'">

                    <p style="'.cssClassToStyle('mail-p-title lh10').'"><strong>' . $product_name . '</strong></p>
                ';
            if($facts){
                $message .= '<p style="'. cssClassToStyle('mail-p lh10') .' color: #767A7D; font-size: 10px; font-weight: 400; line-height: 5px;"><i> '.$facts.'</i></p>';
            }

            $id = $accomD['lineItemId'][$itr2];
            $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE `order_item_id` = '{$id}'", OBJECT);

            if ($res['9']->meta_key == 'booked_from') {
                $getFirstAmountDate = $res['9']->meta_value;
            }

            foreach ($res as $row) {
                if ($row->meta_key == 'booked_from') {
                    $bookedFrom = $row->meta_value;
                }
                if ($row->meta_key == 'booked_to') {
                    $bookedTo = $row->meta_value;
                }
                if ($row->meta_key == 'persons_adult') {
                    $personsAdult = $row->meta_value;
                }
                if ($row->meta_key == 'persons_child') {
                    $personsChild = $row->meta_value;
                }
                if ($item->get_meta('Hund')) {
                    $hundFlag = 1;
                }
            }

            if (!empty($hundFlag)) {
                $hund = 'HUND';
            } else {
                $hund = '';
            }

            $message .= '<p style="'. cssClassToStyle('mail-p lh10') .'">
                        '.__('TILLTRÄDE:','beds24').' <strong> ' . $bookedFrom . '</strong> '.__('från  16:00  AVRESA:','beds24').' <strong>' . $bookedTo . '</strong> '.__('senast 11:00','beds24').'  </p>
                    <p style="'. cssClassToStyle('mail-p lh10') .'">' . $personsAdult .' '.__('VUXNA','beds24') .', ' . $personsChild .' '.__('BARN','beds24'). ', ' . $hund . '</p>
                </td>
            </tr>';
            $pt++;
            $itr2++;
        }

        $message .= '<tr>
                <td style="'.cssClassToStyle('mail-text-td').'">
                    <table width="100%" style="border-collapse: collapse;" class="nopad-t">
                        <tr>
                            <th style="'.cssClassToStyle('mail-table-summary-th-1').'">'.__('AVGIFTER','beds24').'</th>
                            <th style="'.cssClassToStyle('mail-table-summary-th-2').'"></th>
                        </tr>';

        $currency = get_woocommerce_currency();
        $getIT = getItemMetaData($order_id);
        $order_date = $order->get_date_created(); // This returns a DateTime object
        $cleanPrice = function($p){
            return (float)trim(strip_tags($p));
        };
        $formatted_date = $order_date->date('Y-m-d');
        foreach ($order->get_items() as $item_key => $item) {
            $order_item_type = $item->get_type();
            $item_data = $item->get_data();
            $product_name = $item_data['name'];
            $line_total_from_data = $item_data['total'];
            $line_total_tax_from_data = $item_data['total_tax'];

            $prod = wc_get_product($item_data['product_id']);
            $roomID = get_post_meta($prod->get_id(),'_product_beds_id', true);
            $t = $wpdb->prefix.'postmeta';

            $postID = $wpdb->get_var("select post_id from $t where meta_key='_product_beds_id' and meta_value=$roomID");
            $datetime1 = date_create($bookedFrom);
            $datetime2 = date_create($bookedTo);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->days;
            require_once(BEDS_DIR . '/includes/class.action.php');
            $act = new \beds_booking\Action_beds_booking();
            $price_by_period = $act->getRoomPriceByDays($days,$bookedFrom, $bookedTo, $postID);
//            var_dump( $roomID);
//            var_dump($price_by_period);
            $future_date = date('Y-m-d', strtotime('-30 days', strtotime($getFirstAmountDate)));

                $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdl mail-table-summary-tdh pb0').'">' . $product_name . '</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdr mail-table-summary-tdh pb0').'">' . $cleanPrice($price_by_period). ' ' . $currency . '</td>
                        </tr>';

                if(!empty($order->get_items('fee'))){
                    foreach ($order->get_items('fee') as $key => $item_fee) {
                        $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdl').'">'.$item_fee->get_name().'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdr').'">'.$item_fee->get_total().' '.$currency.'</td>
                        </tr>';
                    }
                }
                if (!empty($item->get_meta('Hund'))) {
                    $w = str_replace('&#107;&#114;','',$item->get_meta('Hund'));

                    $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdl').'">'.__('Hunddeposition','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdr').'">'.$cleanPrice($w).' '.$currency.'</td>
                        </tr>';
                }
                if (!empty($item->get_meta('Avresestädning'))) {
                    $w = str_replace('&#107;&#114;','',$item->get_meta('Avresestädning'));

                    $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdl').'">'.__('Avresestädning','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdr').'">'.$cleanPrice($w).' '.$currency.'</td>
                        </tr>';
                }
                if (!empty($item->get_meta('Avbokningskydd'))) {
                    $w = str_replace('&#107;&#114;','',$item->get_meta('Avbokningskydd'));
                    $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdl').'">'.__('Avbokningskydd','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td pt0 mail-table-summary-tdr').'">'.$cleanPrice($w).' '.$currency.'</td>
                        </tr>';
                }

                $itr++;

        }
//        die();
            $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdl mail-table-summary-td-total').'">'.__('TOTALT','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdr mail-table-summary-td-total').'">' . round($order_data['total']) . ' ' . $currency . '</td>
                        </tr>';

        $message .= '</table>
                </td>
            </tr>';
        $pay_type = $order->get_payment_method(); //direct == 'cod'

        if ($pay_type == 'stripe_cc'){
            //$pay_type = 'Stripe';
            $pay_type = __('Card');
        }
        if(!$pay_type || $pay_type == 'cod'){
            $pay_type = __('Plusgiro/Banköverföring');
        }
        $message .= '<tr>

                <td style="'.cssClassToStyle('mail-text-td').'">
                    <table width="100%" style="border-collapse: collapse;" class="nopad-t">
                        <tr>
                            <th style="'.cssClassToStyle('mail-table-summary-th-1').'">'.__('GENOMFÖRDA BETALNINGAR','beds24').'</th>
                            <th style="'.cssClassToStyle('mail-table-summary-th-2').'"></th>
                        </tr>

                        <tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdl mail-table-summary-tdh pb0').'">'.__('Delbetalning','beds24').':</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdr mail-table-summary-tdh pb0').'">'. round($depositPartialPaymentsData['deposit']['total']) . ' ' . $currency . '</td>
                        </tr>
                        <tr>
                         <td style="'.cssClassToStyle('mail-table-summary-td pt0').'"> <span style="text-transform: capitalize;">'. $pay_type.'</span>, ' . $formatted_date . '</td>
                        </tr>
                        <tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td  mail-table-summary-tdl  mail-table-summary-td-total').'">'.__('TOTALT','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td  mail-table-summary-tdr  mail-table-summary-td-total').'">' . round($depositPartialPaymentsData['deposit']['total']) . ' ' . $currency . '</td>
                        </tr>
                    </table>
                </td>

            </tr>';

        $message .= '<tr>

                <td style="'.cssClassToStyle('mail-text-td').'">
                    <table width="100%" style="border-collapse: collapse;" class="nopad-t">
                        <tr>
                            <th style="'.cssClassToStyle('mail-table-summary-th-1').'">'.__('RESTERANDE BETALNINGAR','beds24').'</th>
                            <th style="'.cssClassToStyle('mail-table-summary-th-2').'"></th>
                        </tr>
                        <tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdl mail-table-summary-tdh pb0').'">'.__('Slutbetalning:','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td mail-table-summary-tdr mail-table-summary-tdh pb0').'">' . round($depositPartialPaymentsData['unlimited']['total']) . ' ' . $currency . '</td>
                        </tr>
                        <tr> <td style="'.cssClassToStyle('mail-table-summary-td pt0').'">'.__('Förfallodag:','beds24').' ' . $future_date . '</td></tr>';

        $message .= '<tr>
                            <td style="'.cssClassToStyle('mail-table-summary-td  mail-table-summary-tdl  mail-table-summary-td-total').'">'.__('TOTALT','beds24').'</td>
                            <td style="'.cssClassToStyle('mail-table-summary-td  mail-table-summary-tdr  mail-table-summary-td-total').'">' . round($depositPartialPaymentsData['unlimited']['total']) . ' ' . $currency . '</td>
                        </tr>
                    </table>
                </td>
            </tr>';
        ob_start();
        ?>
        <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 20px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 800px; background-color: #ffffff; border: 1px solid #dcdcdc; padding: 20px;">
            <tr>
                <td style="<?= cssClassToStyle('mail-logo-td') ?>">
                    <div style="text-align: center; width: 100%;">
                        <div id="logo-container" style="text-align: center; width: 100%;">
                            <img id="logo" src="https://stugor2.hemsida.eu/wp-content/uploads/2024/07/rehnbergs_logo_stor.png" alt="logo" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="<?= cssClassToStyle('mail-text-tdp') ?>">
                    <p style="<?= cssClassToStyle('mail-p') ?>">
                        <?php _e('Hi', 'beds24');?> <?php echo $fio;?> !
                    </p>
                </td>
            </tr>
            <tr>
                <td style="<?= cssClassToStyle('mail-text-tdp') ?>">
                    <p style="<?= cssClassToStyle('mail-p') ?>">
                        <?php _e('We just want to remind you that it is time to make the final payment for booking', 'beds24');?> <?php echo $order_id;?> .
                    </p>
                </td>
            </tr>
            <tr>
                <td style="<?= cssClassToStyle('mail-text-tdp') ?>">
                    <p style="<?= cssClassToStyle('mail-p') ?>">
                        <?php _e('Vänligen betala med plusgiro/banköverföring eller via','beds24') ?> <a href="<?= $order->get_checkout_payment_url() ?>"> <?php _e('kassan på vår websida.','beds24') ?></a> <?php _e('Vid bankbetalning så uppge ditt bokningsnummer. Plusgiro 122 08 27-8. BAN SE28 9500 0099 6034 1220 8278, BIC NDEASESS.','beds24') ?></p>
                </td>
            </tr>

            <?php if(!$order->get_payment_method() || $order->get_payment_method() == 'code'): ?>
            <tr>
                <td style="<?= cssClassToStyle('mail-text-tdp') ?>">
                    <p style="<?= cssClassToStyle('mail-p') ?>"><?php _e('Vänligen uppge ditt bokingsnummer vid betalning. Gör betalningen till Plusgiro 122 08 27-8 eller IBAN SE28 9500 0099 6034 1220 8278, BIC NDEASESS', 'beds24'); ?></p>
                </td>
            </tr>
            <?php endif; ?>

            <?php echo $message;?>
            <?php /* <tr>
                <td style="padding: 10px;">
                    <h5 style="font-size: 24px;line-height: 140%;font-family: 'Saira', sans-serif;font-weight: bold;"><?php _e('PAYMENT INFORMATION','beds24');?></h5>
                    <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                        <?php _e('Please pay by bank transfer to IBAN SE28 9500 0099 6034 1220 8278, BIC NDEASESS or with the checkout','beds24');?>
                        <?php echo ' <a style="color: #CA0013;text-decoration: underline;" href="'.$link.'">'; _e('link','beds24');echo '</a> ';?>
                        <?php _e('on our website. If you pay by bank transfer, please include your booking number', 'beds24');?>
                    </p>
                    <p style="margin-bottom: 5px; margin-top: 0px; font-size: 16px; line-height: 22px; padding: 0; font-weight: 500; color: #000;">
                        <?php _e('For bank transfer from Swedish banks, you can pay with Plusgiro 122 08 27-8','beds24');?>
                    </p>
                </td>
            </tr> */?>

        </table>
        </body>
        <?php
        return ob_get_clean();
    }
}

