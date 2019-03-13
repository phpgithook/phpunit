<?php

namespace PHPGithook\PHPUnit;

use PHPGithookInterface\ParameterBagInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class PHPUnitRunner
{
    protected function execute(OutputInterface $output, ParameterBagInterface $parameters): bool
    {
        // @codeCoverageIgnoreStart
        if (!\is_string($parameters->get('bin', ''))) {
            throw new \RuntimeException('The parameter "bin" is not a string');
        }

        if (!\is_string($parameters->get('configurationFile', ''))) {
            throw new \RuntimeException('The parameter "configurationFile" is not a string');
        }
        // @codeCoverageIgnoreEnd

        $command[] = trim($parameters->get('bin', ''));
        if ($parameters->get('configurationFile', '')) {
            $command[] = '-c';
            $command[] = trim($parameters->get('configurationFile', ''));
        }

        $process = new Process($command);
        $process->run();

        $output->writeln($process->getOutput());
        $output->writeln("<error>{$process->getErrorOutput()}</error>");

        return $process->isSuccessful();
    }
}
