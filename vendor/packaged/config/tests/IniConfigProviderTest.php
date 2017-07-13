<?php

class IniConfigProviderTest extends ConfigProviderBaseTest
{
  /**
   * @return \Packaged\Config\Provider\Ini\IniConfigProvider
   */
  public function getConfigProvider()
  {
    return new \Packaged\Config\Provider\Ini\IniConfigProvider();
  }

  public function testMissingLoadFile()
  {
    $file = dirname(__DIR__) . '/testData/missing.file';
    $this->setExpectedException(
      "RuntimeException",
      "Config file '$file' could not be found"
    );
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
  }

  public function testInvalidLoadFile()
  {
    $file = dirname(__DIR__) . '/testData/useless.file';
    $this->setExpectedException(
      "RuntimeException",
      "The ini file '$file' is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
  }

  public function testLoadFile()
  {
    $file = dirname(__DIR__) . '/testData/test.ini';
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadFileConstruct()
  {
    $file = dirname(__DIR__) . '/testData/test.ini';
    $provider = new \Packaged\Config\Provider\Ini\IniConfigProvider($file);
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadCorruptString()
  {
    $file = dirname(__DIR__) . '/testData/useless.file';
    $this->setExpectedException(
      "RuntimeException",
      "The ini string passed is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadString(file_get_contents($file));
  }

  public function testLoadString()
  {
    $file = dirname(__DIR__) . '/testData/test.ini';
    $provider = $this->getConfigProvider();
    $provider->loadString(file_get_contents($file));
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadFileEnv()
  {
    putenv('TESTVAR1=fileTestValue1');
    putenv('TESTVAR2=fileTestValue2');

    $file = dirname(__DIR__) . '/testData/envtest.ini';
    $provider = $this->getConfigProvider();
    $provider->loadFile($file, true);

    $this->assertEquals(
      'fileTestValue1',
      $provider->getItem('default', 'var1')
    );
    $this->assertEquals(
      'fileTestValue2',
      $provider->getItem('default', 'var2')
    );
    $this->assertEquals(
      'fileTestValue1-fileTestValue2',
      $provider->getItem('default', 'var1and2')
    );
    $this->assertEquals(
      'defaultValue',
      $provider->getItem('default', 'nonexistentDefault')
    );
    $this->assertEquals(
      '',
      $provider->getItem('default', 'nonexistentNoDefault')
    );
  }

  public function testLoadStringEnv()
  {
    putenv('TESTVAR1=stringTestValue1');
    putenv('TESTVAR2=stringTestValue2');

    $file = dirname(__DIR__) . '/testData/envtest.ini';
    $provider = $this->getConfigProvider();
    $provider->loadString(file_get_contents($file), true);

    $this->assertEquals(
      'stringTestValue1',
      $provider->getItem('default', 'var1')
    );
    $this->assertEquals(
      'stringTestValue2',
      $provider->getItem('default', 'var2')
    );
    $this->assertEquals(
      'stringTestValue1-stringTestValue2',
      $provider->getItem('default', 'var1and2')
    );
    $this->assertEquals(
      'defaultValue',
      $provider->getItem('default', 'nonexistentDefault')
    );
    $this->assertEquals(
      '',
      $provider->getItem('default', 'nonexistentNoDefault')
    );
  }

  public function testLoadFileConstructEnv()
  {
    putenv('TESTVAR1=constructTestValue1');
    putenv('TESTVAR2=constructTestValue2');
    putenv('API_SERVICE_HOST=apihost');
    putenv('API_SERVICE_PORT=8080');

    $file = dirname(__DIR__) . '/testData/envtest.ini';
    $provider = new \Packaged\Config\Provider\Ini\IniConfigProvider(
      $file, true
    );

    $this->assertEquals(
      'constructTestValue1',
      $provider->getItem('default', 'var1')
    );
    $this->assertEquals(
      'constructTestValue2',
      $provider->getItem('default', 'var2')
    );
    $this->assertEquals(
      'constructTestValue1-constructTestValue2',
      $provider->getItem('default', 'var1and2')
    );
    $this->assertEquals(
      'defaultValue',
      $provider->getItem('default', 'nonexistentDefault')
    );
    $this->assertEquals(
      '',
      $provider->getItem('default', 'nonexistentNoDefault')
    );
    $this->assertEquals(
      'http://apihost:8080',
      $provider->getItem('default', 'api')
    );
  }
}
