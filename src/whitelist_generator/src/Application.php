<?php

namespace Manul\WhiteList;

use ErrorException;
use \Manul\WhiteList\Command\Build;
use Symfony\Component\Console\Application as Base;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 *
 * @package Manul\WhiteList
 */
class Application extends Base
{
    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * @override
     */
    public function __construct($name = 'WhiteList')
    {
        // convert errors to exceptions
        set_error_handler(
            function ($code, $message, $file, $line) {
                if (error_reporting() & $code) {
                    throw new ErrorException($message, 0, $code, $file, $line);
                }
                // @codeCoverageIgnoreStart
            }
        // @codeCoverageIgnoreEnd
        );

        parent::__construct($name, $this->version);
    }

    /**
     * @override
     */
    public function run(
        InputInterface $input = null,
        OutputInterface $output = null
    ) {
        $output = $output ? : new ConsoleOutput();

        $output->getFormatter()->setStyle(
            'error',
            new OutputFormatterStyle('red')
        );

        $output->getFormatter()->setStyle(
            'question',
            new OutputFormatterStyle('cyan')
        );

        return parent::run($input, $output);
    }

    /**
     * @override
     */
    protected function getDefaultCommands()
    {
        $commands   = parent::getDefaultCommands();
        $commands[] = new Build();

        return $commands;
    }
}