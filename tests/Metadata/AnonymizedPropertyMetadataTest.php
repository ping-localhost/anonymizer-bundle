<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Metadata\AnonymizedPropertyMetadata
 */
class AnonymizedPropertyMetadataTest extends TestCase
{
    private $class;
    private $name;

    /**
     * @var AnonymizedPropertyMetadata
     */
    private $metadata;

    protected function setUp(): void
    {
        $this->class = ExampleObject::class;
        $this->name  = 'username';

        $this->metadata = new AnonymizedPropertyMetadata($this->class, $this->name);
    }

    public function testSetProperty(): void
    {
        $this->metadata->setProperty('Iets');
        self::assertSame('Iets', $this->getValue($this->metadata, 'property'));
    }

    public function testSetArguments(): void
    {
        $this->metadata->setArguments(['arguments']);
        self::assertSame(['arguments'], $this->getValue($this->metadata, 'arguments'));
    }

    public function testSetExcluded(): void
    {
        $this->metadata->setExcluded(['Iets']);
        self::assertSame(['Iets'], $this->getValue($this->metadata, 'excluded'));
    }

    public function testSetValueMissingGenerator(): void
    {
        $object = new ExampleObject('username', 'email');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No generator has been set and no value has been passed.');

        $this->metadata->setValue($object);
    }

    public function testSetValueMissingProperty(): void
    {
        $object = new ExampleObject('username', 'email');
        $this->metadata->setGenerator(Factory::create());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No property has been set and no value has been passed.');

        $this->metadata->setValue($object);
    }

    public function testSetValueObjectShouldBeExcluded(): void
    {
        $metadata = new AnonymizedPropertyMetadata($this->class, 'created_at');
        $object   = new ExampleObject('username', 'email');
        $current  = $this->getValue($object, 'created_at');

        $metadata->setGenerator(Factory::create());
        $metadata->setProperty('dateTime');
        $metadata->setValue($object);

        self::assertNotSame($current, $object->getCreatedAt());
    }

    public function testSetValueShouldBeExcluded(): void
    {
        $object = new ExampleObject('root', 'email');
        $this->metadata->setProperty('username');
        $this->metadata->setGenerator(Factory::create());
        $this->metadata->setExcluded(['/root/']);
        $this->metadata->setValue($object);

        self::assertSame('root', $object->getUsername());

        $this->metadata->setExcluded(['/not-root/']);
        $this->metadata->setValue($object);
        self::assertNotSame('root', $object->getUsername());
    }

    public function testSetValueNull(): void
    {
        $object = new ExampleObject(null, 'email');
        $this->metadata->setProperty('username');
        $this->metadata->setGenerator(Factory::create());
        $this->metadata->setValue($object);

        self::assertNull($object->getUsername());
    }

    public function testSetValue(): void
    {
        $object = new ExampleObject('username', 'email');
        $this->metadata->setValue($object, 'nameuser');
        self::assertSame('nameuser', $object->getUsername());

        $this->metadata->setProperty('username');
        $this->metadata->setGenerator(Factory::create());
        $this->metadata->setValue($object);
        self::assertNotSame('nameuser', $object->getUsername());
    }

    public function testSetGeneratorInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            "Invalid argument, expected one one 'Faker\Generator' or 'Faker\UniqueGenerator', got %s",
            \stdClass::class
        ));

        $this->metadata->setGenerator(new \stdClass());
    }

    public function testSetGenerator(): void
    {
        $generator = Factory::create();
        $this->metadata->setGenerator($generator);
        self::assertSame($generator, $this->getValue($this->metadata, 'generator'));
    }

    public function getValue($object, string $property_name)
    {
        $property = new \ReflectionProperty($object, $property_name);
        $property->setAccessible(true);
        $value = $property->getValue($object);
        $property->setAccessible(false);

        return $value;
    }
}
