<?php
namespace Tests;

use Cubex\Testing\CubexTestCase;

class ProjectTest extends CubexTestCase
{
  public function testProjectIsCubexKernel()
  {
    $this->assertInstanceOf(
      '\Cubex\Kernel\CubexKernel',
      $this->getCubex()->make('\Cubex\Kernel\CubexKernel')
    );
  }

  public function testDefaultAction()
  {
    $this->getResponse('/');
    $this->assertResponseContains('Welcome to Cubex');
    $this->assertResponseOk();
    $this->assertReturnsCubexResponse();
  }

  public function testHello()
  {
    $this->getResponse('/hello');
    $this->assertResponseContains('Hello World');
  }

  public function testHi()
  {
    $this->getResponse('/hi');
    $this->assertRedirectedTo('/hello/world');
  }
}
