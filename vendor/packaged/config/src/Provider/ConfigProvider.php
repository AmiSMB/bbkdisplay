<?php
namespace Packaged\Config\Provider;

class ConfigProvider extends AbstractConfigProvider
{
  /**
   * @param array $sections [section => [item => value]]
   */
  public function __construct(array $sections = null)
  {
    if($sections !== null)
    {
      foreach($sections as $section => $items)
      {
        if(!is_array($items))
        {
          $this->addSection(
            new ConfigSection(
              is_scalar($items) && is_int($section) ? $items : $section
            )
          );
          break;
        }

        foreach($items as $item => $value)
        {
          $this->addItem($section, $item, $value);
        }
      }
    }
  }
}
