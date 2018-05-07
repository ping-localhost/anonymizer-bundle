<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

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
    private $anonymized_property_metadata;

    protected function setUp(): void
    {
        $this->class = ExampleObject::class;
        $this->name  = 'username';

        $this->anonymized_property_metadata = new AnonymizedPropertyMetadata($this->class, $this->name);
    }

    public function testSetValue(): void
    {
    }

    public function testSetGenerator(): void
    {
    }

    public function testSetProperty(): void
    {
    }

    public function testSetArguments(): void
    {
    }

    public function testSetExcluded(): void
    {
    }
}
