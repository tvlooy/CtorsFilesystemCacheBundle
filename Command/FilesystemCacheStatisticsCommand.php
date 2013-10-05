<?php

namespace Ctors\FilesystemCacheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption;
use Lurker\Event\FilesystemEvent,
    Lurker\ResourceWatcher;

class FilesystemCacheStatisticsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('ctors:filesystemcache:stats')
             ->setDescription('Show the filesystem cache statistics')
             ->addOption(
                 'watch',
                 'w',
                 InputOption::VALUE_NONE,
                 'Watch filesystem cache resources'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $printResources = function ($output) {
            $cacheStats = $this->getContainer()->get('ctors.cache')->getStats();
            $date = new \DateTime();

            $output->writeln('Filesystem cache statistics (<info>' . $date->format('d/m/Y H:i:s') . '</info>)');
            $output->writeln('  - number of objects <info>' . $cacheStats['objects'] . '</info>');
            $output->writeln('  - disk usage <info>' . $cacheStats['size'] . '</info>');
        };

        $printResources($output);

        if ($input->getOption('watch')) {
            $watcher = new ResourceWatcher();
            $watcher->track(
                'ctors.cache',
                $this->getContainer()->getParameter('kernel.cache_dir') .
                   $this->getContainer()->getParameter('ctors.cache_dir')
            );

            $watcher->addListener('ctors.cache', function (FilesystemEvent $event) use ($printResources, $output) {
                echo $printResources($output);
            });

            $watcher->start();
        }
    }
}
