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
            ->setDescription('Generates missing thumbnails by dispatching ThumbnailJob messages')
            ->setHelp('This command generates thumbnails')
            ->addArgument(
                'assetId',
                InputArgument::REQUIRED,
                'The asset ID'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @example bin/console basilicom:thumbnail <assetId>
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $assetId = $input->getArgument('assetId');
        $this->bus->dispatch(new ThumbnailJob((int)$assetId));
        $output->writeln('Dispatched ThumbnailJob for assetId='.$assetId);
    }
}
