<?php

namespace PHPGithook\PHPUnit;

use PHPGithookInterface\Hooks\PreCommitInterface;
use PHPGithookInterface\ParameterBagInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PreCommit extends PHPUnitRunner implements PreCommitInterface
{
    /**
     * This hook is called before obtaining the proposed commit message.
     *
     * Returning false will abort the commit.
     *
     * It is used to check the commit itself (rather than the message).
     *
     * @param InputInterface        $input
     * @param OutputInterface       $output
     * @param ParameterBagInterface $configuration
     *
     * @return bool
     */
    public function preCommit(InputInterface $input, OutputInterface $output, ParameterBagInterface $configuration): bool
    {
        if ($configuration->get('precommit')) {
            return $this->execute($output, $configuration);
        }

        return true;
    }
}
