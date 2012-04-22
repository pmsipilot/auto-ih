<?php
require_once '../vendor/.composer/autoload.php';

use Symfony\Component\Finder\Finder;


$app = new Silex\Application();

$config = new Pimple();
$config['working_dir']  = '/media/autoih_worker';
$config['incoming_dir'] = '/media/autoih_worker/incoming';


$app['config'] = $config;

$app['debug'] = true;

$app->post('/genrsa/2012/send', function () use ($app) {

  if (!is_readable($app['config']['incoming_dir']))
  {
    mkdir($app['config']['incoming_dir']);
  }

  $id  = md5(microtime());

  $dir = $app['config']['incoming_dir'] . DIRECTORY_SEPARATOR . $id;
  mkdir($dir);

  $status  = 0;
  $message = 'OK';
  $content = array();

  try
  {
    $file = $app['request']->files->get('rss');
    if (null === $file)
    {
      throw new RuntimeException('Fichier RSS manquant', 1);
    }
    move_uploaded_file($file->getRealPath(), $dir . DIRECTORY_SEPARATOR .  'rss');

    $file = $app['request']->files->get('autorisations');
    if (null === $file)
    {
      throw new RuntimeException('Fichier autorisations manquant', 2);
    }
    move_uploaded_file($file->getRealPath(), $dir . DIRECTORY_SEPARATOR .  'autorisations');

    $file = $app['request']->files->get('anohosp');
    if (null === $file)
    {
      throw new RuntimeException('Fichier anohosp manquant', 3);
    }
    move_uploaded_file($file->getRealPath(), $dir . DIRECTORY_SEPARATOR .  'anohosp');
    $content = array('id' => $id);

    file_put_contents($dir . DIRECTORY_SEPARATOR . 'ok', '');
  }
  catch (Exception $e)
  {
    rmdir($dir);
    $status = $e->getCode();
    $message = $e->getMessage();
  }

  $infos = array('status' => $status, 'message' => $message, 'content' => $content);
  return json_encode($infos);
});

$app->get('/genrsa/2012/{id}/status', function ($id) use ($app) {
  $status  = 0;
  $message = 'OK';
  $content = array();

  $folders = array(
    'ok'       => 'SUCCESS',
    'incoming' => 'WAITING',
    'current'  => 'RUNNING',
    'not_ok'   => 'ERROR',
  );
  $genrsaStatus  = null;
  foreach (array_keys($folders) as $folder)
  {
    $dir = $app['config']['working_dir']  . '/' .$folder . '/' . $id;
    if (is_readable($dir))
    {
      $genrsaStatus = $folders[$folder];
    }
  }
  $content['status'] = $genrsaStatus;

  $infos = array('status' => $status, 'message' => $message, 'content' => $content);
  return json_encode($infos);
});

$app->get('/genrsa/2012/{id}/file/{type}', function ($id) use ($app, $config) {
  $status  = 0;
  $message = 'OK';
  $content = array();

  $okDir   =  $app['config']['working_dir'] . '/ok/' . $id;

  $finder = new Finder();
  $finder->files()->name('*.zip')->in($okDir);
  if (count($finder) == 1)
  {
    $files = array_values(iterator_to_array($finder));
    return file_get_contents($files[0]);
  }

  $infos = array('status' => $status, 'message' => $message, 'content' => $content);
  return json_encode($infos);
});


$app->run();

