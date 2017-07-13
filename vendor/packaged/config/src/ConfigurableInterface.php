<?php
namespace Packaged\Config;

interface ConfigurableInterface
{
  /**
   * Configure the data connection
   *
   * @param ConfigSectionInterface $configuration
   *
   * @return static
   */
  public function configure(ConfigSectionInterface $configuration);
}
