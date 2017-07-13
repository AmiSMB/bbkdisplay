<?php
namespace Packaged\Config\Provider;

use Packaged\Config\ConfigProviderInterface;
use Packaged\Config\ConfigSectionInterface;

abstract class AbstractConfigProvider implements ConfigProviderInterface
{
  /**
   * @var ConfigSection[]
   */
  protected $_sections;

  /**
   * Add an item to the configuration
   *
   * @param string $section Section Name
   * @param string $item    Config Item Key
   * @param mixed  $value   Config Item Value
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function addItem($section, $item, $value)
  {
    if(isset($this->_sections[$section]))
    {
      $this->_sections[$section]->addItem($item, $value);
    }
    else
    {
      $this->addSection(new ConfigSection($section, [$item => $value]));
    }

    return $this;
  }

  /**
   * Remove an item from the configuration
   *
   * @param $section
   * @param $item
   *
   * @return $this
   */
  public function removeItem($section, $item)
  {
    if(isset($this->_sections[$section]))
    {
      $this->_sections[$section]->removeItem($item);
    }
    return $this;
  }

  /**
   * @param string $section Section Name
   * @param string $key     Config Item Key
   * @param mixed  $default Default value for missing item
   *
   * @return mixed Configuration Value
   *
   * @throws \Exception when default is passed as an exception
   */
  public function getItem($section, $key, $default = null)
  {
    if(!$this->sectionExists($section))
    {
      if($default instanceof \Exception)
      {
        throw $default;
      }
      return $default;
    }
    return $this->getSection($section)->getItem($key, $default);
  }

  /**
   * Retrieve all configuration sections
   *
   * @return ConfigSectionInterface[]
   */
  public function getSections()
  {
    return $this->_sections;
  }

  /**
   * @param string $name Name/Key of the configuration section
   *
   * @return ConfigSectionInterface
   * @throws \Exception
   */
  public function getSection($name)
  {
    if(isset($this->_sections[$name]))
    {
      return $this->_sections[$name];
    }
    throw new \Exception("Configuration section $name could not be found");
  }

  /**
   * Check to see if a section exists within the configuration
   *
   * @alias has
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function sectionExists($name)
  {
    return isset($this->_sections[$name]);
  }

  /**
   * Check to see if a section exists within the configuration
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function has($name)
  {
    return isset($this->_sections[$name]);
  }

  /**
   * @param ConfigSectionInterface $section Section container to add
   *
   * @return $this
   * @throws \Exception when the section already exists
   */
  public function addSection(ConfigSectionInterface $section)
  {
    if($this->sectionExists($section->getName()))
    {
      throw new \Exception(
        "The section " . $section->getName() . " cannot be re-added"
      );
    }

    $this->_sections[$section->getName()] = $section;
    return $this;
  }

  /**
   * Same as addSection, however, will replace an existing section if one exists
   *
   * @param ConfigSectionInterface $section Section container to add
   *
   * @return $this
   */
  public function setSection(ConfigSectionInterface $section)
  {
    $this->_sections[$section->getName()] = $section;
    return $this;
  }

  /**
   * Remove a section from the configuration
   *
   * @param ConfigSectionInterface $section Section container to remove
   *
   * @return $this
   */
  public function removeSection(ConfigSectionInterface $section)
  {
    $this->removeSectionByName($section->getName());
    return $this;
  }

  /**
   * Remove a section from the configuration by its name
   *
   * @param string $sectionName Section name to remove
   *
   * @return $this
   */
  public function removeSectionByName($sectionName)
  {
    if(isset($this->_sections[$sectionName]))
    {
      unset($this->_sections[$sectionName]);
    }

    return $this;
  }
}
