<?php

use AmiSMB\BBKDisplay\Helpers\LogHelper;

//Defining PHP_START will allow cubex to add an execution time header
define('PHP_START', microtime(true));

//Include the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Convert errors into exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
  if((error_reporting() & $errno) && !($errno & E_NOTICE))
  {
    $errfile = str_replace(dirname(__DIR__), '', $errfile);
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }
}

// Log exceptions
function exception_handler($e)
{
  $log = new LogHelper(LogHelper::CONSOLE, 'BBK Display');
  $log->exception($e);
}

function shutdown()
{
  $log = new LogHelper(LogHelper::STDERR, 'BBK Display');
  $error = error_get_last();
  if($error['type'] === E_ERROR)
  {
    $log->error($error['message']);
  }
}

set_error_handler("exception_error_handler");

set_exception_handler("exception_handler");

register_shutdown_function("shutdown");

//Create an instance of cubex, with the web root defined
$app = new \Cubex\Cubex(__DIR__);
$app->boot();

//Create and configure a new dispatcher
$dispatcher = new \Packaged\Dispatch\Dispatch(
  $app,
  $app->getConfiguration()->getSection('dispatch')
);

//Set the correct working directory for dispatcher
$dispatcher->setBaseDirectory(dirname(__DIR__));

//Load in the cache of file hashes to improve performance of dispatched assets
$fileHash = 'conf/dispatch.filehash.ini';
if(file_exists($fileHash))
{
  $hashTable = parse_ini_file($fileHash, false);
  if(!empty($hashTable))
  {
    $dispatcher->setFileHashTable($hashTable);
  }
}

//Inject dispatch to handle assets
$app = (new \Stack\Builder())->push([$dispatcher, 'prepare'])->resolve($app);

//Create a request object
$request = \Cubex\Http\Request::createFromGlobals();

//Tell Cubex to handle the request, and do its magic
$response = $app->handle($request);

//Send the generated response to the user
$response->send();

//Shutdown Cubex
$app->terminate($request, $response);
