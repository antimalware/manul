<?php

namespace Manul\WhiteList\Command;

use Manul\WhiteList\WhiteList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Build
 *
 * @package Manul\WhiteList\Command
 */
class Build extends Command
{
    /**
     * @override
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('build');
        $this->setDescription('Builds a new whitelist.');
        $this->setHelp(
            <<<HELP
The <info>%command.name%</info> command will build a new whitelist based on a variety of settings.
<comment>
whitelist build --cms wordpress --cmsVersion=3.9 --path=/path/to/wordpress
</comment>
HELP
        );

        $this->addOption(
            'cms',
            null,
            InputOption::VALUE_REQUIRED,
            'The CMS/Framework name.'
        );

        $this->addOption(
            'cmsVersion',
            null,
            InputOption::VALUE_REQUIRED,
            'The CMS/Framework version.'
        );

        $this->addOption(
            'path',
            null,
            InputOption::VALUE_REQUIRED,
            'The path to the files.'
        );

        $this->addOption(
            'minify',
            null,
            InputOption::VALUE_OPTIONAL,
            'To minify the output file.',
            true
        );

        $this->addOption(
            'formatVersion',
            null,
            InputOption::VALUE_OPTIONAL,
            'The output file format version.',
            '0.1'
        );
    }

    /**
     * @override
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmsName       = $input->getOption('cms');
        $cmsVersion    = $input->getOption('cmsVersion');
        $path          = $input->getOption('path');
        $minify        = $input->getOption('minify');
        $formatVersion = $input->getOption('formatVersion');

        $output->writeln('Building...');

        try {
            WhiteList::create()
                ->setCmsName($cmsName)
                ->setCmsVersion($cmsVersion)
                ->setPath($path)
                ->setMinify($minify)
                ->setFormatVersion($formatVersion)
                ->build();
        } catch (\ErrorException $e) {
            $output->writeln(sprintf('<error>%s<error>', $e->getMessage()));
        }

        $output->writeln('<info>Done.<info>');
    }
}