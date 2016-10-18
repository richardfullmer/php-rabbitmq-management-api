<?php

namespace RabbitMq\ManagementApi\Api;

/**
 * Connection
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Connection extends AbstractApi
{
    /**
     * A list of all open connections.
     *
     * @return array
     */
    public function all()
    {
        return $this->client->send('/api/connections');
    }

    /**
     * An individual connection.
     *
     * @param string $name
     * @return array|int
     */
    public function get($name)
    {
        return $this->client->send(sprintf('/api/connections/%s', urlencode($name)));
    }

    /**
     *  Deleting a connection will close it.
     *
     * @param string $name
     * @return array|int
     */
    public function delete($name)
    {
        return $this->client->send(sprintf('/api/connections/%s', urlencode($name)), 'DELETE');
    }
}
