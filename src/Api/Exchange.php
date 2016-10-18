<?php

namespace RabbitMq\ManagementApi\Api;

use RabbitMq\ManagementApi\Exception\InvalidArgumentException;

/**
 * Exchange
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Exchange extends AbstractApi
{
    /**
     * A list of all exchanges.
     *
     * OR
     *
     * A list of all exchanges in a given virtual host.
     *
     * @param null|string $vhost
     * @return array
     */
    public function all($vhost = null)
    {
        if ($vhost) {
            return $this->client->send(sprintf('/api/exchanges/%s', urlencode($vhost)));
        } else {
            return $this->client->send('/api/exchanges');
        }
    }

    /**
     * An individual exchange.
     *
     * @param string $vhost
     * @param string $name
     * @return array
     */
    public function get($vhost, $name)
    {
        return $this->client->send(sprintf('/api/exchanges/%s/%s', urlencode($vhost), urlencode($name)));
    }

    /**
     * To create an exchange, you will need a body looking something like this:
     *
     * {
     *     "type": "direct",
     *     "auto_delete": false,
     *     "durable": true,
     *     "internal": false,
     *     "arguments": []
     * }
     *
     * The 'type' key is mandatory; other keys are optional.
     *
     * @param string $vhost
     * @param string $name
     * @param array  $exchange
     * @return array
     * @throws \RabbitMq\ManagementApi\Exception\InvalidArgumentException
     */
    public function create($vhost, $name, array $exchange)
    {
        if (!isset($exchange['type'])) {
            throw new InvalidArgumentException("Error creating exchange: Exchange key 'type' is mandatory");
        }

        return $this->client->send(sprintf('/api/exchanges/%s/%s', urlencode($vhost), urlencode($name)), 'PUT', [], $exchange);
    }

    /**
     * Delete an exchange
     *
     * @param string $vhost
     * @param string $name
     * @return array
     */
    public function delete($vhost, $name)
    {
        return $this->client->send(sprintf('/api/exchanges/%s/%s', urlencode($vhost), urlencode($name)), 'DELETE');
    }

    /**
     * A list of all bindings in which a given exchange is the source.
     *
     * @param string $vhost
     * @param string $name
     * @return array
     */
    public function sourceBindings($vhost, $name)
    {
        return $this->client->send(sprintf('/api/exchanges/%s/%s/bindings/source', urlencode($vhost), urlencode($name)));
    }

    /**
     * A list of all bindings in which a given exchange is the destination.
     *
     * @param string $vhost
     * @param string $name
     * @return array
     */
    public function destinationBindings($vhost, $name)
    {
        return $this->client->send(sprintf('/api/exchanges/%s/%s/bindings/destination', urlencode($vhost), urlencode($name)));
    }

    /**
     * Publish a message to a given exchange. You will need a body looking something like:
     *
     * {
     *     "properties": {},
     *     "routing_key": "my key",
     *     "payload": "my body",
     *     "payload_encoding":"string"
     * }
     *
     * All keys are mandatory. The payload_encoding key should be either "string" (in which case the payload will be
     * taken to be the UTF-8 encoding of the payload field) or "base64" (in which case the payload field is taken to be
     * base64 encoded).
     *
     * If the message is published successfully, the response will look like:
     *
     * {"routed": true}
     *
     * routed will be true if the message was sent to at least one queue.
     *
     * Please note that the publish / get paths in the HTTP API are intended for injecting test messages, diagnostics etc - they do not implement reliable delivery and so should be treated as a sysadmin's tool rather than a general API for messaging.
     *
     * @param string $vhost
     * @param string $name
     * @param array $message
     * @throws InvalidArgumentException
     * @return array
     */
    public function publish($vhost, $name, array $message)
    {
        if (!isset($message['properties'])) {
            throw new InvalidArgumentException("Error publishing to exchange: Message key 'properties' is mandatory");
        } elseif (!isset($message['routing_key'])) {
            throw new InvalidArgumentException("Error publishing to exchange: Message key 'routing_key' is mandatory");
        } elseif (!isset($message['payload'])) {
            throw new InvalidArgumentException("Error publishing to exchange: Message key 'payload' is mandatory");
        } elseif (!isset($message['payload_encoding'])) {
            throw new InvalidArgumentException("Error publishing to exchange: Message key 'payload_encoding' is mandatory");
        }

        return $this->client->send(sprintf('/api/exchanges/%s/%s/publish', urlencode($vhost), urlencode($name)), 'POST', [], $message);
    }
}
