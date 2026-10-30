<?php

namespace Nails\GeoCode\FindReplace\Exclusion;

use Nails\FindReplace\Interfaces\Exclusion;

class Package implements Exclusion
{
    public function hidden(): array
    {
        return [
            NAILS_DB_PREFIX . 'geocode_cache',
        ];
    }

    public function readOnly(): array
    {
        return [];
    }
}
