<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity\Entity;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Metadata\AnonymizedClassMetadata
 */
class AnonymizedClassMetadataTest extends TestCase
{
    private $name;

    /**
     * @var AnonymizedClassMetadata
     */
    private $anonymized_class_metadata;

    protected function setUp(): void
    {
        $this->name = Entity::class;

        $this->anonymized_class_metadata = new AnonymizedClassMetadata($this->name);
    }

    public function testGetMethod(): void
    {
    }

    public function testSetMethod(): void
    {
    }

    public function testShouldInclude(): void
    {
    }

    public function testGetMatchers(): void
    {
    }

    public function testSetMatchers(): void
    {
    }

    public function testIsCouldExclude(): void
    {
    }

    public function testSetCouldExclude(): void
    {
    }

    public function testMerge(): void
    {
    }
}
