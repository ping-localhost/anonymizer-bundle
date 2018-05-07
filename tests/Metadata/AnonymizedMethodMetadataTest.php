<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Metadata\AnonymizedMethodMetadata
 */
class AnonymizedMethodMetadataTest extends TestCase
{
    private $class;
    private $name;

    /**
     * @var AnonymizedMethodMetadata
     */
    private $anonymized_method_metadata;

    protected function setUp(): void
    {
        $this->class = ExampleObject::class;
        $this->name  = 'anonymize';

        $this->anonymized_method_metadata = new AnonymizedMethodMetadata($this->class, $this->name);
    }

    public function testGetArguments(): void
    {
    }

    public function testSetArguments(): void
    {
    }

    public function testInvoke(): void
    {
    }
}
