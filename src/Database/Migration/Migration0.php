<?php

/**
 * Migration:   0
 * Started:     28/12/2015
 * Finalised:   28/12/2015
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    Database Migration
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoCode\Database\Migration;

use Nails\Common\Console\Migrate\Base;

class Migration0 extends Base
{
    /**
     * Execute the migration
     * @return Void
     */
    public function execute()
    {
        $this->query("
            CREATE TABLE `{{NAILS_DB_PREFIX}}geocode_cache` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `address` varchar(255) NOT NULL DEFAULT '',
                `latlng` point NOT NULL,
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
