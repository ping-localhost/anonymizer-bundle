<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity\Entity;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;
use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeEntity;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Driver\AnnotationReader
 */
class AnnotationReaderTest extends TestCase
{
    private $reader;

    /**
     * @var AnnotationReader
     */
    private $annotation_reader;

    protected function setUp(): void
    {
        $this->reader = $this->prophesize(Reader::class);

        $this->annotation_reader = new AnnotationReader(
            $this->reader->reveal()
        );
    }

    /**
     * @dataProvider emptyProvider
     */
    public function testGetClassAnnotation(bool $empty): void
    {
        $class      = new \ReflectionClass(Entity::class);
        $annotation = $this->prophesize(AnonymizeEntity::class)->reveal();

        $this->reader->getClassAnnotations($class)->willReturn($empty ? [] : [$annotation]);

        self::assertSame($empty ? null : $annotation, $this->annotation_reader->getClassAnnotation($class));
    }

    /**
     * @dataProvider emptyProvider
     */
    public function testGetMethodAnnotation(bool $empty): void
    {
        $method      = new \ReflectionMethod(Entity::class, 'getUsername');
        $annotation = $this->prophesize(Anonymize::class)->reveal();

        $this->reader->getMethodAnnotations($method)->willReturn($empty ? [] : [$annotation]);

        self::assertSame($empty ? null : $annotation, $this->annotation_reader->getMethodAnnotation($method));
    }

    /**
     * @dataProvider emptyProvider
     */
    public function testGetPropertyAnnotation(bool $empty): void
    {
        $property      = new \ReflectionProperty(Entity::class, 'username');
        $annotation = $this->prophesize(Anonymize::class)->reveal();

        $this->reader->getPropertyAnnotations($property)->willReturn($empty ? [] : [$annotation]);

        self::assertSame($empty ? null : $annotation, $this->annotation_reader->getPropertyAnnotation($property));
    }

    public function emptyProvider(): array
    {
        return [[true], [false]];
    }
}
