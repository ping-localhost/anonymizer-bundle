<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Processor;

use Metadata\MetadataFactoryInterface;
use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\EmptyObject;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor
 */
class AnonymizeProcessorTest extends TestCase
{
    private $metadata_factory;

    /**
     * @var AnonymizeProcessor
     */
    private $anonymize_processor;

    protected function setUp(): void
    {
        $this->metadata_factory = $this->prophesize(MetadataFactoryInterface::class);

        $this->anonymize_processor = new AnonymizeProcessor($this->metadata_factory->reveal());
    }

    public function testAnonymizeNoMetadata(): void
    {
        $object = new EmptyObject('username', 'email@example.com');

        $this->metadata_factory->getMetadataForClass(EmptyObject::class)->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf("Couldn't load the metadata for class %s,", EmptyObject::class));

        $this->anonymize_processor->anonymize($object);
    }
}
