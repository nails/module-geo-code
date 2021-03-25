<?php

namespace Nails\GeoCode\Settings;

use Nails\GeoCode\Service\Driver;
use Nails\Common\Helper\Form;
use Nails\Common\Interfaces;
use Nails\Common\Service\FormValidation;
use Nails\Components\Setting;
use Nails\GeoCode\Constants;
use Nails\Factory;

/**
 * Class General
 *
 * @package Nails\GeoCode\Settings
 */
class General implements Interfaces\Component\Settings
{
    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return 'Geo-IP';
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        /** @var Driver $oDriverService */
        $oDriverService = Factory::service('Driver', Constants::MODULE_SLUG);

        /** @var Setting $oDriver */
        $oDriver = Factory::factory('ComponentSetting');
        $oDriver
            ->setKey($oDriverService->getSettingKey())
            ->setType($oDriverService->isMultiple()
                ? Form::FIELD_DROPDOWN_MULTIPLE
                : Form::FIELD_DROPDOWN
            )
            ->setLabel('Driver')
            ->setFieldset('Driver')
            ->setClass('select2')
            ->setOptions(['' => 'No Driver Selected'] + $oDriverService->getAllFlat())
            ->setValidation([
                FormValidation::RULE_REQUIRED,
            ]);

        return [
            $oDriver,
        ];
    }
}
