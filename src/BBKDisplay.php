<?php
namespace AmiSMB\BBKDisplay;

use Cubex\Http\Response;
use Cubex\Kernel\CubexKernel;

/**
 * This project is the default entry point for you application, as specified
 * by conf/defaults.ini [kernel]default=
 */
class BBKDisplay extends CubexKernel
{
  public function getRoutes()
  {
    return [
      'hello/world' => 'hello', //Run the renderHello method
      'google'      => 'http://www.google.com', //Redirect to url
      'hi'          => '#@/hello/world', //Redirect to hello/world
    ];
  }

  /**
   * Default action will be executed if no alternate route can be found
   *
   * @return Response|null
   */
  public function defaultAction()
  {
    return new Response("Welcome to Cubex");
  }

  /**
   * If no valid response is returned, cubex will grab all echoed content
   * produced within your method call
   */
  public function renderHello()
  {
    $hello = null;
    echo "Hello World" . $hello->txt;
  }
}
