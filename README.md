PHP RabbitMQ Management Api
===========================

A simple object oriented wrapper for the [RabbitMQ Management HTTP Api](http://hg.rabbitmq.com/rabbitmq-management/raw-file/rabbitmq_v3_0_3/priv/www/api/index.html) in PHP 5.3

Uses [Guzzle](http://guzzlephp.org) for REST requests.

Installation
------------

Installable through composer via:

```bash
$ composer require richardfullmer/rabbitmq-management-api
```

Basic Usage
-----------

```php
<?php

use RabbitMq\ManagementApi\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client();
$queue = $client->queues()->get('/', 'sample-messages-queue'));
$response = $client->exchanges()->publish('/', 'sample-messages', array(
    'properties' => array(),
    'routing_key' => '',
    'payload' => 'This is a test',
    'payload_encoding' => 'string'
)));

if ($response['routed']) {
    print 'Message delivered';
}

```

License
-------

php-rabbitmq-management-api is licensed under the MIT License - see the LICENSE file for details

Credits
-------

Structure from [KnpLabs php-github-api](https://github.com/KnpLabs/php-github-api)

Rabbit's Excellent Message Queue
