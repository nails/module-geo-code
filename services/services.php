<?php

return [
    'services'  => [
        'GeoCode' => function () {
            if (class_exists('\App\GeoCode\Service\GeoCode')) {
                return new \App\GeoCode\Service\GeoCode();
            } else {
                return new \Nails\GeoCode\Service\GeoCode();
            }
        },
    ],
    'models'    => [
        'Driver' => function () {
            if (class_exists('\App\GeoCode\Model\Driver')) {
                return new \App\GeoCode\Model\Driver();
            } else {
                return new \Nails\GeoCode\Model\Driver();
            }
        },
    ],
    'factories' => [
        'LatLng' => function () {
            if (class_exists('\App\GeoCode\Result\LatLng')) {
                return new \App\GeoCode\Result\LatLng();
            } else {
                return new \Nails\GeoCode\Result\LatLng();
            }
        },
    ],
];
