<?php

return [
    'services'  => [
        'Driver'  => function () {
            if (class_exists('\App\GeoCode\Service\Driver')) {
                return new \App\GeoCode\Service\Driver();
            } else {
                return new \Nails\GeoCode\Service\Driver();
            }
        },
        'GeoCode' => function () {
            if (class_exists('\App\GeoCode\Service\GeoCode')) {
                return new \App\GeoCode\Service\GeoCode();
            } else {
                return new \Nails\GeoCode\Service\GeoCode();
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
