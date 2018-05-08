<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use Faker\Generator as BaseGenerator;
use Faker\Provider\Base;
use Faker\UniqueGenerator;
use InvalidArgumentException;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException;
use PingLocalhost\AnonymizerBundle\Exception\InvalidFakerException;
use PingLocalhost\AnonymizerBundle\Faker\Generator;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedClassMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedMethodMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedPropertyMetadata;

class AnonymizeDriver implements DriverInterface
{
    /**
     * @var AnnotationReader
     */
    private $extractor;

    /**
     * @var Generator
     */
    private $generator;

    public function __construct(AnnotationReader $extractor, Generator $generator)
    {
        $this->extractor = $extractor;
        $this->generator = $generator;
    }

    public function loadMetadataForClass(\ReflectionClass $class): AnonymizedClassMetadata
    {
        $class_metadata = new AnonymizedClassMetadata($class->getName());

        $this->buildClassMetadata($class, $class_metadata);
        $this->buildPropertyMetadata($class, $class_metadata);
        $this->buildMethodMetadata($class, $class_metadata);

        return $class_metadata;
    }

    private function buildClassMetadata(\ReflectionClass $class, AnonymizedClassMetadata $class_metadata): void
    {
        if (null === ($annotation = $this->extractor->getClassAnnotation($class))) {
            return;
        }

        foreach ($annotation->getExclusions() as $property => $regex) {
            if (!$class->hasProperty($property)) {
                throw new InvalidAnonymizeAnnotationException(
                    sprintf("The expected property %s doesn\'t exist in class %s", $property, $class->getName())
                );
            }
        }

        foreach ($annotation->getInclusions() as $property => $regex) {
            if (!$class->hasProperty($property)) {
                throw new InvalidAnonymizeAnnotationException(
                    sprintf("The expected property %s doesn\'t exist in class %s", $property, $class->getName())
                );
            }
        }

        $class_metadata->setCouldExclude(true);

        if (count($annotation->getInclusions()) > 0) {
            $class_metadata->setMatchers($annotation->getInclusions());
            $class_metadata->setMethod(AnonymizedClassMetadata::INCLUDE);

            return;
        }

        $class_metadata->setMatchers($annotation->getExclusions());
        $class_metadata->setMethod(AnonymizedClassMetadata::EXCLUDE);
    }

    private function buildPropertyMetadata(\ReflectionClass $class, ClassMetadata $class_metadata): void
    {
        foreach ($class->getProperties() as $property) {
            if (null === ($annotation = $this->extractor->getPropertyAnnotation($property))) {
                continue;
            }

            $name = sprintf('%s::%s', $class->getName(), $property->getName());

            $factory = $this->createFactory($annotation->getFaker(), $name);
            if ($annotation->isUnique()) {
                $factory = $factory->unique();
            }

            $property_metadata = new AnonymizedPropertyMetaData($class->getName(), $property->getName());
            $property_metadata->setGenerator($factory);
            $property_metadata->setArguments($annotation->getArguments());
            $property_metadata->setProperty($annotation->getFaker());
            $property_metadata->setExcluded($annotation->getExcluded());

            $class_metadata->addPropertyMetadata($property_metadata);
        }
    }

    private function createFactory(string $generator, string $property): Generator
    {
        try {
            $this->generator->getFormatter($generator);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidFakerException($generator, $property, $exception);
        }

        return $this->generator;
    }

    private function buildMethodMetadata(\ReflectionClass $class, ClassMetadata $class_metadata): void
    {
        foreach ($class->getMethods() as $method) {
            if (null === $this->extractor->getMethodAnnotation($method)) {
                continue;
            }

            $factory   = null;
            $parameter = null;
            $arguments = [];

            foreach ($method->getParameters() as $parameter) {
                if (null === ($type = $parameter->getType())) {
                    continue;
                }

                $name = $parameter->name;
                $type = $type->getName();
                if (is_a($type, Base::class, true)) {
                    $factory          = new $type($this->generator);
                    $arguments[$name] = $factory;
                    continue;
                }

                if (is_a($type, BaseGenerator::class, true)) {
                    $factory          = $this->generator;
                    $arguments[$name] = $factory;
                    continue;
                }

                if (is_a($type, UniqueGenerator::class, true)) {
                    $factory          = $this->generator->unique();
                    $arguments[$name] = $factory;
                    continue;
                }

                throw new InvalidArgumentException(
                    sprintf('Didn\'t know how to inject class "%s" for method "%s".', $type, $method->name)
                );
            }

            $metadata = new AnonymizedMethodMetadata($class->getName(), $method->getName());
            $metadata->setArguments($arguments);

            $class_metadata->addMethodMetadata($metadata);
        }
    }
}
