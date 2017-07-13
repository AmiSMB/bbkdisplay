<?php
namespace Packaged\Config;

/**
 * Interface ConfigProviderInterface
 *
 * The Configuration Provider is responsible for
 *
 * @package Packaged\Config
 */
interface ConfigProviderInterface
{
  /**
   * Retrieve all configuration sections
   *
   * @return ConfigSectionInterface[]
   */
  public function getSections();

  /**
   * @param string $name Name/Key of the configuration section
   *
   * @return ConfigSectionInterface
   * @throws \Exception
   */
  public function getSection($name);

  /**
   * @param ConfigSectionInterface $section Section container to add
   *
   * @return $this
   * @throws \Exception when the section already exists
   */
  public function addSection(ConfigSectionInterface $section);

  /**
   * Same as addSection, however, will replace an existing section if one exists
   *
   * @param ConfigSectionInterface $section Section container to add
   *
   * @return $this
   */
  public function setSection(ConfigSectionInterface $section);

  /**
   * Check to see if a section exists within the configuration
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function sectionExists($name);

  /**
   * Check to see if a section exists within the configuration
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function has($name);

  /**
   * @param string $section Section Name
   * @param string $key     Config Item Key
   * @param mixed  $default Default value for missing item
   *
   * @return mixed Configuration Value
   *
   * @throws \Exception when default is passed as an exception
   */
  public function getItem($section, $key, $default = null);

  /**
   * Add an item to the configuration
   *
   * @param string $section Section Name
   * @param string $item    Config Item Key
   * @param mixed  $value   Config Item Value
   *
   * @return $this
   */
  public function addItem($section, $item, $value);

  /**
   * Remove an item from the configuration
   *
   * @param $section
   * @param $item
   *
   * @return $this
   */
  public function removeItem($section, $item);

  /**
   * Remove a section from the configuration
   *
   * @param ConfigSectionInterface $section Section container to remove
   *
   * @return $this
   */
  public function removeSection(ConfigSectionInterface $section);

  /**
   * Remove a section from the configuration by its name
   *
   * @param string $sectionName Section name to remove
   *
   * @return $this
   */
  public function removeSectionByName($sectionName);
}
