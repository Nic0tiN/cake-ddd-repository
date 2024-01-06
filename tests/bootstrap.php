<?php

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\Fixture\SchemaLoader;

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
}

ConnectionManager::setConfig('test', [
    'host' => 'localhost',
    //'port' => 'non_standard_port_number',
    'username' => 'my_app',
    'password' => 'secret',
    'database' => 'test_myapp',
    //'schema' => 'myapp',
    'url' => 'sqlite://127.0.0.1/tests.sqlite',
    'log' => false,
]);
ConnectionManager::alias('test', 'default');
$loader = new SchemaLoader();
$loader->loadInternalFile(ROOT . '/tests/config/schema.php');

if (!Configure::check('App.namespace')) {
    Configure::write('App.namespace', 'CakeDDD\\Repository\\Test');
}
if (!Configure::check('App.encoding')) {
    Configure::write('App.encoding', 'UTF-8');
}
