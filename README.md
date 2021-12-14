
[![Build Status](https://app.travis-ci.com/b2pweb/bdf-serializer-bundle.svg?branch=master)](https://app.travis-ci.com/b2pweb/bdf-serializer-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/b2pweb/bdf-serializer-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/b2pweb/bdf-serializer-bundle/?branch=master)
[![Packagist Version](https://img.shields.io/packagist/v/b2pweb/bdf-serializer-bundle.svg)](https://packagist.org/packages/b2pweb/bdf-serializer-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/b2pweb/bdf-serializer-bundle.svg)](https://packagist.org/packages/b2pweb/bdf-serializer-bundle)

Installation
============

1 Download the Bundle
---------------------

Download the latest stable version of this bundle with composer:

```bash
    $ composer require b2pweb/bdf-serializer-bundle
```

2 Enable the Bundle
-------------------

Adding the following line in the ``config/bundles.php`` file of your project::

```php
<?php
// config/bundles.php

return [
    // ...
    Bdf\SerializerBundle\BdfSerializerBundle::class => ['all' => true],
    // ...
];
```

3 Add configuration
-------------------

Add a default config file to `./config/packages/bdf_serializer.yaml`.

Enable caching for production

```yaml
bdf_serializer:
  cache:
    pool: 'cache.app'
```

Add a test file to `./config/packages/test/bdf_serializer.yaml`

```yaml
bdf_serializer:
  cache:
    pool: null
```
