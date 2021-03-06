<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use Metadata\MergeableClassMetadata;
use Metadata\MergeableInterface;

class AnonymizedClassMetadata extends MergeableClassMetadata
{
    public const INCLUDE = 0;
    public const EXCLUDE = 1;

    private $matchers      = [];
    private $method        = self::INCLUDE;
    private $could_exclude = false;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @param object $object
     *
     * @return bool
     */
    public function shouldInclude($object): bool
    {
        $reflection = new \ReflectionClass($object);
        foreach ($this->getMatchers() as $property => $matches) {
            if (!$reflection->hasProperty($property)) {
                continue;
            }

            $prop = $reflection->getProperty($property);
            $prop->setAccessible(true);

            $value = $prop->getValue($object);

            if (\is_string($matches) && (false !== stripos($value, $matches) || preg_match($matches, $value))) {
                return $this->method === self::INCLUDE;
            }
        }

        return $this->method === self::EXCLUDE;
    }

    public function merge(MergeableInterface $object): void
    {
        parent::merge($object);

        if (!($object instanceof self)) {
            return;
        }

        $this->could_exclude = $object->could_exclude || $this->could_exclude;
        $this->matchers      = $object->matchers ?? $this->matchers;
        $this->method        = $object->method ?? $this->method;
    }

    public function setMethod(int $method): void
    {
        if (!\in_array($method, [self::INCLUDE, self::EXCLUDE], true)) {
            throw new \InvalidArgumentException(sprintf('The method %d is not a valid method', $method));
        }

        $this->method = $method;
    }

    public function getMatchers(): array
    {
        return $this->matchers;
    }

    public function setMatchers(array $matchers): void
    {
        $this->matchers = $matchers;
    }

    public function isCouldExclude(): bool
    {
        return $this->could_exclude;
    }

    public function setCouldExclude(bool $could_exclude): void
    {
        $this->could_exclude = $could_exclude;
    }

    public function getMethodMetadata(): array
    {
        return $this->methodMetadata;
    }

    public function getPropertyMetadata(): array
    {
        return $this->propertyMetadata;
    }
}
