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
 * @covers \PingLocalhost\AnonymizerBundle\Metadata\AnonymizedMethodMetadata
 */
class AnonymizedMethodMetadataTest extends TestCase
{
    private $class;
    private $name;

    /**
     * @var AnonymizedMethodMetadata
     */
    private $metadata;

    protected function setUp(): void
    {
        $this->class = ExampleObject::class;
        $this->name = 'anonymize';

        $this->metadata = new AnonymizedMethodMetadata($this->class, $this->name);
    }

    public function testGetArguments(): void
    {
        self::assertEmpty($this->metadata->getArguments());
    }

    public function testSetArguments(): void
    {
        $this->metadata->setArguments(['argument']);
        self::assertSame(['argument'], $this->metadata->getArguments());
    }

    public function testInvoke(): void
    {
        $this->metadata->setArguments(['generator' => Factory::create()]);

        $this->metadata->invoke(new ExampleObject('username', 'email@email.com'));
    }
}
