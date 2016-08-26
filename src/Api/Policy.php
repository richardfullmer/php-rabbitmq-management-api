<?php

namespace RabbitMq\ManagementApi\Api;

/**
 * Policy
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Policy extends AbstractApi
{
    /**
     * A list of all policies.
     *
     * @return array
     */
    public function all()
    {
        return $this->client->send('/api/policies');
    }

    /**
     * A list of all policies in a given virtual host.
     *
     * OR
     *
     * An individual policy.
     *
     * @param string $vhost
     * @param string|null $name
     * @return array
     */
    public function get($vhost, $name = null)
    {
        if ($name) {
            return $this->client->send(sprintf('/api/policies/%s/%s', urlencode($vhost), urlencode($name)));
        }

        return $this->client->send(sprintf('/api/policies/%s', urlencode($vhost)));
    }

    /**
     * To PUT a policy, you will need a body looking something like this:
     *
     * {"pattern":"^amq.", "definition": {"federation-upstream-set":"all"}, "priority":0}
     *
     * @param string $vhost
     * @param string $name
     * @param array $policy
     * @return array
     */
    public function create($vhost, $name, array $policy)
    {
        return $this->client->send(sprintf('/api/policies/%s/%s', urlencode($vhost), urlencode($name)), 'PUT', [], $policy);
    }

    /**
     * Delete a policy
     *
     * @param string $vhost
     * @param string $name
     * @return array
     */
    public function delete($vhost, $name)
    {
        return $this->client->send(sprintf('/api/policies/%s/%s', urlencode($vhost), urlencode($name)), 'DELETE');
    }
}
