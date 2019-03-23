<?php

/**
 * This service manages the GeoCoding drivers
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    Service
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoCode\Service;

use Nails\Common\Model\BaseDriver;

class Driver extends BaseDriver
{
    protected $sModule         = 'nails/module-geo-code';
    protected $bEnableMultiple = false;
}
