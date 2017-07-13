<?php
namespace Packaged\Config\Provider;

use Packaged\Config\ConfigSectionInterface;

/**
 * Configuration section
 */
class ConfigSection implements ConfigSectionInterface, \ArrayAccess
{
  protected $_name;
  protected $_items;

  /**
   * @param string $name  Name of this configuration section
   * @param array  $items all configuration items e.g. [host => localhost]
   */
  public function __construct($name = '', array $items = [])
  {
    $this->_name = $name;
    $this->_items = $items;
  }

  /**
   * Name the current section
   *
   * @param string $name Name of this section
   *
   * @return $this
   */
  public function setName($name)
  {
    $this->_name = $name;
    return $this;
  }

  /**
   * Get the name of the current section e.g. database
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }

  /**
   * Check to see if a config item exists within the configuration
   *
   * @param $key
   *
   * @return bool
   */
  public function has($key)
  {
    return isset($this->_items[$key]);
  }

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
  public function getItem($key, $default = null)
  {
    if(isset($this->_items[$key]))
    {
      return $this->_items[$key];
    }
    else
    {
      if($default instanceof \Exception)
      {
        throw $default;
      }
      return $default;
    }
  }

  /**
   * Retrieve all the items in the configuration section
   *
   * @return array
   */
  public function getItems()
  {
    return $this->_items;
  }

  /**
   * Add a new configuration item
   *
   * @param string $key   Configuration item key e.g. hostname
   * @param mixed  $value Configuration item value e.g. localhost
   *
   * @return $this
   */
  public function addItem($key, $value)
  {
    $this->_items[$key] = $value;
    return $this;
  }

  /**
   * Remove a configuration item
   *
   * @param string $key Configuration item key e.g. hostname
   *
   * @return $this
   */
  public function removeItem($key)
  {
    unset($this->_items[$key]);
    return $this;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset <p>
   *                      An offset to check for.
   *                      </p>
   *
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
    return isset($this->_items[$offset]);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   *                      The offset to retrieve.
   *                      </p>
   *
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
    return $this->getItem($offset);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset <p>
   *                      The offset to assign the value to.
   *                      </p>
   * @param mixed $value  <p>
   *                      The value to set.
   *                      </p>
   *
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    $this->addItem($offset, $value);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   *                      The offset to unset.
   *                      </p>
   *
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->_items[$offset]);
  }
}
