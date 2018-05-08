<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Faker\Generator;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Driver\AnonymizeDriver
 */
class AnonymizeDriverTest extends TestCase
{
    private $extractor;
    private $generator;

    /**
     * @var AnonymizeDriver
     */
    private $anonymize_driver;

    protected function setUp(): void
    {
        $this->extractor = $this->prophesize(AnnotationReader::class);
        $this->generator = $this->prophesize(Generator::class);

        $this->anonymize_driver = new AnonymizeDriver($this->extractor->reveal(), $this->generator->reveal());
    }

    public function testLoadMetadataForClass(): void
    {
    }
}
