<?php

namespace PHPGithook\PHPUnitTest;


use PHPGithook\PHPUnit\PrePush;
use PHPGithook\PHPUnit\Setup;
use PHPGithookInterface\ParameterBagInterface;
use PHPGithookInterface\Parameters;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\BufferedOutput;

class PrePushTest extends TestCase
{

    /**
     * @var PrePush
     */
    private $hook;

    /**
     * @var BufferedOutput
     */
    private $output;

    /**
     * @test
     */
    public function can_pre_commit(): void
    {
        $destinationName = 'name';
        $destinationLoc = 'loc';

        $input = new ArgvInput();

        $return = $this->hook->prePush(
            $destinationName,
            $destinationLoc,
            $input,
            $this->output,
            $this->getConfig(__DIR__.'/../greenTest')
        );
        $this->assertTrue($return);
    }

    /**
     * @test
     */
    public function will_return_true_if_pre_commit_is_turned_off(): void
    {
        $destinationName = 'name';
        $destinationLoc = 'loc';

        $input = new ArgvInput();
        $parameters = new Parameters(['prepush' => false]);
        $return = $this->hook->prePush(
            $destinationName,
            $destinationLoc,
            $input,
            $this->output,
            $this->getConfig(__DIR__.'/../greenTest', $parameters)
        );
        $this->assertTrue($return);
    }

    /**
     * @test
     */
    public function will_return_false_if_unittests_not_successful(): void
    {
        $destinationName = 'name';
        $destinationLoc = 'loc';

        $input = new ArgvInput();

        $return = $this->hook->prePush(
            $destinationName,
            $destinationLoc,
            $input,
            $this->output,
            $this->getConfig(__DIR__.'/../redTest')
        );
        $this->assertFalse($return);
    }

    protected function setUp(): void
    {
        $this->output = new BufferedOutput();
        $this->hook = new PrePush();
    }

    private function getInput(string $dir): ArrayInput
    {
        $inputDefinition = new InputDefinition();
        $inputDefinition->addArgument(new InputArgument('directory'));
        $input = new ArrayInput(['directory' => $dir], $inputDefinition);
        $input->setInteractive(false);

        return $input;
    }

    private function getConfig(string $dir, ParameterBagInterface $parameters = null): ParameterBagInterface
    {
        return new Parameters((new Setup())->createConfiguration($this->getInput($dir), $this->output, $parameters));
    }

}
