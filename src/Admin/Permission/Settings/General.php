<?php

namespace Nails\GeoCode\Admin\Permission\Settings;

use Nails\Admin\Interfaces\Permission;

class General implements Permission
{
    public function label(): string
    {
        return 'Can manage Geo-IP settings';
    }

    public function group(): string
    {
        return 'Settings';
    }
}
