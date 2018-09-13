<?php
namespace SkyForge;

class RestEndpoint
{
    /**
     * @var String $api_prefix
     */
    public $api_prefix = 'wp';

    /**
     * @var String $api_version
     */
    public $api_version = '2';

    /**
     * @var String $api_namespace
     */
    public $api_namespace;

    /**
     * @var String $api_endpoint
     */
    public $api_endpoint;

    /**
     * @var String $permalink
     */
    public $permalink;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->api_namespace = "/{$this->api_prefix}/v{$this->api_version}";
        $this->permalink = get_option('permalink_structure');
    }

    /**
     * Get and Endpoint based on provided slug
     *
     * @param String $slug
     *
     * @return String
     * @throws \Exception
     */
    public function getEndpoint(string $slug) : string
    {
        echo "----------- BMO $slug : {$this->permalink} ------------------\n";
        return "{$this->api_namespace}/posts";
    }
}
