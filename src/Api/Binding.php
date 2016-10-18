<?php

namespace RabbitMq\ManagementApi\Api;

/**
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Binding extends AbstractApi
{
    /**
     * A list of all bindings.
     *
     * OR
     *
     * A list of all bindings in a given virtual host.
     *
     * @param string|null $vhost
     * @return array
     */
    public function all($vhost = null)
    {
        if ($vhost) {
            return $this->client->send(sprintf('/api/bindings/%s', urlencode($vhost)));
        } else {
            return $this->client->send('/api/bindings');
        }
    }

    /**
     * A list of all bindings between an exchange and a queue. Remember, an exchange and a queue can be bound together
     * many times!
     *
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     * @return array
     */
    public function binding($vhost, $exchange, $queue)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/q/%s', urlencode($vhost), urlencode($exchange), urlencode($queue)));
    }

    /**
     * A list of all bindings between two exchanges. Remember, two exchanges can be bound together many times with
     * different parameters!
     *
     * @param string $vhost
     * @param string $source
     * @param string $destination
     *
     * @return array
     */
    public function exchangeBinding($vhost, $source, $destination)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/e/%s', urlencode($vhost), urlencode($source), urlencode($destination)));
    }

    /**
     *  To create a new binding, POST to this URI. You will need a body looking something like this:
     *
     * {
     *     "routing_key": "my_routing_key",
     *     "arguments": []
     * }
     *
     * All keys are optional. The response will contain a Location header telling you the URI of your new binding.
     *
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     * @param string|null $routingKey
     * @param array|null $arguments
     * @return array
     */
    public function create($vhost, $exchange, $queue, $routingKey = null, array $arguments = null)
    {
        $parameters = array();

        if ($routingKey) {
            $parameters['routing_key'] = $routingKey;
        } else {
            $parameters['routing_key'] = '';
        }
        if ($arguments) {
            $parameters['arguments'] = $arguments;
        }

        return $this->client->send(sprintf('/api/bindings/%s/e/%s/q/%s', urlencode($vhost), urlencode($exchange), urlencode($queue)), 'POST', [], $parameters);
    }

    /**
     *  To create a new exchange to exchange binding, POST to this URI. You will need a body looking something like this:
     *
     * {
     *     "routing_key": "my_routing_key",
     *     "arguments": []
     * }
     *
     * All keys are optional. The response will contain a Location header telling you the URI of your new binding.
     *
     * @param string $vhost
     * @param string $source
     * @param string $destination
     * @param string|null $routingKey
     * @param array|null $arguments
     * @return array
     */
    public function createExchange($vhost, $source, $destination, $routingKey = null, array $arguments = null)
    {
        $parameters = array();

        if ($routingKey) {
            $parameters['routing_key'] = $routingKey;
        } else {
            $parameters['routing_key'] = '';
        }
        if ($arguments) {
            $parameters['arguments'] = $arguments;
        }

        return $this->client->send(sprintf('/api/bindings/%s/e/%s/e/%s', urlencode($vhost), urlencode($source), urlencode($destination)), 'POST', [], $parameters);
    }

    /**
     * An individual binding between an exchange and a queue. The props part of the URI is a "name" for the binding
     * composed of its routing key and a hash of its arguments.
     *
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     * @param string $props
     * @return array
     */
    public function get($vhost, $exchange, $queue, $props)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/q/%s/%s', urlencode($vhost), urlencode($exchange), urlencode($queue), urlencode($props)));
    }

    /**
     * An individual binding between two exchanges. The props part of the URI is a "name" for the binding
     * composed of its routing key and a hash of its arguments.
     *
     * @param string $vhost
     * @param string $source
     * @param string $destination
     * @param string $props
     * @return array
     */
    public function getExchange($vhost, $source, $destination, $props)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/e/%s/%s', urlencode($vhost), urlencode($source), urlencode($destination), urlencode($props)));
    }

    /**
     * Remove an individual binding between an exchange and a queue.
     *
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     * @param string $props
     * @return array
     */
    public function delete($vhost, $exchange, $queue, $props)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/q/%s/%s', urlencode($vhost), urlencode($exchange), urlencode($queue), urlencode($props)), 'DELETE');
    }

    /**
     * Remove an individual binding between two exchanges.
     *
     * @param string $vhost
     * @param string $source
     * @param string $destination
     * @param string $props
     * @return array
     */
    public function deleteExchange($vhost, $source, $destination, $props)
    {
        return $this->client->send(sprintf('/api/bindings/%s/e/%s/e/%s/%s', urlencode($vhost), urlencode($source), urlencode($destination), urlencode($props)), 'DELETE');
    }
}
