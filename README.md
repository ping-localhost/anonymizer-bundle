<p align="center">
<a href="https://github.com/ping-localhost"><img width="200" src="https://i.imgur.com/cYZ0hos.png"></a>
</p>

**Package details** 

[![Latest Release Version](https://img.shields.io/github/release/ping-localhost/anonymizer-bundle.svg?style=flat-square)](https://packagist.org/packages/ping-localhost/anonymizer-bundle)
[![License](https://img.shields.io/github/license/ping-localhost/anonymizer-bundle.svg?style=flat-square)](https://packagist.org/packages/ping-localhost/anonymizer-bundle)
[![Maintenance](https://img.shields.io/maintenance/yes/2018.svg?style=flat-square)](https://packagist.org/packages/ping-localhost/anonymizer-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/ping-localhost/anonymizer-bundle.svg?style=flat-square)](https://packagist.org/packages/ping-localhost/anonymizer-bundle)

**Code quality**

[![Build Status](https://travis-ci.com/ping-localhost/anonymizer-bundle.svg?branch=master)](https://travis-ci.com/ping-localhost/anonymizer-bundle)
[![Code coverage](https://codecov.io/gh/ping-localhost/anonymizer-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/ping-localhost/anonymizer-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/5aef06f90fb24f5450e02e9b/badge.svg)](https://www.versioneye.com/user/projects/5aef06f90fb24f5450e02e9b)

# Credit
This bundle is a stripped down version of the [OrangeRT/AnonymizeBundle](https://github.com/OrangeRT/AnonymizeBundle) package created by OrangeRT, so I'm hereby giving credit where credit is due.

I've forked their project and stripped it, so that I could use it to anonymize data per class, instead of the whole database.

# Contents
- [Installation](#installation)
  * [Enabling the Bundle without Symfony Flex](#enabling-the-bundle-without-symfony-flex)
- [Usage](#usage)
  * [Anonymizing a property](#anonymizing-a-property)
  * [Anonymizing callback](#anonymizing-callback)
  * [Unique variables](#unique-variables)
  * [Excluding classes](#excluding-classes)
    + [Skipping objects](#skipping-objects)
- [Faker](#faker)
  * [Providers](#providers)
  * [Changing the locale](#changing-the-locale)
- [License](#license)

# Installation
Installation of the bundle can be done using `composer` and is the recommended way of adding the bundle to your application. To do so, in your command line enter the project directory and execute the following command to download the latest stable version of this bundle:

```bash
$ composer require ping-localhost/anonymizer-bundle
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

## Enabling the Bundle without Symfony Flex
When using Symfony Flex, Symfony will automatically enable the bundle. If Flex is not used, then in `app/AppKernel.php` you need to enable the bundle by adding it to the list of registered bundles. Just add it to the list of already registered bundles like so:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new PingLocalhost\AnonymizerBundle\AnonymizerBundle(),
            // ...
        ];
    }
}
```

# Usage
Classes can be anonymized by annotating the properties that you want to be anonymized.
There are two annotations available, `@Anonymize` and `@AnonymizeClass`.

## Anonymizing a property
Properties can be anonymized with the `@Anonymize` annotation:

```php
<?php

use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;

class Person
{
    /**
     * @Anonymize(faker="userName")
     */
    private $username;
}
```

Once you have tagged a class with the `@Anonymize`-annotation, its possible to anonymize that class
by passing it to the `anonymize`-function within the `AnonymizeProcessor`-class.

## Anonymizing callback
Sometimes it is required that properties need an advanced or a custom way of anonymizing said class.
If a method is annotated with the `@Anonymize` annotation, the method is called.

If you need a faker you can typehint the parameter, as soon below:
```php
<?php

use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;

class Person
{
    private $username;
    private $email;

    /**
     * @Anonymize()
     */
    public function anonymize(\Faker\UniqueGenerator $generator)
    {
        $this->username = $this->email = $generator->email;
    }
}
```

Upon anonymizing the person, a UniqueGenerator is created for the method and the method is invoked with the generator.
The username will be the same as the email, and it will be a uniquely generated email.

## Unique variables
For properties like email and usernames, unique values should be used. The Anonymize property has a `unique=true`
flag to set use the `UniqueGenerator` provided by the Faker library. If a callback needs the UniqueGenerator,
typehint the generator with the UniqueGenerator.

## Excluding objects
It is possible to either skip a property, or to skip an entire object.

### Skipping objects
It is either possible to blacklist an object, or to whitelist an object. The exclusions are done with a key value pair,
where the key is the name of the property, and the value is either a direct match with the value, or a regex.

The inclusions are done in the same way, if one of the inclusions is matched, the object is anonymized.

In the example below, every person will be anonymized except for the people
that have a username that ends with `@example.com`.
```php
<?php

use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeClass;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;

/**
 *
 * @AnonymizeClass(exclusions={"username": "/@example.com$/"})
 */
class Person
{
    /**
     * @Anonymize(faker="email", unique=true)
     */
    private $username;
    
    /**
     * @Anonymize(faker="firstName")
     */
    private $firstname;
    
    /**
     * @Anonymize(faker="lastName")
     */
    private $lastname;
}
```

# Faker
The [Faker library](https://github.com/fzaninotto/Faker) is used to generate random values. 
 
## Providers
All the possible providers for `faker` option can be found here: [https://github.com/fzaninotto/Faker#formatters](https://github.com/fzaninotto/Faker#formatters)  

## Changing the locale
The default faker locale is `nl_NL`, which can be changed as shown below:
```yaml
# app/config/parameters.yml
    // ...
    ping_localhost.anonymizer_bundle.default_locale: 'en_US'
```

# License
The `ping-localhost/anonymizer-bundle` is licensed under the [MIT License](https://github.com/ping-localhost/anonymizer-bundle/blob/master/LICENSE), meaning you can reuse the code within proprietary software provided that all copies of the licensed software include a copy of the MIT License terms and the copyright notice.
