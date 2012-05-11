<?php
namespace Autoih\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;


class WorkerRunPaprica extends BaseWorker
{

  /**
   * configure
   *
   * @return void
   */
  protected function configure()
  {
    parent::configure();
    $this
      ->setName('worker:run-paprica')
      ->setDescription('Execute ')
    ;
  }

  /**
   * process
   *
   * @param OutputInterface $output
   * @param string          $currentPath
   *
   * @return void
   */
  protected function process(OutputInterface $output, $year, $currentPath)
  {
    $config = $this->getApplication()->getConfig();
    $cmd = sprintf(
      '.\bin\paprica\%s.exe %s %s %s',
      $year,
      escapeshellarg(str_replace('/', '\\', $currentPath)),
      escapeshellarg($config[sprintf('paprica_%s_path', $year)]),
      escapeshellarg($config['finess'])
    );
    exec($cmd);
  }

}