<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use Doctrine\Common\Annotations\Reader;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Base;
use Faker\UniqueGenerator;
use InvalidArgumentException;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException;
use PingLocalhost\AnonymizerBundle\Exception\InvalidFunctionException;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;
use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeEntity;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedClassMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedMethodMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedPropertyMetadata;

class AnonymizeDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Generator
     */
    private $generator;

    public function __construct(Reader $reader, string $locale = 'nl_NL')
    {
        $this->reader    = $reader;
        $this->generator = Factory::create($locale);
    }

    public function loadMetadataForClass(\ReflectionClass $class): ClassMetadata
    {
        $class_metadata = new AnonymizedClassMetadata($class->getName());

        $this->buildClassMetadata($class, $class_metadata);
        $this->buildPropertyMetadata($class, $class_metadata);
        $this->buildMethodMetadata($class, $class_metadata);

        return $class_metadata;
    }

    private function buildClassMetadata(\ReflectionClass $class, AnonymizedClassMetadata $class_metadata): void
    {
        /** @var AnonymizeEntity $annotation */
        $annotation = $this->reader->getClassAnnotation($class, AnonymizeEntity::class);

        if ($annotation === null) {
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
        if (count($annotation->getInclusions()) > 0) {
            $class_metadata->setMatchers($annotation->getInclusions());
            $class_metadata->setMethod(AnonymizedClassMetadata::INCLUDE);
        } else {
            $class_metadata->setMatchers($annotation->getExclusions());
            $class_metadata->setMethod(AnonymizedClassMetadata::EXCLUDE);
        }

        $class_metadata->setCouldExclude(true);
    }

    private function buildPropertyMetadata(\ReflectionClass $class, ClassMetadata $class_metadata): void
    {
        foreach ($class->getProperties() as $property) {
            $property_metadata = new AnonymizedPropertyMetaData($class->getName(), $property->getName());

            /** @var Anonymize $annotation */
            if (null === ($annotation = $this->reader->getPropertyAnnotation($property, Anonymize::class))) {
                continue;
            }

            $name = sprintf('%s::%s', $class->getName(), $property->getName());

            $factory = $this->createFactory($annotation->getFaker(), $name);
            if ($annotation->isUnique()) {
                $factory = $factory->unique();
            }

            $property_metadata->setGenerator($factory);
            $property_metadata->setArguments($annotation->getArguments());
            $property_metadata->setProperty($annotation->getFaker());
            $property_metadata->setExcluded($annotation->getExcluded());

            $class_metadata->addPropertyMetadata($property_metadata);
        }
    }

    private function createFactory(string $function, string $name): Generator
    {
        try {
            $this->generator->getFormatter($function);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidFunctionException($function, $name, $e);
        }

        return $this->generator;
    }

    private function buildMethodMetadata(\ReflectionClass $class, ClassMetadata $class_metadata): void
    {
        foreach ($class->getMethods() as $method) {
            $metadata = new AnonymizedMethodMetadata($class->getName(), $method->getName());

            /** @var AnonymizedMethodMetadata $annotation */
            if (null === ($annotation = $this->reader->getMethodAnnotation($method, Anonymize::class))) {
                continue;
            }

            $factory   = null;
            $parameter = null;
            $arguments = [];

            foreach ($method->getParameters() as $parameter) {
                if (null === ($type = $parameter->getType())) {
                    $arguments = $this->lookupParameter($parameter, $annotation, $method, $arguments);
                    continue;
                }

                $name = $parameter->name;
                $type = $type->getName();
                if (is_a($type, Base::class, true)) {
                    $factory          = new $type($this->generator);
                    $arguments[$name] = $factory;
                    continue;
                }

                if (is_a($type, Generator::class, true)) {
                    $factory          = $this->generator;
                    $arguments[$name] = $factory;
                    continue;
                }

                if (is_a($type, UniqueGenerator::class, true)) {
                    $factory          = $this->generator->unique();
                    $arguments[$name] = $factory;
                    continue;
                }

                $arguments = $this->lookupParameter($parameter, $annotation, $method, $arguments);
            }

            $metadata->setArguments($arguments);

            $class_metadata->addMethodMetadata($metadata);
        }
    }

    private function lookupParameter(
        \ReflectionParameter $parameter,
        AnonymizedMethodMetadata $annotation,
        \ReflectionMethod $method,
        array $arguments
    ): array {
        if (array_key_exists($parameter->name, $annotation->getArguments())) {
            throw new InvalidArgumentException(
                sprintf('Didn\'t know how to inject class %s for argument %s', $parameter->name, $method->name)
            );
        }

        $arguments[$parameter->name] = $annotation->getArguments()[$parameter->name];

        return $arguments;
    }
}
