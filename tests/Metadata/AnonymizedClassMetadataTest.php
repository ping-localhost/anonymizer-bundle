<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Metadata\AnonymizedClassMetadata
 */
class AnonymizedClassMetadataTest extends TestCase
{
    private $name;

    /**
     * @var AnonymizedClassMetadata
     */
    private $metadata;

    protected function setUp(): void
    {
        $this->name = ExampleObject::class;

        $this->metadata = new AnonymizedClassMetadata($this->name);
    }

    public function testGeneric(): void
    {
        $this->metadata->setMethod(AnonymizedClassMetadata::INCLUDE);
        self::assertSame(AnonymizedClassMetadata::INCLUDE, $this->getValue($this->metadata, 'method'));

        self::assertEmpty($this->metadata->getMatchers());
        $this->metadata->setMatchers(['matchers']);
        self::assertSame(['matchers'], $this->metadata->getMatchers());

        self::assertFalse($this->metadata->isCouldExclude());
        $this->metadata->setCouldExclude(true);
        self::assertTrue($this->metadata->isCouldExclude());
    }

    public function testSetMethodInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The method 9999 is not a valid method');

        $this->metadata->setMethod(9999);
    }

    public function testShouldInclude(): void
    {
    }

    public function testMerge(): void
    {
    }

    private function getValue($object, string $property_name)
    {
        $property = new \ReflectionProperty($object, $property_name);
        $property->setAccessible(true);
        $value = $property->getValue($object);
        $property->setAccessible(false);

        return $value;
    }
}
