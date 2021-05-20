<?php

namespace Nails\GeoCode\Interfaces;

use Nails\GeoCode\Result\LatLng;

interface Driver
{
    /**
     * Returns information about a particular address
     * @param string $sAddress  The address to look up
     * @return LatLng
     */
    public function lookup($sAddress): LatLng;
}
