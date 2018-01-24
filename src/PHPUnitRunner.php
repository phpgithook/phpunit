<?php

namespace PHPGithook\PHPUnit;

use PHPGithookInterface\ParameterBagInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class PHPUnitRunner
{
    protected function execute(OutputInterface $output, ParameterBagInterface $parameters): bool
    {
        $exec = sprintf(
            '%s/phpunit %s %s',
            $parameters->get('BINDIR'),
            $parameters->has('configurationFile') ? $parameters->get('configurationFile') : '',
            $parameters->get('WORKINGDIR')
        );

        ob_start();
        `$exec`;

        return $output->writeln(ob_get_flush());
    }
}
