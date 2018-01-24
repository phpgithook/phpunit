<?php

namespace PHPGithook\PHPUnit;

use PHPGithookInterface\Hooks\PrePushInterface;
use PHPGithookInterface\ParameterBagInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrePush extends PHPUnitRunner implements PrePushInterface
{
    /**
     * Called prior to a push to a remote.
     *
     * Returning false aborts the push.
     *
     * @param string                $destinationName     Git remote name
     * @param string                $destinationLocation Git remote uri
     * @param InputInterface        $input
     * @param OutputInterface       $output
     * @param ParameterBagInterface $configuration
     *
     * @return bool
     */
    public function prePush(string $destinationName, string $destinationLocation, InputInterface $input, OutputInterface $output, ParameterBagInterface $configuration): bool
    {
        if ($configuration->get('prepush')) {
            return $this->execute($output, $configuration);
        }

        return true;
    }
}
