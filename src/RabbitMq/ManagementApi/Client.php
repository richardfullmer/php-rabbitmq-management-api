<?php

namespace RabbitMq\ManagementApi;

use Guzzle\Http\Client as GuzzleHttpClient;

/**
 * ManagementApi
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Client
{
    protected $client;
    protected $username;
    protected $password;

    /**
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     */
    /**
     * @param \Guzzle\Http\Client $client
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     */
    public function __construct(GuzzleHttpClient $client = null, $baseUrl = 'http://localhost:15672', $username = 'guest', $password = 'guest')
    {
        $this->client = $client ?: new GuzzleHttpClient();
        $this->client->setBaseUrl($baseUrl);
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Declares a test queue, then publishes and consumes a message. Intended for use by monitoring tools. If
     * everything is working correctly, will return HTTP status 200 with body:
     *
     * {"status":"ok"}
     *
     * Note: the test queue will not be deleted (to to prevent queue churn if this is repeatedly pinged).
     *
     * @param string $vhost
     * @return array
     */
    public function alivenessTest($vhost)
    {
        return $this->send(array('/api/aliveness-test/{vhost}', array('vhost' => $vhost)));
    }

    /**
     * Various random bits of information that describe the whole system.
     *
     * @return array
     */
    public function overview()
    {
        return $this->send('/api/overview');
    }

    /**
     * A list of extensions to the management plugin.
     *
     * @return array
     */
    public function extensions()
    {
        return $this->send('/api/extensions');
    }

    /**
     * The server definitions - exchanges, queues, bindings, users, virtual hosts, permissions and parameters.
     *
     * Everything apart from messages. POST to upload an existing set of definitions. Note that:
     *
     * - The definitions are merged. Anything already existing is untouched.
     * - Conflicts will cause an error.
     * - In the event of an error you will be left with a part-applied set of definitions.
     *
     * For convenience you may upload a file from a browser to this URI (i.e. you can use multipart/form-data as well as
     * application/json) in which case the definitions should be uploaded as a form field named "file".
     *
     * @return mixed
     */
    public function definitions()
    {
        return $this->send('/api/definitions');
    }

    /**
     * @return Api\Connection
     */
    public function connections()
    {
        return new Api\Connection($this);
    }

    /**
     * @return Api\Channel
     */
    public function channels()
    {
        return new Api\Channel($this);
    }

    /**
     * @return Api\Exchange
     */
    public function exchanges()
    {
        return new Api\Exchange($this);
    }

    /**
     * @return Api\Queue
     */
    public function queues()
    {
        return new Api\Queue($this);
    }

    /**
     * @return Api\Vhost
     */
    public function vhosts()
    {
        return new Api\Vhost($this);
    }

    /**
     * @return Api\Binding
     */
    public function bindings()
    {
        return new Api\Binding($this);
    }

    /**
     * @return Api\User
     */
    public function users()
    {
        return new Api\User($this);
    }

    /**
     * @return Api\Permission
     */
    public function permissions()
    {
        return new Api\Permission($this);
    }

    /**
     * @return Api\Parameter
     */
    public function parameters()
    {
        return new Api\Parameter($this);
    }

    /**
     * @return Api\Policy
     */
    public function policies()
    {
        return new Api\Policy($this);
    }

    /**
     * @return array
     */
    public function whoami()
    {
        return $this->send('/api/whoami');
    }

    /**
     * @param string|array          $endpoint Resource URI.
     * @param string                $method
     * @param array                 $headers  HTTP headers
     * @param string|resource|array $body     Entity body of request (POST/PUT) or response (GET)
     * @return array
     */
    public function send($endpoint, $method = 'GET', $headers = null, $body = null)
    {
        if (null !== $body) {
            $body = json_encode($body);
        }
        
        $request = $this->client->createRequest($method, $endpoint, $headers, $body)->setAuth($this->username, $this->password);
        
        if (in_array($method, array('PUT', 'POST', 'DELETE'))) {
            $request->setHeader('content-type', 'application/json');
        }
        
        $response = $request->send();

        return json_decode($response->getBody(), true);
    }


}
