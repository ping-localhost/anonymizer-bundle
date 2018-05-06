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
 * @Target(value={"PROPERTY","METHOD"})
 */
class Anonymize
{
    /**
     * The provider to call on the faker generator.
     *
     * @var string
     */
    private $faker;

    /**
     * An optional array of arguments to pass to the faker.
     *
     * @var array
     */
    private $arguments;

    /**
     * An array of regular expressions.
     * Excludes the current property of the entity if one of the regular expressions matches.
     *
     * @var string[]
     */
    private $excluded;

    /**
     * A regular expression or a string to match the value against. If the value matches, set the property.
     *
     * @var string
     */
    private $exclude;

    /**
     * Whether the values in the database should be unique.
     *
     * @var bool
     */
    private $unique;

    public function __construct(array $options)
    {
        $this->faker     = $options['faker'] ?? '';
        $this->arguments = $options['fakerArguments'] ?? [];
        $this->excluded  = $options['excluded'] ?? [];
        $this->exclude   = $options['exclude'] ?? null;
        $this->unique    = $options['unique'] ?? false;

        if ($this->exclude !== null && count($this->excluded) > 0) {
            throw new InvalidAnonymizeAnnotationException(
                'You can\'t set both the excluded array and the exclude annotation.'
            );
        }
    }

    public function getFaker(): string
    {
        return $this->faker;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return string[]
     */
    public function getExcluded(): array
    {
        return $this->excluded;
    }

    public function getExclude(): string
    {
        return $this->exclude;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }
}
