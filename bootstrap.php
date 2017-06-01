<?php
namespace ChatWatch;

use Silex\Application;
use \Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use \Doctrine\Common\Annotations\AnnotationRegistry;
use \Silex\Provider\DoctrineServiceProvider;
use ChatWatch\Config;

$loader = require '.' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$app = new Application();
$app['debug'] = true;

$databaseConfigs = array(
    'dbs.options' => array()
);
$conf = (new Config())->getConf('db');

$databaseConfigs['dbs.options']['default'] = array(
    'driver'    => $conf['driver'],
    'host'      => $conf['host'],
    'dbname'    => $conf['dbname'],
    'user'      => $conf['user'],
    'password'  => $conf['password'],
    'charset'   => $conf['charset']
);

$app->register(new DoctrineServiceProvider(), $databaseConfigs);

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'doctrine' . DIRECTORY_SEPARATOR . 'proxies',
    'orm.default_cache' => 'array',
    'orm.auto_generate_proxies' => true,
    'orm.em.options' => array(
        'mappings' => array(
            // Using actual filesystem paths
            array(
                'type' => 'annotation',
                'namespace' => 'ChatWatch\\Domain\\Entities',
                'path' => __DIR__ . DIRECTORY_SEPARATOR . 'Domain' . DIRECTORY_SEPARATOR . 'Entities'
            )
        )
    )
));

AnnotationRegistry::registerLoader(array(
    $loader,
    'loadClass'
));

return $app;
