<?php

namespace RabbitMq\ManagementApi\Api;

/**
 * Channel
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Consumer extends AbstractApi
{
    /**
     * A list of all open consumers.
     *
     * @return array
     */
    public function all()
    {
        return $this->client->send('/api/consumers');
    }

    /**
     * Details about an individual consumer.
     *
     * @param string $vhost
     * @return array
     */
    public function get($vhost)
    {
        return $this->client->send(sprintf('/api/consumers/%s', urlencode($vhost)));
    }
}
