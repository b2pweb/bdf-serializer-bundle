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
