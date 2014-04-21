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
            return $this->client->send(array('/api/bindings/{vhost}', array('vhost' => $vhost)));
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
        return $this->client->send(array('/api/bindings/{vhost}/e/{exchange}/q/{queue}', array('vhost' => $vhost, 'exchange' => $exchange, 'queue' => $queue)));
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

        return $this->client->send(array('/api/bindings/{vhost}/e/{exchange}/q/{queue}', array('vhost' => $vhost, 'exchange' => $exchange, 'queue' => $queue)), 'POST', null, $parameters);
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
        return $this->client->send(array('/api/bindings/{vhost}/e/{exchange}/q/{queue}/{props}', array('vhost' => $vhost, 'exchange' => $exchange, 'queue' => $queue, 'props' => $props)));
    }

    /**
     * Remove an individual binding.
     *
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     * @param string $props
     * @return array
     */
    public function delete($vhost, $exchange, $queue, $props)
    {
        return $this->client->send(array('/api/bindings/{vhost}/e/{exchange}/q/{queue}/{props}', array('vhost' => $vhost, 'exchange' => $exchange, 'queue' => $queue, 'props' => $props)), 'DELETE');
    }
}
