<?php

/**
 * This class abstracts access to the GeoCode service
 *
 * @package    Nails
 * @subpackage module-geo-code
 * @category   Service
 * @author     Nails Dev Team
 */

namespace Nails\GeoCode\Service;

use Nails\Components;
use Nails\Factory;
use Nails\GeoCode\Constants;
use Nails\GeoCode\Interfaces;
use Nails\GeoCode\Exception\GeoCodeException;
use Nails\GeoCode\Exception\GeoCodeDriverException;
use Nails\GeoCode\Result\LatLng;

/**
 * Class GeoCode
 *
 * @package Nails\GeoCode\Service
 */
class GeoCode
{
    use \Nails\Common\Traits\Caching;

    // --------------------------------------------------------------------------

    /** @var Interfaces\Driver */
    protected $oDriver;

    // --------------------------------------------------------------------------

    /**
     * The name of the table to store cached results
     */
    const DB_CACHE_TABLE = NAILS_DB_PREFIX . 'geocode_cache';

    // --------------------------------------------------------------------------

    /**
     * The default driver to use if none is specified
     */
    const DEFAULT_DRIVER = 'nails/driver-geo-code-google';

    // --------------------------------------------------------------------------

    /**
     * Construct the Library, test that the driver is valid
     *
     * @throws GeoCodeException
     */
    public function __construct()
    {
        //  Load the driver
        // @todo: build a settings interface for setting and configuring the driver.
        $sSlug    = defined('APP_GEO_CODE_DRIVER') ? strtolower(APP_GEO_CODE_DRIVER) : self::DEFAULT_DRIVER;
        $aDrivers = Components::drivers(Constants::MODULE_SLUG);
        $oDriver  = null;

        for ($i = 0; $i < count($aDrivers); $i++) {
            if ($aDrivers[$i]->slug == $sSlug) {
                $oDriver = $aDrivers[$i];
                break;
            }
        }

        if (empty($oDriver)) {
            throw new GeoCodeDriverException('"' . $sSlug . '" is not a valid Geo-Code driver');
        }

        $sDriverClass = $oDriver->data->namespace . $oDriver->data->class;

        if (!classImplements($sDriverClass, Interfaces\Driver::class)) {
            throw new GeoCodeDriverException(sprintf(
                '"%s" must implement %s',
                $sDriverClass,
                Interfaces\Driver::class
            ));
        }

        /** @var Interfaces\Driver $oInstance */
        $oInstance     = Components::getDriverInstance($oDriver);
        $this->oDriver = $oInstance;
    }

    // --------------------------------------------------------------------------

    /**
     * Return all information about a given address
     *
     * @param string $sAddress The address to get details for
     *
     * @return null|LatLng
     */
    public function lookup(string $sAddress = ''): ?LatLng
    {
        $sAddress = trim($sAddress);

        if (empty($sAddress)) {
            return null;
        }

        //  Check caches; local cache first followed by DB cache
        $oCache = $this->getCache($sAddress);

        if (!empty($oCache)) {
            return $oCache;
        }

        /** @var \Nails\Common\Service\Database $oDb */
        $oDb = Factory::service('Database');
        if ($oDb->isMySQL80()) {
            $oDb->select('ST_X(latlng) lat, ST_Y(latlng) lng');
        } else {
            $oDb->select('X(latlng) lat, Y(latlng) lng');
        }
        $oDb->where('address', $sAddress);
        $oDb->limit(1);
        $oResult = $oDb->get(self::DB_CACHE_TABLE)->row();

        if (!empty($oResult)) {

            /** @var LatLng $oLatLng */
            $oLatLng = Factory::factory('LatLng', Constants::MODULE_SLUG);
            $oLatLng
                ->setAddress($sAddress)
                ->setLat($oResult->lat)
                ->setLng($oResult->lng);

        } else {

            $oLatLng = $this->oDriver->lookup($sAddress);

            if (!($oLatLng instanceof LatLng)) {
                throw new GeoCodeException(sprintf(
                    'Geo Code Driver did not return a %s result',
                    LatLng::class
                ));
            }

            $sLat = $oLatLng->getLat();
            $sLng = $oLatLng->getLng();

            //  Save to the DB Cache
            if (!empty($sLat) && !empty($sLng)) {
                $oDb->set('address', $sAddress);
                $oDb->set('latlng', 'POINT(' . $sLat . ', ' . $sLng . ')', false);
                $oDb->set('created', 'NOW()', false);
                $oDb->insert(self::DB_CACHE_TABLE);
            }
        }

        $this->setCache($sAddress, $oLatLng);

        return $oLatLng;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the address property of a lookup
     *
     * @param string $sAddress The address to look up
     *
     * @return string|null
     */
    public function address(string $sAddress): ?string
    {
        $oResult = $this->lookup($sAddress);
        return $oResult ? $oResult->getAddress() : null;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the latLng property of a lookup
     *
     * @param string $sAddress The address to look up
     *
     * @return \stdClass|null
     */
    public function latLng(string $sAddress = ''): ?\stdClass
    {
        $oResult = $this->lookup($sAddress);
        return $oResult ? $oResult->getLatLng() : null;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat property of a lookup
     *
     * @param string $sAddress The address to look up
     *
     * @return string|null
     */
    public function lat(string $sAddress = ''): ?string
    {
        $oResult = $this->lookup($sAddress);
        return $oResult ? $oResult->getLat() : null;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lng property of a lookup
     *
     * @param string $sAddress The address to look up
     *
     * @return string|null
     */
    public function lng(string $sAddress = ''): ?string
    {
        $oResult = $this->lookup($sAddress);
        return $oResult ? $oResult->getLng() : null;
    }
}
