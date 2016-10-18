<?php

namespace RabbitMq\ManagementApi\Api;

/**
 * User
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class User extends AbstractApi
{
    /**
     * A list of all users.
     *
     * @return array
     */
    public function all()
    {
        return $this->client->send('/api/users');
    }

    /**
     * An individual user.
     *
     * @param string $name
     * @return array
     */
    public function get($name)
    {
        return $this->client->send(sprintf('/api/users/%s', urlencode($name)));
    }

    /**
     *  To PUT a user, you will need a body looking something like this:
     *
     * {"password":"secret","tags":"administrator"}
     *
     * or:
     *
     * {"password_hash":"2lmoth8l4H0DViLaK9Fxi6l9ds8=", "tags":"administrator"}
     *
     * The tags key is mandatory. Either password or password_hash must be set. Setting password_hash to "" will
     * ensure the user cannot use a password to log in. tags is a comma-separated list of tags for the user. Currently
     * recognised tags are "administrator", "monitoring" and "management".
     *
     * @param string $name
     * @param array $user
     * @return mixed
     */
    public function create($name, array $user)
    {
        return $this->client->send(sprintf('/api/users/%s', urlencode($name)), 'PUT', [], $user);
    }

    /**
     * Delete a user.
     *
     * @param string $name
     * @return array
     */
    public function delete($name)
    {
        return $this->client->send(sprintf('/api/users/%s', urlencode($name)), 'DELETE');
    }

    /**
     * A list of all permissions for a given user.
     *
     * @param string $name
     * @return array
     */
    public function permissions($name)
    {
        return $this->client->send(sprintf('/api/users/%s/permissions', urlencode($name)));
    }
}
