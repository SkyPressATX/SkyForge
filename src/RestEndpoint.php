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
     * @param Int $id
     * @param String $type
     *
     * @return String
     * @throws \Exception
     */
    public function getEndpoint(int $id = null, string $type) : string
    {
        $type = $this->getRestBase($type);
        $endpoint = "{$this->api_namespace}/{$type}";
        if (null !== $id || (int)0 !== $id) {
            $endpoint .= "/$id";
        }

        return $endpoint;
    }

    /**
     * Get Rest API Base slug
     *
     * @param String $type
     *
     * @return String
     */
    public function getRestBase(string $type) : string
    {
        $type_object = get_post_type_object($type);
        return (empty((array)$type_object) || ! property_exists($type_object, 'rest_base')) ? $type : strtolower($type_object->rest_base);
    }
}
