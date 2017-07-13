<?php
use Packaged\Config\Provider\ConfigSection;
use Packaged\Config\ConfigurableTrait;

class ConfigurableTraitTest extends \PHPUnit_Framework_TestCase
{
  public function testTrait()
  {
    $config = new ConfigSection('abstract', ['name' => 'test']);
    $mock   = new MockConfigurableTrait();
    $mock->configure($config);
    $this->assertSame($config, $mock->config());
  }

  public function testDefaultConfig()
  {
    $mock = new MockConfigurableTrait();
    $this->assertEmpty($mock->config()->getItems());
  }
}

class MockConfigurableTrait
{
  use ConfigurableTrait;

  /**
   * @return \Packaged\Config\ConfigSectionInterface
   */
  public function config()
  {
    return $this->_config();
  }
}
