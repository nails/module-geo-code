<?php

namespace Nails\GeoCode\Console\Command\Cache;

use Nails\Components;
use Nails\Console\Command\Base;
use Nails\Factory;
use Nails\GeoCode\Housekeeping\Cache;
use Nails\Housekeeping\Routine\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use housekeeping:run --routine=Nails\GeoCode\Housekeeping\Cache
 */
class Clear extends Base
{
    /**
     * Configures the command
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('geocode:cache:clear')
            ->setDescription('[DEPRECATED] Clears expired items from the Geo-code cache')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Clear the entire cache')
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Log what would be deleted without deleting'
            );
    }

    /**
     * Executes the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $oInput
     * @param \Symfony\Component\Console\Output\OutputInterface $oOutput
     *
     * @return int
     * @throws \Nails\Common\Exception\FactoryException
     */
    protected function execute(InputInterface $oInput, OutputInterface $oOutput)
    {
        parent::execute($oInput, $oOutput);
        $this->banner('Geo-code: Clear Cache (deprecated)');

        if (!Components::exists('nails/module-housekeeping')) {
            $oOutput->writeln('<error>This command now requires nails/module-housekeeping.</error>');
            $oOutput->writeln('Install it with <comment>composer require nails/module-housekeeping</comment>');
            $oOutput->writeln('then run <comment>nails housekeeping:run --routine=' . Cache::class . '</comment>');

            return static::EXIT_CODE_FAILURE;
        }

        $bDryRun = (bool) $oInput->getOption('dry-run');

        if ($oInput->getOption('force')) {
            /** @var \Nails\Housekeeping\Service\Logger $oLogger */
            $oLogger  = Factory::service('Logger', 'nails/module-housekeeping');
            $oContext = new Context($bDryRun, $oLogger, Cache::class, $oOutput);
            $oContext->log('START');
            $oResult = (new Cache())->truncate($oContext);
            $oContext->log(sprintf(
                'SUMMARY processed=%d failed=%d success=%s dry_run=%s',
                $oResult->getProcessed(),
                $oResult->getFailed(),
                $oResult->isSuccess() ? 'true' : 'false',
                $bDryRun ? 'true' : 'false'
            ));
            $oContext->log('FINISH');

            return $oResult->isSuccess() ? static::EXIT_CODE_SUCCESS : static::EXIT_CODE_FAILURE;
        }

        /** @var \Nails\Housekeeping\Service\Orchestrator $oOrchestrator */
        $oOrchestrator = Factory::service('Orchestrator', 'nails/module-housekeeping');
        $oResult       = $oOrchestrator->runRoutine(
            Cache::class,
            $bDryRun,
            true,
            $oOutput
        );

        return $oResult->isSuccess() ? static::EXIT_CODE_SUCCESS : static::EXIT_CODE_FAILURE;
    }
}
