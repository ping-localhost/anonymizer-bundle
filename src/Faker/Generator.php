<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Faker;

use Faker\Factory;
use Faker\Generator as BaseGenerator;

/**
 * An (ugly) wrapper around the \Faker\Factory so that we can inject custom providers with ease
 */
class Generator extends BaseGenerator
{
    public function __construct(string $locale, iterable $fakers)
    {
        $generator = Factory::create($locale);
        foreach ($generator->getProviders() as $provider) {
            $this->addProvider($provider);
        }

        foreach ($fakers as $faker) {
            $this->addProvider($faker);
        }
    }
}
