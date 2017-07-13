<?php
class TestConfigProviderTest extends ConfigProviderBaseTest
{
  public function getConfigProvider()
  {
    return new \Packaged\Config\Provider\Test\TestConfigProvider();
  }
}
