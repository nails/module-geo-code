<?php

/**
 * This class abstracts access to the GeoCode service
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    Library
 * @author      Nails Dev Team
 * @link
 * @todo        Update this library to be a little more comprehensive, like the CDN library
 */

namespace Nails\GeoCode\Library;

use Nails\GeoCode\Exception\GeoCodeException;
use Nails\GeoCode\Exception\GeoCodeDriverException;

class GeoCode
{
    use \Nails\Common\Traits\Caching;

    // --------------------------------------------------------------------------

    protected $oDriver;
    protected $aCache;

    // --------------------------------------------------------------------------

    const DEFAULT_DRIVER = 'nailsapp/driver-geo-code-google';

    // --------------------------------------------------------------------------

    /**
     * Construct the Library, test that the driver is valid
     * @throws GeoCodeException
     */
    public function __construct()
    {
        //  Load the driver
        // @todo: build a settings interface for etting and configuring the driver.
        $sSlug    = defined('APP_GEO_CODE_DRIVER') ? strtolower(APP_GEO_CODE_DRIVER) : self::DEFAULT_DRIVER;
        $aDrivers = _NAILS_GET_DRIVERS('nailsapp/module-geo-code');
        $oDriver  = null;

        for ($i=0; $i < count($aDrivers); $i++) {
            if ($aDrivers[$i]->slug == $sSlug) {
                $oDriver = $aDrivers[$i];
                break;
            }
        }

        if (empty($oDriver)) {
            throw new GeoCodeDriverException('"' . $sSlug . '" is not a valid Geo-Code driver', 1);
        }

        $sDriverClass = $oDriver->data->namespace . $oDriver->data->class;

        //  Ensure driver implements the correct interface
        $sInterfaceName = 'Nails\GeoCode\Interfaces\Driver';
        if (!in_array($sInterfaceName, class_implements($sDriverClass))) {

            throw new GeoCodeDriverException(
                '"' . $sDriverClass . '" must implement ' . $sInterfaceName,
                2
            );
        }

        $this->oDriver = _NAILS_GET_DRIVER_INSTANCE($oDriver);
    }

    // --------------------------------------------------------------------------

    /**
     * Return all information about a given address
     * @param string $sAddress The address to get details for
     * @return \stdClass
     */
    public function lookup($sAddress = '')
    {
        $sAddress = trim($sAddress);
        $oCache   = $this->getCache($sAddress);

        if (!empty($oCache)) {
            return $oCache;
        }

        $oAddress = $this->oDriver->lookup($sAddress);

        if (!($oAddress instanceof \Nails\GeoCode\Result\LatLng)) {

            throw new GeoCodeException(
                'Geo Code Driver did not return a \Nails\GeoCode\Result\LatLng result',
                3
            );
        }

        $this->setCache($sAddress, $oAddress);

        return $oAddress;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the address property of a lookup
     * @param string $sAddress The address to look up
     * @return string|null
     */
    public function address($sAddress)
    {
        return $this->lookup($sAddress)->getAddress();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the latLng property of a lookup
     * @param string $sAddress The address to look up
     * @return string|null
     */
    public function latLng($sAddress = '')
    {
        return $this->lookup($sAddress)->getLatLng();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat property of a lookup
     * @param string $sAddress The address to look up
     * @return string|null
     */
    public function lat($sAddress = '')
    {
        return $this->lookup($sAddress)->getLat();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lng property of a lookup
     * @param string $sAddress The address to look up
     * @return string|null
     */
    public function lng($sAddress = '')
    {
        return $this->lookup($sAddress)->getLng();
    }
}
