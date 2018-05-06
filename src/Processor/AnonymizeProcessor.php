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
     * @param object $entity
     */
    public function anonymize($entity): void
    {
        /** @var AnonymizedClassMetadata $metadata */
        if (null === ($metadata = $this->metadata_factory->getMetadataForClass(\get_class($entity)))) {
            throw new \RuntimeException(sprintf("Couldn't load the metadata for class %s,", \get_class($entity)));
        }

        $property_metadata = array_filter($metadata->propertyMetadata, function ($metadata) {
            return $metadata instanceof AnonymizedPropertyMetadata;
        });

        $method_metadata = array_filter($metadata->methodMetadata, function ($metadata) {
            return $metadata instanceof AnonymizedMethodMetadata;
        });

        if (count($method_metadata) <= 0 && count($property_metadata) <= 0) {
            return;
        }

        if ($metadata->isCouldExclude() && !$metadata->shouldInclude($entity)) {
            return;
        }
        /** @var AnonymizedPropertyMetaData $property */
        foreach ($property_metadata as $property) {
            $property->setValue($entity);
        }

        /** @var AnonymizedMethodMetadata $method */
        foreach ($method_metadata as $method) {
            $method->invoke($entity);
        }
    }
}
