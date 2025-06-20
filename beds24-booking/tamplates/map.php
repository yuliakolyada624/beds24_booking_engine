<style>

  .gm-style-iw, .gm-style-iw-d {
    overflow: unset !important;
  }

  .gm-style-iw-c{
    padding: 0 !important;
    max-width: none !important;
    max-height: none !important;
  }

  .gm-style-iw-c .gm-style-iw-d{
    max-height: none !important;
  }

</style>

<?php
//$post_id = get_the_id();
//if (isset($_SESSION['wishlist'])) {
//    $list = explode(',', $_SESSION['wishlist']);
//    $disp = [0 => 'block', 1 => 'none'];
//    if (in_array($post_id, $list)) {
//        $disp[0] = 'none';
//        $disp[1] = 'block';
//    }
//} else {
//    $disp = [0 => 'block', 1 => 'none'];
//} ?>

<!--<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js" type="module"></script>-->

<script src=""></script>

    <script>
        /**
         * @license
         * Copyright 2019 Google LLC. All Rights Reserved.
         * SPDX-License-Identifier: Apache-2.0
         */
        var activeInfoWindow;
        const citymap = {
            hundfjallet: {
                center: { lat: 61.16026458036356, lng: 12.982057874492293},
                houses: 1000000,

                title: 'Hundfjället'

            },

            tandadalen: {

                center: { lat: 61.17323334975624, lng: 13.02401617168265},

                houses: 1000000,

                title:'Tandådalen'

            },

            hogfjallet: {

                center: { lat: 61.14525929925045, lng: 13.11909770266309},

                houses: 1000000,

                title:'Högfjället'

            },

            lindvallen: {

                center: { lat: 61.151611102217984, lng: 13.172677652501257},

                houses: 1000000,

                title:'Lindvallen'

            },

        };

        const xmlhttp = new XMLHttpRequest();

        let res

        xmlhttp.onload = function() {

                // console.log(this.responseText)

                res = JSON.parse(this.responseText.slice(0,-1))

        }

        function initMap() {

            // Create the map.

            let popup

            const map = new google.maps.Map(document.getElementById("map"), {

                zoom: 12.75,

                center: { lat: 61.155957, lng: 13.078008 },

                mapTypeId: "terrain",

            });



            // Construct the circle for each value in citymap.

            // Note: We scale the area of the circle based on the population.

            for (const city in citymap) {
                // console.log(city);
                // Add the circle for this city to the map.

                const cityCircle = new google.maps.Circle({

                    strokeColor: "#00a6ff",

                    strokeOpacity: 0.8,

                    strokeWeight: 2,

                    fillColor: "#00a6ff",

                    fillOpacity: 0.35,

                    map,

                    center: citymap[city].center,

                    radius: Math.sqrt(citymap[city].houses),

                });



                const image = "https://stugor2.hemsida.eu/dot.png";

                var marker = new google.maps.Marker({

                    position: citymap[city].center, // new google.maps.LatLng(data.lat, data.lon),

                    map: map,

                    label: {

                        text: citymap[city].title,

                        color: "#3b3b3b",

                    },

                    icon: image,

                });

            }

            const markers = [];


            let av_products_ids_str = $('body .av_products_ids_str').val();
            const av_products_ids = av_products_ids_str.split(',');
            let av_products_ids_int = av_products_ids.map(function (x) { 
              return parseInt(x, 10); 
            });

            // setTimeout(()=>
            
            res.forEach(function (elem) {
                let productID = parseInt(elem.productID)
                if(av_products_ids_int.includes(productID)){
                    // console.log(productID);
                    let ses = "<?php echo $_SESSION['wishlist'];?>"
                    // console.log(ses)
                    let disp0 = 'block';
                    let disp1 = 'none';
                    if (ses.includes(productID.toString())){
                        disp0 = 'none';
                        disp1 = 'block';
                    } else {
                        let disp0 = 'block';
                        let disp1 = 'none';
                    }

                const contentString =

                    '<div id="content" style="width:300px;">' +

                    '<div id="siteNotice">' +

                    "</div>" +

                    "<a href='#' onclick='window.open(\""+elem.link+"\");'><img src='"+elem.img+"' width='300'></a>"+

                    "<label style='position: absolute;left: 10px;top: 5px;background: #ffffffc2;border-radius: 50%;padding: 5px;' class='add-to-favorites' data-id='"+elem.productID+"'>" +

                    '<svg data-id="'+elem.productID+'-b" style="display: '+disp0+'" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path fill="red" d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/></svg>'+
                    '<svg data-id="'+elem.productID+'-r" style="display: '+disp1+'" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path fill="red" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>'+

                    '</label>'+
                    // '<p style="position: absolute;left: 10px;top: 5px;background: #ffffffc2;border-radius: 50%;padding: 5px;" id="'+elem.productID+'" class="add-to-favorites"><i class="far fa-heart"></i></p>'+

                    '<div style="display: flex;justify-content: space-between;padding: 20px 10px 0 10px;"><div><a href="#" onclick="window.open(\''+elem.link+'\')"><p style="font-size: 16px;font-weight: 900;">'+elem.nameRoom+'</p></a></div><div><p style="color:#CA0013; font-size:18px;font-weight:500;">'+elem.price+' SEK</p></div></div>' +

                    '<div id="bodyContent" style="padding: 0 10px;">' +

                    "<span style='font-size:17px;font-weight:500;'>Period: <?php echo $period1.' - '. $period2?></span>" +

                    "<div>"+elem.icons+"</div>"+

                    "</div>" +

                    '<div class="search-item-buttons" style="flex-direction: row;justify-content: space-around; padding: 0 10px 20px 10px;">'+

                    '<a style="width: 50%;" href="#" class="btn btn-transparent add-to-cart" data-s="<?php echo $s;?>" data-product_id="'+ elem.productID+'" data-custom_price="'+elem.price+'" data-toggle="modal" data-target="#<?php echo $s;?>">+ <i class="fas fa-shopping-cart"></i></a>'+

                    '<a style="width: 50%;" data-product_id="'+ elem.productID+'" data-custom_price="'+elem.price+'" class="beds_add_to_cart btn open-cart" href=""><i class="fas fa-shopping-cart"></i> Boka</a></div>'+

                    "</div>";



                const infowindow = new google.maps.InfoWindow({

                    content: contentString,

                    ariaLabel: +elem.nameRoom,

                    // pixelOffset: new google.maps.Size(200, 0)

                });

                // const svgMarker = {

                //     path: "M23.685 9.66001L20.2634 6.74425V2.60349C20.2634 2.17609 19.9208 1.82966 19.4981 1.82966H17.0727C16.65 1.82966 16.3073 2.17609 16.3073 2.60349V3.37108L12.6673 0.269544C12.2816 -0.0903363 11.698 -0.0903363 11.3222 0.269544L0.314413 9.66001C0.0177048 9.93991 -0.0811776 10.37 0.0672071 10.7599C0.215592 11.14 0.581396 11.39 0.987059 11.39H2.57919V23.0003C2.57919 23.5504 3.02434 24.0004 3.56826 24.0004H20.4311C20.975 24.0004 21.4201 23.5504 21.4201 23.0003V11.39H23.0124C23.4179 11.39 23.7839 11.14 23.9322 10.7499C24.0805 10.37 23.9816 9.93991 23.685 9.66001ZM12.7464 20.3648C12.3211 20.9547 11.4508 20.9547 11.0156 20.3648C9.65081 18.4647 7.13871 14.7246 7.13871 12.8044C7.13871 10.1644 9.26505 8.01423 11.8859 8.01423C14.5069 8.01423 16.6333 10.1644 16.6333 12.8044C16.6333 14.7246 14.1211 18.4647 12.7464 20.3648Z",

                //     fillColor: "#CA0013",

                //     fillOpacity: 0.6,

                //     strokeWeight: 0,

                //     rotation: 0,

                //     scale: 2,

                //     anchor: new google.maps.Point(0, 20),

                // };

                // const beachFlagImg = document.createElement("img");

                //beachFlagImg.src = "<?php //echo BEDS_URL.'assets/svg/V.png';?>//"

                const marker1 = new google.maps.Marker({

                    position: { lat: parseFloat(elem.latitude), lng: parseFloat(elem.longitude)},

                    icon: "<?php echo BEDS_URL.'assets/img/house_marker_1.png';?>",

                    // content: beachFlagImg,

                    map: map,

                });



                // infowindow to left bottom

                function updateInfoWindowPosition() {

                  var bounds = map.getBounds();

                  var sw = bounds.getSouthWest();



                  // infowindow.setPosition(sw);

                    // const markerPosition = marker1.getPosition();
                    //
                    // // Assuming the InfoWindow has a certain width and height
                    // const infowindowWidth = 300; // Replace with the actual width
                    // const infowindowHeight = 490; // Replace with the actual height
                    // const offset = 0.0001; // Adjust this value as needed for the lat/lng offset
                    //
                    // // Calculate the new position for the bottom-left corner
                    // const newPosition = {
                    //     lat: markerPosition.lat() - (infowindowHeight / 111320), // Convert meters to lat (approx.)
                    //     lng: markerPosition.lng() - (infowindowWidth / (111320 * Math.cos(markerPosition.lat() * Math.PI / 180))), // Convert meters to lng (approx.)
                    // };
                    //
                    // // Set the new position for the InfoWindow
                    // infowindow.setPosition(newPosition);

                }



                marker1.addListener("click", () => {

                    infowindow.open({

                        anchor: marker1,

                        map,

                    });



                    if (activeInfoWindow) {

                      activeInfoWindow.close();

                    }

                    infowindow.open({

                        anchor: marker1,

                        map,

                    });

                    activeInfoWindow = infowindow;

                    updateInfoWindowPosition()
                    // map.setCenter(marker.getPosition());
                });

                markers.push(marker1)
                }
            })

            // ,1000)

            // setTimeout(

                new MarkerClusterer(map, markers, {

                    maxZoom: 14,

                imagePath: 'https://cdn.jsdelivr.net/gh/googlemaps/v3-utility-library@07f15d84/markerclustererplus/images/m'

            })

                // ,1500)

        }



        let str = "/wp-admin/admin-ajax.php?action=getHouseList&d_s=<?php echo $date_start;?>&d_e=<?php echo $date_end;?>&d=<?php echo $days;?>&n_a=<?php echo $adult;?>&c=<?php echo $child;?>";
        xmlhttp.open("GET", str);

        xmlhttp.send();

        setTimeout(

            window.initMap = initMap,2000

        )

    </script>



    <style>



        /**



         * @license



         * Copyright 2019 Google LLC. All Rights Reserved.



         * SPDX-License-Identifier: Apache-2.0



         */



        /**



         * Always set the map height explicitly to define the size of the div element



         * that contains the map.



         */



        #map {



            height: 600px;



            display: none;



        }







    </style>



