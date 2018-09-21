<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use Faker\Generator;
use Faker\UniqueGenerator;
use Metadata\PropertyMetadata;

class AnonymizedPropertyMetadata extends PropertyMetadata
{
    /**
     * @var Generator|UniqueGenerator
     */
    private $generator;

    /**
     * @var string
     */
    private $property;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $excluded = [];

    public function __construct(string $class, string $name)
    {
        parent::__construct($class, $name);
    }

    public function setProperty(string $property): void
    {
        $this->property = $property;
    }

    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function setExcluded(array $excluded): void
    {
        $this->excluded = $excluded;
    }

    public function setValue($object, $value = null): void
    {
        if ($value === null) {
            if (null === ($value = $this->getValue($object))) {
                return;
            }

            if (true === $this->shouldBeExcluded($this->getValue($object))) {
                return;
            }

            if (null === $this->generator) {
                throw new \InvalidArgumentException('No generator has been set and no value has been passed.');
            }

            if (null === $this->property) {
                throw new \InvalidArgumentException('No property has been set and no value has been passed.');
            }

            $value = \is_callable([$this->generator, $this->property])
                ? \call_user_func_array([$this->generator, $this->property], $this->arguments)
                : $this->generator->${$this->property};
        }

        parent::setValue($object, $value);
    }

    public function setGenerator($generator): void
    {
        if (!($generator instanceof Generator) && !($generator instanceof UniqueGenerator)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid argument, expected one one 'Faker\Generator' or 'Faker\UniqueGenerator', got %s",
                \get_class($generator)
            ));
        }

        $this->generator = $generator;
    }

    private function shouldBeExcluded($value): bool
    {
        if (\is_object($value) && false === method_exists($value, '__toString')) {
            return false;
        }

        $original_value = (string) $value;
        foreach ($this->excluded as $item) {
            if (preg_match($item, $original_value)) {
                return true;
            }
        }

        return false;
    }
}
