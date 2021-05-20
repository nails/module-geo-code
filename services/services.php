<?php

use Nails\GeoCode\Result;
use Nails\GeoCode\Service;

return [
    'services'  => [
        'Driver'  => function (): Service\Driver {
            if (class_exists('\App\GeoCode\Service\Driver')) {
                return new \App\GeoCode\Service\Driver();
            } else {
                return new Service\Driver();
            }
        },
        'GeoCode' => function (): Service\GeoCode {
            if (class_exists('\App\GeoCode\Service\GeoCode')) {
                return new \App\GeoCode\Service\GeoCode();
            } else {
                return new Service\GeoCode();
            }
        },
    ],
    'factories' => [
        'LatLng' => function (): Result\LatLng {
            if (class_exists('\App\GeoCode\Result\LatLng')) {
                return new \App\GeoCode\Result\LatLng();
            } else {
                return new Result\LatLng();
            }
        },
    ],
];
