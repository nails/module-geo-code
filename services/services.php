<?php

return array(
    'services' => array(
        'GeoCode' => function () {
            if (class_exists('\App\GeoCode\Library\GeoCode')) {
                return new \App\GeoCode\Library\GeoCode();
            } else {
                return new \Nails\GeoCode\Library\GeoCode();
            }
        }
    ),
    'models' => array(
        'Driver' => function () {
            if (class_exists('\App\GeoCode\Model\Driver')) {
                return new \App\GeoCode\Model\Driver();
            } else {
                return new \Nails\GeoCode\Model\Driver();
            }
        },
    ),
    'factories' => array(
        'LatLng' => function () {
            if (class_exists('\App\GeoCode\Result\LatLng')) {
                return new \App\GeoCode\Result\LatLng();
            } else {
                return new \Nails\GeoCode\Result\LatLng();
            }
        }
    )
);
