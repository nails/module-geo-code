<?php

namespace Nails\GeoCode\Interfaces;

interface Driver
{
    /**
     * Returns information about a particular address
     * @param string $sAddress  The address to look up
     * @return \stdClass
     */
    public function lookup($sAddress);
}
