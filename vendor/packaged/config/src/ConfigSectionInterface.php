<?php
namespace Packaged\Config;

/**
 * Interface ConfigSectionInterface
 *
 * Configuration storage for a specific section of your entire config
 *
 * An example of this would be the configuration for a specific service
 *
 * @package Packaged\Config
 */
interface ConfigSectionInterface
{
  /**
   * Get the name of the current section e.g. database
   *
   * @return string
   */
  public function getName();

  /**
   * Name the current section
   *
   * @param string $name Name of this section
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Retrieve an item from the configuration
   *
   * @param string $key     Configuration item key e.g. hostname
   * @param mixed  $default Default value if the config item does not exist
   *
   * @return mixed
   *
   * @throws \Exception when default is passed as an exception
   */
  public function getItem($key, $default = null);

  /**
   * Check to see if a config item exists within the configuration
   *
   * @param $key
   *
   * @return bool
   */
  public function has($key);

  /**
   * Retrieve all the items in the configuration section
   *
   * @return array
   */
  public function getItems();

  /**
   * Add an item to the configuration section
   *
   * @param string $item  Config Item Key
   * @param mixed  $value Config Item Value
   *
   * @return $this
   */
  public function addItem($item, $value);

  /**
   * Remove a configuration item
   *
   * @param string $key Configuration item key e.g. hostname
   *
   * @return $this
   */
  public function removeItem($key);
}
