<?php

namespace PHPGithook\PHPUnitTest;


use PHPGithook\PHPUnit\PreCommit;
use PHPGithook\PHPUnit\PrePush;
use PHPGithook\PHPUnit\Setup;
use PHPGithookInterface\Parameters;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\NullOutput;

class SetupTest extends TestCase
{

    /**
     * @var Setup
     */
    private $setup;

    /**
     * @test
     */
    public function can_create_configuration(): void
    {
        $output = new NullOutput();
        $configuration = $this->setup->createConfiguration($this->getInput(__DIR__.'/../greenTest'), $output);
        $this->assertIsArray($configuration);

        $this->assertArrayHasKey('precommit', $configuration);
        $this->assertSame('1', $configuration['precommit']);

        $this->assertArrayHasKey('prepush', $configuration);
        $this->assertSame('1', $configuration['prepush']);

        $this->assertArrayHasKey('configurationFile', $configuration);
        $this->assertStringEndsWith('greenTest/phpunit.xml.dist', $configuration['configurationFile']);

        $this->assertArrayHasKey('bin', $configuration);
        $this->assertStringEndsWith('greenTest/bin/phpunit', $configuration['bin']);

        $output = new NullOutput();
        $configuration = $this->setup->createConfiguration($this->getInput(__DIR__.'/../redTest'), $output);
        $this->assertIsArray($configuration);

        $this->assertArrayHasKey('configurationFile', $configuration);
        $this->assertStringEndsWith('redTest/phpunit.xml', $configuration['configurationFile']);

        $this->assertArrayHasKey('bin', $configuration);
        $this->assertStringEndsWith('redTest/vendor/bin/phpunit', $configuration['bin']);
    }

    /**
     * @test
     */
    public function should_fail_if_directory_is_not_set(): void
    {
        $this->expectException(\RuntimeException::class);

        $output = new NullOutput();
        $this->setup->createConfiguration(new ArrayInput([]), $output);
    }


    /**
     * @test
     */
    public function can_update_configuration(): void
    {
        $output = new NullOutput();

        $parameters = new Parameters(['precommit' => false]);

        $configuration = $this->setup->createConfiguration($this->getInput(__DIR__.'/greenTest'), $output, $parameters);

        $this->assertIsArray($configuration);
        $this->assertArrayHasKey('precommit', $configuration);
        $this->assertSame('', $configuration['precommit']);
    }

    /**
     * @test
     */
    public function can_get_name(): void
    {
        $this->assertSame('phpunit', $this->setup->getName());
    }

    /**
     * @test
     */
    public function can_get_visualname(): void
    {
        $this->assertSame('PHP Unit', $this->setup->getVisualName());
    }

    /**
     * @test
     */
    public function can_get_description(): void
    {
        $this->assertSame('PHP Unit runner', $this->setup->getDescription());
    }

    /**
     * @test
     */
    public function set_hook_classes(): void
    {
        $this->assertIsArray($this->setup->getClasses());
        $this->assertContains(PreCommit::class, $this->setup->getClasses());
        $this->assertContains(PrePush::class, $this->setup->getClasses());
    }

    protected function setUp(): void
    {
        $this->setup = new Setup();
    }

    private function getInput(string $dir): ArrayInput
    {
        $inputDefinition = new InputDefinition();
        $inputDefinition->addArgument(new InputArgument('directory'));
        $input = new ArrayInput(['directory' => $dir], $inputDefinition);
        $input->setInteractive(false);

        return $input;
    }

}
