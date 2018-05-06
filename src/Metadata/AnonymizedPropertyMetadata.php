<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use Faker\Generator;
use Faker\UniqueGenerator;
use InvalidArgumentException;
use Metadata\PropertyMetadata;

class AnonymizedPropertyMetadata extends PropertyMetadata
{
    /**
     * @var Generator
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

    public function setValue($object, $value = null): void
    {
        if ($value === null) {
            if (null === ($value = $this->getValue($object))) {
                return;
            }

            if (true === $this->shouldBeExcluded($object)) {
                return;
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
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid argument, expected one one \'Faker\\Generator\' or \'Faker\\UniqueGenerator\', got %s',
                    \get_class($generator)
                ),
                2002
            );
        }

        $this->generator = $generator;
    }

    public function setProperty(string $property): void
    {
        $this->property = $property;
    }

    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function setExcluded($excluded): void
    {
        $this->excluded = $excluded;
    }

    private function shouldBeExcluded($value): bool
    {
        if (!is_scalar($this) && \is_object($value) && !method_exists($value, '__toString')) {
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
