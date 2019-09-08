<?php

namespace Basilicom\ThumbnailBundle\Command;

use Basilicom\ThumbnailBundle\Message\ThumbnailJob;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ThumbnailCommand extends AbstractCommand
{

    private $bus;

    public function __construct(string $name = null, MessageBusInterface $bus)
    {
        parent::__construct($name);
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this->setName('basilicom:thumbnail')
            ->setDescription('Generates missing Thumbnails')
            ->setHelp('this command generates thumbnails');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @example bin/console basilicom:thumbnail
     *
     * - is the input file in expected format:
     *     does it have a column 'key'
     *     are there any fields that pimcore doesn't know about
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bus->dispatch(new ThumbnailJob(1));
        $output->writeln('thumbnail creation');
    }
}
