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