<div class="bg-white">
<div id="map" class="bed_24_map" style="height: 800px !important; max-height: 100vh"></div>
</div>







    <script



    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7evqZwA0Lo6todahvFbAg8G_uGd6eA1g&callback=initMap&v=weekly"



    defer



></script>







<script src="https://cdn.jsdelivr.net/gh/googlemaps/v3-utility-library@07f15d84/markerclustererplus/src/markerclusterer.js"></script>







    <?php



//    global $wpdb;



//



//    $res = $wpdb->get_results('select roomId,nameRoom,id,latitude,longitude from `beds_properties`',ARRAY_A);



//    var_dump($res);



//    $loop = new WP_Query( array(



//        'post_type' => 'product',



//        'meta_query' => $meta_arr,



//        'orderby' => 'post__in',



//        'order' => 'DESC',



//        'posts_per_page'=>-1,



//    ));







//    require_once(BEDS_DIR . '/includes/class.action.php');



//    $act = new \beds_booking\Action_beds_booking();



//



//    foreach ($res as $key => $val) {



//        $t = $wpdb->prefix.'postmeta';



//        $id = $val['roomId'];



//        $postID = $wpdb->get_var("select post_id from $t where meta_key='_product_beds_id' and meta_value=$id");



//        $res[$key]['img'] = get_the_post_thumbnail_url($postID,'post-thumbnail');



//



//



//        $res[$key]['permalink'] = get_the_permalink().'?'.$get_parrs;



//



//        var_dump($get_parrs);



//        $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $postID);



//        $res[$key]['price'] = $price_by_period;



//    }



//    var_dump($res);







