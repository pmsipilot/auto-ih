<?php
namespace Autoih\Config;

use Symfony\Component\Yaml\Parser;


class Config extends \Pimple
{

  /**
   * getAuthorizedKeys
   *
   * @return array
   */
  public function getAuthorizedKeys()
  {
    return array(
      'genrsa_working_dir',
      'epmsi_working_dir',
      'paprica_working_dir',
      'sahi_userdata',
      'sahi_host',
      'epmsi_user',
      'epmsi_password',
      'worker_genrsa_dir',
      'worker_epmsi_dir',
      'worker_paprica_dir',
      'paprica_2012_path',
      'paprica_2012_log_path',
      'genrsa_2012_path',
      'genrsa_2011_path',
      'finess',
      'desktop_path',
    );
  }

  /**
   * load
   *
   * @param string $file
   *
   * @return $this
   */
  public function load($file)
  {
    $parser = new Parser();
    if (!is_file($file))
    {
      throw new \RuntimeException(sprintf('Configuration file "%s" not found', $file));
    }
    $config = $parser->parse(file_get_contents($file));
    foreach ($this->getAuthorizedKeys() as $key)
    {
      if (!isset($config[$key]))
      {
        continue;
      }
      $this[$key] = $config[$key];
    }
    return $this;
  }

}


