<?php

/**
 * This service manages the GeoCoding drivers
 *
 * @package    Nails
 * @subpackage module-geo-code
 * @category   Service
 * @author     Nails Dev Team
 */

namespace Nails\GeoCode\Service;

use Nails\Common\Model\BaseDriver;
use Nails\GeoCode\Constants;

/**
 * Class Driver
 *
 * @package Nails\GeoCode\Service
 */
class Driver extends BaseDriver
{
    protected $sModule         = Constants::MODULE_SLUG;
    protected $bEnableMultiple = false;
}
