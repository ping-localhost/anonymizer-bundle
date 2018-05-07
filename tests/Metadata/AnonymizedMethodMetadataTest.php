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
    private $anonymized_method_metadata;

    protected function setUp(): void
    {
        $this->class = ExampleObject::class;
        $this->name = 'anonymize';

        $this->anonymized_method_metadata = new AnonymizedMethodMetadata($this->class, $this->name);
    }

    public function testGetArguments(): void
    {
        self::assertEmpty($this->anonymized_method_metadata->getArguments());
    }

    public function testSetArguments(): void
    {
        $this->anonymized_method_metadata->setArguments(['argument']);
        self::assertSame(['argument'], $this->anonymized_method_metadata->getArguments());
    }

    public function testInvoke(): void
    {
        $this->anonymized_method_metadata->setArguments(['generator' => Factory::create()]);

        $this->anonymized_method_metadata->invoke(new ExampleObject('username', 'email@email.com'));
    }
}
