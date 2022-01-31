<?php
$url = env('BASE_URL');
return [
    'monthArr' =>[
        '1'=>'January',
        '2'=>'February',
        '3'=>'March',
        '4'=>'April',
        '5'=>'May',
        '6'=>'June',
        '7'=>'July',
        '8'=>'August',
        '9'=>'September',
        '10'=>'October',
        '11'=>'November',
        '12'=>'December',
       
    ],

   'deliveryArr' =>[
        'Food Delivery'=> $url.'/public/assets/delivery/Group.png',
        'Zomato Delivery'=>  $url.'/public/assets/delivery/Zomato Icon.png',
        'Swiggy Delivery'=>  $url.'/public/assets/delivery/Swiggy Icon.png',
        'Flipcart Delivery'=>  $url.'/public/assets/delivery/Flipkart Icon.png',
        'Amazon Delivery'=>  $url.'/public/assets/delivery/image 33.png',
        'Paytm Delivery'=>  $url.'/public/assets/delivery/Paytm Icon.png',
        'Delhivery'=>  $url.'/public/assets/delivery/Delhivery Icon.png',
        'Blue Dart Delivery'=>  $url.'/public/assets/delivery/Blue Dart Express Limited Logo.png',
        'India Post Delivery'=>  $url.'/public/assets/delivery/Department of Posts (DoP) Icon.png',
        'DTDC Couriour'=>  $url.'/public/assets/delivery/Dtdc Logo.png',
        'DHL Express'=>  $url.'/public/assets/delivery/FedEx Icon undefined.png',
        'FedEx'=>  $url.'/public/assets/delivery/FedEx Icon.png',
        'Gati Limited'=>  $url.'/public/assets/delivery/Gati Limited Icon.png',
        'Ecom Express Logistics'=>  $url.'/public/assets/delivery/Ecom Express Logo.png',
        'Others'=>  $url.'/public/assets/delivery/Group-1.png',
       
    ],

    'cabArr' =>[
        'Ola'=>  $url.'/public/assets/delivery/Meru Icon.png',
        'Uber'=>  $url.'/public/assets/delivery/Uber Icon.png',
        'Meru'=>  $url.'/public/assets/delivery/Meru Icon.png',
        'Saavan'=>  $url.'/public/assets/delivery/saavnCab Logo.png',
        'Bharat Taxi'=>  $url.'/public/assets/delivery/Bharat Taxi Icon.png',
        'Gozo Cabs'=>  $url.'/public/assets/delivery/Gozocabs (Gozo technologies Pvt Ltd) Icon.png',
        'Rapido'=>  $url.'/public/assets/delivery/Paytm Icon.png',
        'Others'=>  $url.'/public/assets/delivery/Group.png',
    ],


    
];