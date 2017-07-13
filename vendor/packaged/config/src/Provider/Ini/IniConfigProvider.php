<?php
namespace Packaged\Config\Provider\Ini;

use Packaged\Config\Provider\AbstractConfigProvider;

class IniConfigProvider extends AbstractConfigProvider
{
  /**
   * @param null|string $file     Full path of ini file to create configuration from
   * @param bool        $parseEnv If true then parse environment variables in the INI file
   */
  public function __construct($file = null, $parseEnv = false)
  {
    if($file !== null)
    {
      $this->loadFile($file, $parseEnv);
    }
  }

  /**
   * Add items from an ini file to the configuration
   *
   * @param string $fullPath full path to the ini file to load
   * @param bool   $parseEnv If true then parse environment variables in the INI file
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadFile($fullPath, $parseEnv = false)
  {
    if(!file_exists($fullPath))
    {
      throw new \RuntimeException("Config file '$fullPath' could not be found");
    }

    if($parseEnv)
    {
      $this->loadString(file_get_contents($fullPath), true);
    }
    else
    {
      $data = parse_ini_file($fullPath, true);

      if(!$data)
      {
        throw new \RuntimeException(
          "The ini file '$fullPath' is corrupt or invalid"
        );
      }

      $this->_buildFromData($data);
    }

    return $this;
  }

  /**
   * Add items from an ini string to the configuration
   *
   * @param string $iniString valid ini string
   * @param bool   $parseEnv  If true then parse environment variables in the INI file
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadString($iniString, $parseEnv = false)
  {
    if($parseEnv)
    {
      $iniString = $this->_parseEnvVars($iniString);
    }
    $data = parse_ini_string($iniString, true);

    if(!$data)
    {
      throw new \RuntimeException(
        "The ini string passed is corrupt or invalid"
      );
    }

    $this->_buildFromData($data);
    return $this;
  }

  /**
   * Build up the configuration from the sectioned array
   *
   * @param array $iniData
   */
  protected function _buildFromData(array $iniData)
  {
    foreach($iniData as $section => $sectionData)
    {
      if(!is_array($sectionData))
      {
        continue;
      }

      foreach($sectionData as $item => $value)
      {
        $this->addItem($section, $item, $value);
      }
    }
  }

  /**
   * Parse environment variable markers in a string and replace them with the
   * variable's value.
   * Variables are expected to be in this format: {{ENV:VARNAME:defaultValue}}
   * The ":defaultValue" part is optional and defaults to an empty string
   *
   * @param string $iniString
   *
   * @return string
   */
  private function _parseEnvVars($iniString)
  {
    return preg_replace_callback(
      '/{{ENV:([0-9A-Za-z_]*)(:([^{}]*))?}}/',
      function ($matches)
      {
        $varName = $matches[1];
        $default = isset($matches[3]) ? $matches[3] : '';
        $value = getenv($varName);
        return $value === false ? $default : $value;
      },
      $iniString
    );
  }
}
