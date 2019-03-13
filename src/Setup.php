<?php

namespace PHPGithook\PHPUnit;

use PHPGithookInterface\ParameterBagInterface;
use PHPGithookInterface\SetupInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Setup implements SetupInterface
{
    /**
     * The configuration to the module
     * array( key1 => value1, key2 => value2 ).
     *
     * This configuration will be parsed to each of the hooks.
     *
     * You can ask the user for configuration options or just set the configuration
     *
     * @param InputInterface             $input
     * @param OutputInterface            $output
     * @param ParameterBagInterface|null $parameters
     *
     * @return array
     */
    public function createConfiguration(InputInterface $input, OutputInterface $output, ParameterBagInterface $parameters = null): array
    {
        if (!$input->hasArgument('directory') || !\is_string($input->getArgument('directory'))) {
            throw new \RuntimeException('Directory argument should be a key');
        }

        $io = new SymfonyStyle($input, $output);

        $configuration = [];
        $configuration['precommit'] = $parameters ? $parameters->get('precommit', '1') : '1';
        $configuration['prepush'] = $parameters ? $parameters->get('prepush', '1') : '1';

        $configFile = sprintf('%s/phpunit.xml', $input->getArgument('directory'));
        if (!file_exists($configFile)) {
            $configFile = sprintf('%s/phpunit.xml.dist', $input->getArgument('directory'));
            if (!file_exists($configFile)) {
                $configFile = '';
            }
        }
        $configuration['configurationFile'] = $parameters ? $parameters->get('configurationFile', $configFile) : $configFile;

        $binfile = sprintf('%s/vendor/bin/phpunit', $input->getArgument('directory'));
        if (!file_exists($binfile)) {
            $binfile = sprintf('%s/bin/phpunit', $input->getArgument('directory'));
            if (!file_exists($binfile)) {
                $binfile = '';
            }
        }
        $configuration['bin'] = $parameters ? $parameters->get('bin', $binfile) : $binfile;

        $configuration['bin'] = $io->ask('PHPUnit bin file', $configuration['bin']);
        $configuration['precommit'] = $io->ask('Run PHPUnit before creating the commit', $configuration['precommit']);
        $configuration['prepush'] = $io->ask('Run PHPUnit before pushing', $configuration['prepush']);
        $configuration['configurationFile'] = $io->ask('Path from your project to phpunit configuration file', $configuration['configurationFile']);

        return $configuration;
    }

    /**
     * The name that will be displayed to the user, when using modify/enable/disable module.
     *
     * @return string
     */
    public function getVisualName(): string
    {
        return 'PHP Unit';
    }

    /**
     * Description of what your module does.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'PHP Unit runner';
    }

    /**
     * The name to the configuration.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'phpunit';
    }

    /**
     * Classes which should be loaded which contains your git hooks.
     *
     * @return array
     */
    public function getClasses(): array
    {
        return [
            PreCommit::class,
            PrePush::class,
        ];
    }
}
