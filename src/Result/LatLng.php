<?php

/**
 * This class is an LatLng object which should be returned by Drivers
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    Result
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoCode\Result;

class LatLng
{
    private $sAddress;
    private $sLat;
    private $sLng;

    // --------------------------------------------------------------------------

    /**
     * Define the LatLng object
     * @param string $sAddress
     * @param string $sLat
     * @param string $sLng
     */
    public function __construct($sAddress = '', $sLat = '', $sLng = '')
    {
        $this->sAddress = $sAddress;
        $this->sLat     = $sLat;
        $this->sLng     = $sLng;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the address property
     * @param string $sAddress The address to set
     * @return $this
     */
    public function setAddress($sAddress)
    {
        $this->sAddress = $sAddress;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the address
     * @return string
     */
    public function getAddress()
    {
        return $this->sAddress;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the address's Latitude and Longitude
     * @param string $sLat The address's Latitude
     * @param string $sLng The address's Longitude
     * @return $this
     */
    public function setLatLng($sLat, $sLng)
    {
        $this->setLat($sLat);
        $this->setLng($sLng);
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Latitude property
     * @param string $sLat The Latitude to set
     * @return $this
     */
    public function setLat($sLat)
    {
        $this->sLat = $sLat;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the latitude
     * @return string
     */
    public function getLat()
    {
        return $this->sLat;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Longitude property
     * @param string $sLng The Longitude to set
     * @return $this
     */
    public function setLng($sLng)
    {
        $this->sLng = $sLng;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the longitude
     * @return string
     */
    public function getLng()
    {
        return $this->sLng;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the address's coordinates
     * @return \stdClass
     */
    public function getLatLng()
    {
        $oLatLng = new \stdClass();
        $oLatLng->lat = $this->sLat;
        $oLatLng->lng = $this->sLng;

        return $oLatLng;
    }
}
