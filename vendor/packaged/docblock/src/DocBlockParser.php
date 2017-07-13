<?php
namespace Packaged\DocBlock;

use Eloquent\Blox\BloxParser;
use Eloquent\Blox\Element\DocumentationTag;

class DocBlockParser
{
  /**
   * @var \Eloquent\Blox\Element\DocumentationBlock
   */
  protected $_docBlock;

  public function __construct($docBlock)
  {
    $this->_docBlock = (new BloxParser())->parseBlockComment($docBlock);
  }

  /**
   * @param $object
   *
   * @return DocBlockParser
   */
  public static function fromObject($object)
  {
    $reflect = new \ReflectionClass($object);
    return new self($reflect->getDocComment());
  }

  /**
   * @param $object
   * @param $method
   *
   * @return DocBlockParser
   */
  public static function fromMethod($object, $method)
  {
    $reflect = new \ReflectionMethod($object, $method);
    return new self($reflect->getDocComment());
  }

  /**
   * @param $object
   * @param $property
   *
   * @return DocBlockParser
   */
  public static function fromProperty($object, $property)
  {
    $reflect = new \ReflectionProperty($object, $property);
    return new self($reflect->getDocComment());
  }

  /**
   * @param      $object
   * @param null $filter
   *
   * @return DocBlockParser[]
   */
  public static function fromProperties($object, $filter = null)
  {
    $reflect = new \ReflectionClass($object);
    $parsers = [];
    foreach($reflect->getProperties($filter) as $property)
    {
      $parsers[$property->name] = new self($property->getDocComment());
    }
    return $parsers;
  }

  /**
   * Get the summary of the docblock
   * @return null|string
   */
  public function getSummary()
  {
    return $this->_docBlock->summary();
  }

  /**
   * Get the body of the docblock (excluding summary)
   *
   * @return null|string
   */
  public function getBody()
  {
    return $this->_docBlock->body();
  }

  /**
   * Get the Blox docblock
   *
   * @return \Eloquent\Blox\Element\DocumentationBlock
   */
  public function rawDocBlock()
  {
    return $this->_docBlock;
  }

  /**
   * Retrieve all the docblock tags
   * @return array
   */
  public function getTags()
  {
    $return = [];
    foreach($this->_docBlock->tags() as $tag)
    {
      /**
       * @var $tag DocumentationTag
       */
      if(!isset($return[$tag->name()]))
      {
        $return[$tag->name()] = [];
      }

      $return[$tag->name()][] = trim($tag->content());
    }
    return $return;
  }

  /**
   * Check to see if the docblock has one or more tags by name
   *
   * @param $tag
   *
   * @return bool
   */
  public function hasTag($tag)
  {
    return $this->_docBlock->tagsByName($tag) !== [];
  }

  /**
   * Retrieve the number of instances of a tag
   *
   * @param $tag
   *
   * @return int|void
   */
  public function getTagCount($tag)
  {
    return count($this->_docBlock->tagsByName($tag));
  }

  /**
   * Get the value of a tag.  If one tag exists, the value will be returned.
   * If multiple items exist, the first value will be returned
   * If no items exist, the default value will be returned
   *
   * @param      $tag
   * @param null $default
   *
   * @return null|string
   */
  public function getTag($tag, $default = null)
  {
    $tags = $this->_docBlock->tagsByName($tag);

    if(empty($tags))
    {
      return $default;
    }

    /**
     * @var $tags DocumentationTag[]
     */
    return trim($tags[0]->content());
  }

  /**
   * Retrieve the first tag with data, if none exist, return the default
   *
   * @param array $tags
   * @param null  $default
   *
   * @return null|string
   */
  public function getTagFailover(array $tags, $default = null)
  {
    foreach($tags as $tag)
    {
      $value = $this->getTag($tag);
      if($value !== null)
      {
        return $value;
      }
    }
    return $default;
  }
}
