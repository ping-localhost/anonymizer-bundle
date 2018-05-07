<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Mapping;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException;

/**
 * @Annotation
 * @Target(value="CLASS")
 */
class AnonymizeEntity
{
    /**
     * The entities to exclude. "property" => "value". If any of the property matches, do not update this entity.
     *
     * @var array
     */
    private $exclusions;

    /**
     * The entities to include. "property" => "value". If any of the property matches the value, update this entity.
     *
     * @var array
     */
    private $inclusions;

    public function __construct(array $options)
    {
        $this->exclusions = $options['exclusions'] ?? [];
        $this->inclusions = $options['inclusions'] ?? [];

        if (count($this->exclusions) >= 1 && count($this->inclusions) >= 1) {
            throw new InvalidAnonymizeAnnotationException('Can\'t set both the inclusions and the exclusions');
        }
    }

    /**
     * @return array
     */
    public function getExclusions(): array
    {
        return $this->exclusions;
    }

    /**
     * @return array
     */
    public function getInclusions(): array
    {
        return $this->inclusions;
    }
}
