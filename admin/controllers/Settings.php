<?php

/**
 * This class registers some handlers for GeoCode settings
 *
 * @package     Nails
 * @subpackage  module-geo-code
 * @category    AdminController
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Admin\GeoCode;

use Nails\Factory;
use Nails\Admin\Helper;
use Nails\Admin\Controller\Base;

class Settings extends Base
{
    /**
     * Announces this controller's navGroups
     * @return stdClass
     */
    public static function announce()
    {
        $oNavGroup = Factory::factory('Nav', 'nails/module-admin');
        $oNavGroup->setLabel('Settings');
        $oNavGroup->setIcon('fa-wrench');

        if (userHasPermission('admin:geocode:settings:*')) {
            $oNavGroup->addAction('Geo Coding');
        }

        return $oNavGroup;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of permissions which can be configured for the user
     * @return array
     */
    public static function permissions(): array
    {
        $permissions           = parent::permissions();
        $permissions['driver'] = 'Can update driver settings';

        return $permissions;
    }

    // --------------------------------------------------------------------------

    /**
     * Manage Email settings
     * @return void
     */
    public function index()
    {
        if (!userHasPermission('admin:geocode:settings:*')) {
            unauthorised();
        }

        $oDb            = Factory::service('Database');
        $oDriverService = Factory::service('Driver', 'nails/module-geo-code');

        //  Process POST
        if ($this->input->post()) {

            //  Settings keys
            $sKeyDriver = $oDriverService->getSettingKey();

            //  Validation
            $oFormValidation = Factory::service('FormValidation');

            $oFormValidation->set_rules($sKeyDriver, '', '');

            if ($oFormValidation->run()) {

                try {

                    $oDb->trans_begin();

                    //  Drivers
                    $oDriverService->saveEnabled($this->input->post($sKeyDriver));

                    $oDb->trans_commit();
                    $this->data['success'] = 'GeoCode settings were saved.';

                } catch (\Exception $e) {

                    $oDb->trans_rollback();
                    $this->data['error'] = 'There was a problem saving settings. ' . $e->getMessage();
                }

            } else {

                $this->data['error'] = lang('fv_there_were_errors');
            }
        }

        // --------------------------------------------------------------------------

        //  Get data
        $this->data['settings']        = appSetting(null, 'nails/module-geo-code', true);
        $this->data['drivers']         = $oDriverService->getAll();
        $this->data['drivers_enabled'] = $oDriverService->getEnabledSlug();

        Helper::loadView('index');
    }
}
