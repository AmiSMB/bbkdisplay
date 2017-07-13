<?php

class DocBlockParserTest extends PHPUnit_Framework_TestCase
{
  public function testFromObject()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromObject(new DocBlockFiller());
    $this->assertEquals('This class is for a test', $bloc->getSummary());
  }

  public function testFromProperty()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromProperty(
      new DocBlockFiller(),
      'property'
    );
    $this->assertEquals(3, $bloc->getTagCount('value'));
  }

  public function testFromMethod()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromMethod(
      new DocBlockFiller(),
      'getProperty'
    );
    $this->assertTrue($bloc->hasTag('return'));
  }

  public function testFromProperties()
  {
    $blocs = \Packaged\DocBlock\DocBlockParser::fromProperties(
      new DocBlockFiller(),
      ReflectionProperty::IS_PUBLIC
    );
    $this->assertContainsOnlyInstancesOf(
      '\Packaged\DocBlock\DocBlockParser',
      $blocs
    );
    $this->assertCount(2, $blocs);
  }

  public function testTagging()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromProperty(
      new DocBlockFiller(),
      'property'
    );
    $this->assertEquals('Hello', $bloc->getTag('name'));
    $this->assertEquals('Default', $bloc->getTag('missing', 'Default'));
    $this->assertEquals('Hello', $bloc->getTagFailover(['nothing', 'name']));
    $this->assertEquals(
      'Default',
      $bloc->getTagFailover(['nothing', 'missing'], 'Default')
    );
    $this->assertEquals(3, $bloc->getTagCount('value'));
    $this->assertArrayHasKey('index', $bloc->getTags());
    $this->assertArrayHasKey('name', $bloc->getTags());
    $this->assertArrayHasKey('value', $bloc->getTags());
  }

  public function testGetBody()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromObject(new DocBlockFiller());
    $this->assertEquals(
      'Lorem ipsum dolor sit amet, consectetur'
      . ' adipiscing elit. Sed ac ligula risus.',
      $bloc->getBody()
    );
  }

  public function testGetRaw()
  {
    $bloc = \Packaged\DocBlock\DocBlockParser::fromObject(new DocBlockFiller());
    $this->assertInstanceOf(
      '\Eloquent\Blox\Element\DocumentationBlock',
      $bloc->rawDocBlock()
    );
  }
}

/**
 * This class is for a test
 *
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac ligula risus.
 */
class DocBlockFiller
{
  /**
   * This property is for data
   *
   * @index
   * @name Hello
   *
   * @value 1
   * @value 2
   * @value 3
   */
  public $property;

  public $propertyTwo;

  /**
   * Method Description
   *
   * @return mixed
   */
  public function getProperty()
  {
    return $this->property;
  }
}
