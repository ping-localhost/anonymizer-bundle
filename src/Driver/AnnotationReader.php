<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Driver;

use Doctrine\Common\Annotations\Reader;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;
use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeClass;

class AnnotationReader
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function getClassAnnotation(\ReflectionClass $class): ?AnonymizeClass
    {
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof AnonymizeClass) {
                return $annotation;
            }
        }

        return null;
    }

    public function getMethodAnnotation(\ReflectionMethod $method): ?Anonymize
    {
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Anonymize) {
                return $annotation;
            }
        }

        return null;
    }

    public function getPropertyAnnotation(\ReflectionProperty $property): ?Anonymize
    {
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof Anonymize) {
                return $annotation;
            }
        }

        return null;
    }
}
