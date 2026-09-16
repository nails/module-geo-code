<?php

namespace Nails\GeoCode\Housekeeping;

use Nails\Common\Service\Database;
use Nails\Factory;
use Nails\GeoCode\Service\GeoCode;
use Nails\Housekeeping\Routine\Base;
use Nails\Housekeeping\Routine\Context;
use Nails\Housekeeping\Routine\Result;

class Cache extends Base
{
    const LABEL           = 'Geo-code cache';
    const DESCRIPTION     = 'Deletes Geo-code cache rows older than GEO_CODE_CACHE_PERIOD';
    const CRON_EXPRESSION = '@hourly';

    public function execute(Context $oContext): Result
    {
        /** @var Database $oDb */
        $oDb        = Factory::service('Database');
        $sTable     = GeoCode::DB_CACHE_TABLE;
        $iPeriod    = GeoCode::cachePeriodSeconds();
        $sCutOff    = GeoCode::cacheCutOff();
        $iBatchSize = 200;
        $iProcessed = 0;
        $iLastId    = 0;

        $oContext
            ->writeln(sprintf(
                'Deleting from <comment>%s</comment> older than <comment>%d</comment> seconds',
                $sTable,
                $iPeriod
            ))
            ->log(sprintf(
                'TABLE %s period_seconds=%d cut_off=%s batch_size=%d dry_run=%s',
                $sTable,
                $iPeriod,
                $sCutOff,
                $iBatchSize,
                $oContext->isDryRun() ? 'true' : 'false'
            ));

        while (true) {
            $oDb->select('id, address, created');
            $oDb->where('created <', $sCutOff);
            $oDb->where('id >', $iLastId);
            $oDb->order_by('id', 'asc');
            $oDb->limit($iBatchSize);
            $aRows = $oDb->get($sTable)->result();

            if (empty($aRows)) {
                break;
            }

            $aIds = [];
            foreach ($aRows as $oRow) {
                $iId     = (int) $oRow->id;
                $iLastId = $iId;
                $aIds[]  = $iId;
                $sAudit  = sprintf(
                    'id=%d address=%s created=%s',
                    $iId,
                    str_replace(["\n", "\r"], ' ', (string) $oRow->address),
                    (string) $oRow->created
                );
                $oContext
                    ->log('DELETE ' . $sAudit)
                    ->writeln(' ↳ ' . $sAudit);
            }

            if (!$oContext->isDryRun()) {
                $oDb->where_in('id', $aIds);
                $oDb->delete($sTable);
            }

            $iProcessed += count($aIds);
        }

        $oContext->writeln(sprintf(
            '<comment>%s</comment> %s',
            number_format($iProcessed),
            $oContext->isDryRun() ? 'would be deleted' : 'deleted'
        ));

        return Result::ok($iProcessed);
    }
}
