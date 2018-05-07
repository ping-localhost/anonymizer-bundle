<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Processor;

use Metadata\MetadataFactoryInterface;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedClassMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedMethodMetadata;
use PingLocalhost\AnonymizerBundle\Metadata\AnonymizedPropertyMetadata;

class AnonymizeProcessor
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadata_factory;

    public function __construct(MetadataFactoryInterface $metadata_factory)
    {
        $this->metadata_factory = $metadata_factory;
    }

    /**
     * @param object $object
     */
    public function anonymize($object): void
    {
        /** @var AnonymizedClassMetadata $metadata */
        if (null === ($metadata = $this->metadata_factory->getMetadataForClass(\get_class($object)))) {
            throw new \RuntimeException(sprintf("Couldn't load the metadata for class %s,", \get_class($object)));
        }

        $property_metadata = $this->getPropertyMetadata($metadata);
        $method_metadata   = $this->getMethodMetadata($metadata);

        if (count($method_metadata) <= 0 && count($property_metadata) <= 0) {
            return;
        }

        if ($metadata->isCouldExclude() && !$metadata->shouldInclude($object)) {
            return;
        }

        foreach ($property_metadata as $property) {
            $property->setValue($object);
        }

        foreach ($method_metadata as $method) {
            $method->invoke($object);
        }
    }

    /**
     * @param AnonymizedClassMetadata $metadata
     *
     * @return AnonymizedPropertyMetadata[]
     */
    private function getPropertyMetadata(AnonymizedClassMetadata $metadata): array
    {
        return array_filter($metadata->getPropertyMetadata(), function ($metadata) {
            return $metadata instanceof AnonymizedPropertyMetadata;
        });
    }

    /**
     * @param AnonymizedClassMetadata $metadata
     *
     * @return AnonymizedMethodMetadata[]
     */
    private function getMethodMetadata(AnonymizedClassMetadata $metadata): array
    {
        return array_filter($metadata->getMethodMetadata(), function ($metadata) {
            return $metadata instanceof AnonymizedMethodMetadata;
        });
    }
}
