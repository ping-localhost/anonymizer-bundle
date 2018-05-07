<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity\Entity;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Driver\AnonymizeDriver
 */
class AnonymizeDriverTest extends TestCase
{
    private $extractor;
    private $locale;

    /**
     * @var AnonymizeDriver
     */
    private $anonymize_driver;

    protected function setUp(): void
    {
        $this->extractor = $this->prophesize(AnnotationReader::class);
        $this->locale    = 'nl_NL';

        $this->anonymize_driver = new AnonymizeDriver($this->extractor->reveal(), $this->locale);
    }

    public function testLoadMetadataForClass(): void
    {
    }
}
