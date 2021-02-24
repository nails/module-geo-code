<?php

use Nails\GeoCode\Constants;

//  Get any additional libraries we'll need
$oInput = \Nails\Factory::service('Input');

?>
<div class="group-invoice settings">
    <?php

    echo form_open();
    $sActiveTab = $oInput->post('active_tab') ?: 'tab-drivers';
    echo '<input type="hidden" name="active_tab" value="' . $sActiveTab . '" id="active-tab">';

    ?>
    <ul class="tabs" data-active-tab-input="#active-tab">
        <?php

        if (userHasPermission('admin:geocode:settings:drivers')) {

            ?>
            <li class="tab">
                <a href="#" data-tab="tab-drivers">Drivers</a>
            </li>
            <?php
        }

        ?>
    </ul>
    <section class="tabs">
        <?php

        if (userHasPermission('admin:geocode:settings:drivers')) {

            ?>
            <div class="tab-page tab-drivers">
                <p class="alert alert-warning">
                    <strong>@todo:</strong> The geocode library should respect these settings
                </p>
                <?=adminHelper(
                    'loadSettingsDriverTable',
                    'Driver',
                    Constants::MODULE_SLUG
                )?>
            </div>
            <?php
        }

        ?>
    </section>
    <p>
        <?=form_submit('submit', lang('action_save_changes'), 'class="btn btn-primary"')?>
    </p>
    <?=form_close()?>
</div>
