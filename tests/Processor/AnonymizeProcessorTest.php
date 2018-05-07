<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Processor;

use Metadata\MetadataFactoryInterface;
use PHPUnit\Framework\TestCase;

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

        $this->anonymize_processor = new AnonymizeProcessor(
            $this->metadata_factory->reveal()
        );
    }

    public function testAnonymize(): void
    {
    }
}
