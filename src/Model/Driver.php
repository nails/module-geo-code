<?php

/**
 * This model manages the GeoCoding drivers
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    Model
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoCode\Model;

use Nails\Common\Model\BaseDriver;

class Driver extends BaseDriver
{
    protected $sModule         = 'nailsapp/module-geo-code';
    protected $bEnableMultiple = false;
}
