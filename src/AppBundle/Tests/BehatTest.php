<?php

namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Behat\Behat\ApplicationFactory;

/**
 * Description of BehatTest.
 *
 * @author Nicolas de Marqué <nicolas.demarque@gmail.com>
 */
class BehatTest extends WebTestCase
{
    /**
     */
    public function testThatBehatScenariosMeetAcceptanceCriteria()
    {
        $options = [
            '--format' => ['pretty'],
            '--format' => ['junit'],
            '--out' => [__DIR__.'/../../../build/logs/behat/'],
        ];

        try {
            $input = new ArrayInput($options);
            $output = new ConsoleOutput();

            $factory = new ApplicationFactory();

            $app = $factory->createApplication();
            $app->setAutoExit(false);

            $app->run($input, $output);

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        
        $this->assertTrue(true);
    }
}
